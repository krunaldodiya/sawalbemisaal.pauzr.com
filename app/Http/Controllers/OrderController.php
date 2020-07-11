<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrder;

use App\Order;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function createOrder(CreateOrder $request)
    {
        $user = auth()->user();

        $subscription = Order::create([
            'user_id' => $user->id,
            'plan_id' => $request->plan_id,
            'payment_id' => $request->payment_id,
            'status' => $request->status
        ]);

        return compact('subscription');
    }
}
