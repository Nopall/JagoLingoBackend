<?php

namespace App\Http\Controllers\API;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends BaseController
{
    // Mengecek status langganan user
    public function status()
    {
        $subscription = Subscription::where('user_id', Auth::id())->get();

        // if ($subscription && $subscription->is_active) {
        //     return response()->json(['status' => 'active'], 200);
        // }

        return response()->json($subscription, 200);
    }

    // Membatalkan langganan (opsional)
    public function cancel()
    {
        $subscription = Subscription::where('user_id', Auth::id())->first();

        if ($subscription) {
            $subscription->update(['is_active' => false]);
            return response()->json(['message' => 'Subscription cancelled'], 200);
        }

        return response()->json(['message' => 'No active subscription found'], 404);
    }
}
