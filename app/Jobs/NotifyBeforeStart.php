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
        $joined_participants = $this->quiz->participants->count();

        $needed_participants = $this->quiz->quiz_infos->total_participants - $joined_participants;

        $topic = Topic::where(['notifiable_type' => 'quiz', 'notifiable_id' => $this->quiz->id])->first();

        $pushNotificationRepositoryInterface->notify("/topics/{$topic->name}", [
            'title_key' => 'will_start_in_few_minutes_title',
            'body_key' => 'will_start_in_few_minutes_body',
            'title' => "Quiz #{$this->quiz->title} will start in few minutes",
            'body' => "Everyone is preparing, are you?",
            'image' => url('images/notify_soon.jpg'),
            'quiz_id' => $this->quiz->id,
            'show_alert_box' => true
        ]);

        if ($needed_participants > 0) {
            $pushNotificationRepositoryInterface->notify("/topics/user_{$this->quiz->host_id}", [
                'title_key' => 'start_inviting_people_title',
                'body_key' => 'start_inviting_people_body',
                'title' => "Spread your QuizID: #{$this->quiz->title}",
                'body' => "{$joined_participants} people have joined your Quiz. You need {$needed_participants} more it to make it successful.",
                'image' => url('images/notify_soon.jpg'),
                'quiz_id' => $this->quiz->id,
                'show_alert_box' => false
            ]);
        }
    }
}
