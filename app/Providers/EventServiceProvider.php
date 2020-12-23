<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;

use Illuminate\Auth\Listeners\SendEmailVerificationNotification;

use App\Events\TopicSubscribed;
use App\Events\QuizGenerated;

use App\Listeners\AddWalletBonusPoints;
use App\Listeners\CheckInvitation;
use App\Listeners\CreateUserTopics;
use App\Listeners\SubscribeToTopics;
use App\Listeners\ManageTopicSubscription;

use App\Listeners\GenerateQuizQuestions;
use App\Listeners\GenerateQuizTopic;
use App\Listeners\GenerateQuizNotification;
use App\Listeners\NotifyFollowers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            AddWalletBonusPoints::class,
            CheckInvitation::class,
            CreateUserTopics::class,
            SubscribeToTopics::class,
        ],

        TopicSubscribed::class => [
            ManageTopicSubscription::class,
        ],

        QuizGenerated::class => [
            GenerateQuizQuestions::class,
            GenerateQuizTopic::class,
            GenerateQuizNotification::class,
            NotifyFollowers::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
