<?php

namespace App\Http\Controllers;

use App\Http\Requests\Withdraw;

use App\Redeem;

use Error;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function getWithdrawHistory(Request $request)
    {
        $history = Redeem::where('user_id', auth()->id())->get();

        return response(['history' => $history], 200);
    }

    public function withdrawAmount(Withdraw $request)
    {
        $user = auth()->user();

        $coins = $user->wallet->balance;

        if ($request->coins < 20) {
            throw new Error("You can't redeem less than 20 coins");
        }

        if ($coins < $request->coins) {
            throw new Error("Not Enough Coins");
        }

        $transaction = $user->createTransaction($request->coins, 'withdraw', [
            'points' => [
                'id' => $user->id,
                'type' => "Withdraw Money"
            ]
        ]);

        $user->deposit($transaction->transaction_id);

        $redeem = Redeem::create([
            'user_id' => $user->id,
            'gateway' => $request->gateway,
            'mobile' => $request->mobile,
            'amount' => $coins * 0.4,
            'status' => 'pending'
        ]);

        return compact('redeem');
    }
}
