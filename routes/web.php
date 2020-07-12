<?php

use App\Question;
use App\Quiz;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $prizes = config('prizes');

    $distributions = $prizes[2];

    $data = collect($distributions)->map(function ($distribution, $index) {
        return [
            'id' => Str::uuid(),
            'quiz_info_id' => Str::uuid(),
            'rank' => $index,
            'prize' => $distribution,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    })
    ->toArray();

    return $data;
});
