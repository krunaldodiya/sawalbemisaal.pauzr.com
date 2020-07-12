<?php

namespace App\Observers;

use App\QuizInfo;

class QuizInfoObserver
{
    public function generate($quizInfo)
    {
        $prizes = config('prizes');

        $distributions = $prizes[$quizInfo->total_participants];

        $data = collect($distributions)->map(function ($distribution, $index) {
            return [
                'id' => Str::uuid(),
                'quiz_info_id' => $quizInfo->id,
                'rank' => $index,
                'prize' => $distribution,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        });

        PrizeDistribution::create($data);
    }

    /**
     * Handle the quiz info "created" event.
     *
     * @param  \App\QuizInfo  $quizInfo
     * @return void
     */
    public function created(QuizInfo $quizInfo)
    {
        return $this->generate($quizInfo);
    }

    /**
     * Handle the quiz info "updated" event.
     *
     * @param  \App\QuizInfo  $quizInfo
     * @return void
     */
    public function updated(QuizInfo $quizInfo)
    {
        PrizeDistribution::where('quiz_info_id', $quizInfo->id)->delete();

        return $this->generate($quizInfo);
    }

    /**
     * Handle the quiz info "deleted" event.
     *
     * @param  \App\QuizInfo  $quizInfo
     * @return void
     */
    public function deleted(QuizInfo $quizInfo)
    {
        PrizeDistribution::where('quiz_info_id', $quizInfo->id)->delete();
    }

    /**
     * Handle the quiz info "restored" event.
     *
     * @param  \App\QuizInfo  $quizInfo
     * @return void
     */
    public function restored(QuizInfo $quizInfo)
    {
        //
    }

    /**
     * Handle the quiz info "force deleted" event.
     *
     * @param  \App\QuizInfo  $quizInfo
     * @return void
     */
    public function forceDeleted(QuizInfo $quizInfo)
    {
        //
    }
}
