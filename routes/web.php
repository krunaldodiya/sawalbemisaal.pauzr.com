<?php

use App\QuizRanking;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request) {
    if ($request->action === "truncate") {
        DB::table('quiz_rankings_improved')->truncate();
    }

    if ($request->action === "insert") {
        $data = QuizRanking::query()
            ->where('prize', '>', 0)
            ->where(function ($query) use ($request) {
                if ($request->period !== "All Time") {
                    return $query->where(
                        'created_at',
                        '>',
                        $request->period === "This Month" ? now()->startOfMonth() : now()->startOfDay()
                    );
                }
            })
            ->get()
            ->groupBy('user_id')
            ->map(function ($collection) {
                return [
                    'id' => Illuminate\Support\Str::uuid(),
                    'user_id' => $collection[0]->user_id,
                    'prize' => $collection->sum('prize'),
                    'period' => now()->endOfMonth(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

        DB::table('quiz_rankings_improved')->insert($data);
    }

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
