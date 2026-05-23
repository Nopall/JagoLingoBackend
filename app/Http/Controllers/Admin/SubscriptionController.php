<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\SubscriptionDataTable;
use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Payment;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $users = \App\Models\User::where('is_deleted', 0)
            ->whereHas('subscriptions')
            ->when($search, function ($q) use ($search) {
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->with(['subscriptions' => function ($q) {
                $q->with('payment.package')->orderBy('created_at', 'desc');
            }])
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('subscription.list', compact('users', 'search'));
    }

    public function show($id)
    {
        try {
            $subscription = Subscription::with(['user', 'package', 'payment'])
                ->findOrFail($id);
            
            // Check jika user sudah dihapus
            if ($subscription->user && $subscription->user->is_deleted) {
                return redirect()->back()->with('error', 'This subscription belongs to a deleted user.');
            }
            
            return view('subscription.detail', compact('subscription'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Subscription not found.');
        }
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

    // Method untuk melihat semua subscription (termasuk yang belum bayar)
    public function allSubscriptions(SubscriptionDataTable $dataTable)
    {
        return $dataTable->render('subscription.all-list');
    }
}