<?php

namespace App\Providers;

use App\DeviceToken;
use App\Observers\DeviceTokenObserver;

use App\Quiz;
use App\Observers\QuizObserver;

use App\QuizParticipant;
use App\Observers\QuizParticipantObserver;
use App\Observers\QuizRankingObserver;
use App\User;
use App\Observers\UserObserver;
use App\QuizRanking;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        DeviceToken::observe(DeviceTokenObserver::class);
        Quiz::observe(QuizObserver::class);
        User::observe(UserObserver::class);
        QuizParticipant::observe(QuizParticipantObserver::class);
        QuizRanking::observe(QuizRankingObserver::class);
    }
}
