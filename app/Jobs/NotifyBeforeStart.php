<?php

namespace App\Jobs;

use App\Quiz;
use App\Repositories\PushNotificationRepositoryInterface;
use App\Topic;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotifyBeforeStart implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $quiz;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(PushNotificationRepositoryInterface $pushNotificationRepositoryInterface)
    {
        $topic = Topic::where(['notifiable_type' => 'quiz', 'notifiable_id' => $this->quiz->id])->first();

        dump([
            'title' => 'Quiz will start in few minutes',
            'body' => "Everyone is preparing, are you?",
            'image' => url('images/notify_soon.jpg'),
            'quiz_id' => $this->quiz->id,
        ]);

        // $pushNotificationRepositoryInterface->notify("/topics/{$topic->name}", [
        //     'title' => 'Quiz will start in few minutes',
        //     'body' => "Everyone is preparing, are you?",
        //     'image' => url('images/notify_soon.jpg'),
        //     'quiz_id' => $this->quiz->id,
        // ]);
    }
}
