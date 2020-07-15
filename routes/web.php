<?php

use App\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $participants = User::inRandomOrder()
            ->limit(5)
            ->get();

    return $participants;
});
