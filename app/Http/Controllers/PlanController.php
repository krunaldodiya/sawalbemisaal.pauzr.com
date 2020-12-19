<?php

namespace App\Http\Controllers;

use App\Order;
use App\Plan;

use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function getPlans(Request $request)
    {
        $plans = Plan::orderBy('amount', 'asc')->get();

        return compact('plans');
    }

    public function purchasePlan(Request $request)
    {
        $user = auth()->user();

        $plan = Plan::find($request->plan_id);

        $transaction = $user->createTransaction($plan->coins, 'deposit', [
            'points' => [
                'id' => $user->id,
                'type' => "purchased_coins"
            ]
        ]);

        $user->deposit($transaction->transaction_id);

        $subscription = Order::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'payment_id' => $request->transaction_id,
            'status' => true
        ]);

        if ($subscription) {
            return response(['success' => true], 200);
        }

        return response(['success' => false], 400);
    }
}
