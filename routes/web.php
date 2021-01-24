<?php

use App\QuizRanking;

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request) {
    $periods = [now()->subYears(100), now()->startOfMonth(), now()->startOfDay()];

    DB::table('quiz_rankings_improved')->truncate();

    collect($periods)
        ->each(function ($period) {
            $data = QuizRanking::query()
                ->where('prize', '>', 0)
                ->where(function ($query) use ($period) {
                    return $query->where('created_at', '>', $period);
                })
                ->get()
                ->groupBy('user_id')
                ->map(function ($collection, $index) {
                    $text = "ALL_TIME";
                    if ($index === 1) {
                        $text = now()->endOfMonth();
                    } else if ($index === 2) {
                        $text = now()->endOfDay();
                    }
                    return [
                        'id' => Illuminate\Support\Str::uuid(),
                        'user_id' => $collection[0]->user_id,
                        'prize' => $collection->sum('prize'),
                        'period' => $text,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->toArray();

            DB::table('quiz_rankings_improved')->insert($data);
        });

    return 'done';
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/app/download', "HomeController@downloadApp");
Route::get('/media/{media}', 'HomeController@getMediaFile');
