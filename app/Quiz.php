<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class Quiz extends Model
{
    use HasUuid;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function participants()
    {
        return $this->hasMany(QuizParticipant::class);
    }

    public function rankings()
    {
        return $this->hasMany(QuizRanking::class);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')->withPivot('is_answerable');
    }

    public function quiz_infos()
    {
        return $this->belongsTo(QuizInfo::class, 'quiz_info_id');
    }

    public static function generateQuiz()
    {
        return "HELLO";
    }
}
