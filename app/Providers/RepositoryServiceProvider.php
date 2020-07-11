<?php

namespace App\Providers;

use App\Repositories\OtpRepository;
use App\Repositories\OtpRepositoryInterface;

use App\Repositories\PushNotificationRepository;
use App\Repositories\PushNotificationRepositoryInterface;

use App\Repositories\QuizRepository;
use App\Repositories\QuizRepositoryInterface;

use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        app()->bind(UserRepositoryInterface::class, UserRepository::class);
        app()->bind(PushNotificationRepositoryInterface::class, PushNotificationRepository::class);
        app()->bind(OtpRepositoryInterface::class, OtpRepository::class);
        app()->bind(QuizRepositoryInterface::class, QuizRepository::class);
    }
}
