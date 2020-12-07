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

        $users = User::with(['country', 'wallet.transactions', 'quiz_rankings'])->get();

        $rankings = $users
            ->map(function ($user) use ($period) {
                $quiz_rankings = $user->quiz_rankings()->where(function ($query) use ($period) {
                    return $query->where('created_at', '>=', $period);
                });

                return [
                    'user' => $user,
                    'prize' => $quiz_rankings->sum('prize')
                ];
            })
            ->toArray();

        return response(['rankings' => $rankings], 200);
    }
}
