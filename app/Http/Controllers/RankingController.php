<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetRankings;
use App\User;

use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function getRankings(GetRankings $request)
    {
        $period = User::filterPeriod($request->period);

        $users = User::query()
            ->with([
                'country',
                'wallet.transactions',
                'quiz_rankings' => function ($query) use ($period) {
                    return $query->where(function ($query) use ($period) {
                        return $query->where('created_at', '>=', $period);
                    });
                }
            ])
            ->get();

        $rankings = $users
            ->map(function ($user) use ($period) {
                return [
                    'user' => $user,
                    'prize' => $user->quiz_rankings->sum('prize'),
                    'period' => $period
                ];
            })
            ->toArray();

        return response(['rankings' => $rankings], 200);
    }
}
