<?php

namespace App\Http\Controllers;

use App\Http\Requests\Withdraw;
use App\Http\Resources\RedeemResourceCollection;
use App\Redeem;

use Error;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public   function onSuccess(Request $request)
    {
        dump($request->all());
    }

    public function proofs(Request $request)
    {
        $payments = Redeem::query()
            ->where('status', 'success')
            ->where('image', '!=', null)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return RedeemResourceCollection::collection($payments)->response();
    }

    public function getWithdrawHistory(Request $request)
    {
        $history = Redeem::where('user_id', auth()->id())->get();

        return response(['history' => $history], 200);
    }

    public function withdrawAmount(Withdraw $request)
    {
        $user = auth()->user();

        $coins = $user->wallet->balance;

        $redeemable_coins = $request->coins;

        if ($redeemable_coins < 20) {
            throw new Error("minimum_redeem_coins_error");
        }

        if ($coins < $redeemable_coins) {
            throw new Error("not_enough_wallet_points");
        }

        $transaction = $user->createTransaction($redeemable_coins, 'withdraw', [
            'points' => [
                'id' => $user->id,
                'type' => "withdraw_money"
            ]
        ]);

        $user->deposit($transaction->transaction_id);

        $redeem = Redeem::create([
            'user_id' => $user->id,
            'gateway' => $request->gateway,
            'mobile' => $request->mobile,
            'coins' => $redeemable_coins,
            'amount' => $redeemable_coins * 0.4,
            'status' => 'pending'
        ]);

        return compact('redeem');
    }
}
