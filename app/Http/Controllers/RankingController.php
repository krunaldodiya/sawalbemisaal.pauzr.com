<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetRankings;
use App\User;

use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function filterPeriod($period)
    {
        switch ($period) {
            case 'Today':
                return now()->startOfDay();

            case 'This Week':
                return now()->startOfWeek();

            case 'This Month':
                return now()->startOfMonth();

            default:
                return now()->startOfDay();
        }
    }

    public function getRankings(GetRankings $request)
    {
        $period = $this->filterPeriod($request->period);

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
