<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubscriptionDataTable;
use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $filter = $request->input('filter', 'all'); // all | active | inactive

        $users = \App\Models\User::where('is_deleted', 0)
            ->when($filter === 'all', fn($q) => $q->whereHas('subscriptions'))
            ->when($filter === 'active', fn($q) => $q->whereHas('subscriptions', fn($q2) => $q2->where('is_active', 1)))
            ->when($filter === 'inactive', fn($q) => $q->whereHas('subscriptions', fn($q2) => $q2->where('is_active', 0))->whereDoesntHave('subscriptions', fn($q2) => $q2->where('is_active', 1)))
            ->when($search, fn($q) => $q->where(fn($q2) => $q2->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%")))
            ->with(['subscriptions' => fn($q) => $q->with('payment.package')->orderBy('created_at', 'desc')])
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        // Hitung tiap status untuk badge counter
        $countAll      = User::where('is_deleted', 0)->whereHas('subscriptions')->count();
        $countActive   = User::where('is_deleted', 0)->whereHas('subscriptions', fn($q) => $q->where('is_active', 1))->count();
        $countInactive = $countAll - $countActive;

        // Data untuk modal Beri Akses
        $allUsers  = User::where('is_deleted', 0)->orderBy('name')->get(['id', 'name', 'email']);
        $packages  = Package::where('is_active', 1)->orderBy('name')->get(['id', 'name', 'price']);

        return view('subscription.list', compact(
            'users', 'search', 'filter',
            'countAll', 'countActive', 'countInactive',
            'allUsers', 'packages'
        ));
    }

    public function show($id)
    {
        $subscription = Subscription::with(['user', 'payment.package'])
            ->findOrFail($id);

        // Ambil semua subscription milik user yang sama
        $allSubscriptions = Subscription::with('payment.package')
            ->where('user_id', $subscription->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('subscription.detail', compact('subscription', 'allSubscriptions'));
    }

    public function activateSubscription($id)
    {
        try {
            $subscription = Subscription::with(['user', 'payment'])
                ->findOrFail($id);
            
            // Validasi user exists dan tidak deleted
            if (!$subscription->user || $subscription->user->is_deleted) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot activate subscription for deleted user.',
                ], 400);
            }
            
            // Validasi payment exists dan sudah paid
            if (!$subscription->payment || $subscription->payment->status != 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment must be completed before activation.',
                ], 400);
            }
            
            $subscription->activate();

            return response()->json([
                'status' => true,
                'message' => 'Subscription activated successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to activate subscription: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function cancelSubscription($id)
    {
        try {
            $subscription = Subscription::with(['user'])
                ->findOrFail($id);
            
            // Validasi user exists
            if (!$subscription->user || $subscription->user->is_deleted) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cannot cancel subscription for deleted user.',
                ], 400);
            }
            
            $subscription->deactivate();

            return response()->json([
                'status' => true,
                'message' => 'Subscription cancelled successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to cancel subscription: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteSubscription($id)
    {
        try {
            $subscription = Subscription::findOrFail($id);
            
            // Soft check - boleh delete meskipun user sudah dihapus
            if ($subscription->user && !$subscription->user->is_deleted) {
                // Update user premium status jika user masih ada
                $subscription->user->update(['is_premium' => 0]);
            }
            
            $subscription->delete();

            return response()->json([
                'status' => true,
                'message' => 'Subscription deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete subscription: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function grantAccess(Request $request)
    {
        $request->validate([
            'user_id'    => 'required|exists:users,id',
            'package_id' => 'required|exists:package,id',
        ]);

        $user    = User::findOrFail($request->user_id);
        $package = Package::findOrFail($request->package_id);

        // Cek apakah user sudah punya subscription aktif untuk paket ini
        $alreadyActive = Subscription::where('user_id', $user->id)
            ->where('is_active', 1)
            ->whereHas('payment', fn($q) => $q->where('package_id', $package->id))
            ->exists();

        if ($alreadyActive) {
            return response()->json([
                'status'  => false,
                'message' => "User sudah memiliki akses aktif ke paket \"{$package->name}\".",
            ], 422);
        }

        DB::transaction(function () use ($user, $package) {
            // Buat payment dengan status paid (1)
            $payment = Payment::create([
                'user_id'        => $user->id,
                'package_id'     => $package->id,
                'amount'         => $package->price,
                'status'         => Payment::STATUS_PAID,
                'transaction_id' => 'ADMIN-' . strtoupper(uniqid()),
                'paid_at'        => now(),
            ]);

            // Buat subscription aktif
            Subscription::create([
                'user_id'    => $user->id,
                'payment_id' => $payment->id,
                'is_active'  => 1,
            ]);

            // Tandai user sebagai premium
            $user->update(['is_premium' => 1]);
        });

        return response()->json([
            'status'  => true,
            'message' => "Akses ke paket \"{$package->name}\" berhasil diberikan kepada {$user->name}.",
        ]);
    }

    // Method untuk melihat semua subscription (termasuk yang belum bayar)
    public function allSubscriptions(SubscriptionDataTable $dataTable)
    {
        return $dataTable->render('subscription.all-list');
    }
}