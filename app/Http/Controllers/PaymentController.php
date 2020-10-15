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

        $cash = $user->wallet->balance / 2;

        if ($request->amount < 20) {
            throw new Error("You can't withdraw less than Rs. 20");
        }

        if ($cash < $request->amount) {
            throw new Error("Not Enough Balance");
        }

        $transaction = $user->createTransaction($request->amount * 2, 'withdraw', [
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
            'amount' => $request->amount,
            'status' => 'pending'
        ]);

        return compact('redeem');
    }
}
