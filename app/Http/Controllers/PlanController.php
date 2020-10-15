<?php

namespace App\Http\Controllers;

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
                'type' => "Purchased Coins"
            ]
        ]);

        $user->deposit($transaction->transaction_id);

        return response(['success' => true], 200);
    }
}
