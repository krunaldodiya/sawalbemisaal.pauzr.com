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
}
