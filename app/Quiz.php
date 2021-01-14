<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class Quiz extends Model
{
    use HasUuid;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'expired_at'];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    protected $appends = ['prize_amount'];

    public function getPrizeAmountAttribute()
    {
        return $this->quiz_infos->entry_fee * $this->quiz_infos->total_participants;
    }

    public function host()
    {
        return $this->belongsTo(User::class, 'host_id');
    }

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
        return $this->belongsToMany(Question::class, 'quiz_questions');
    }

    public function answerable_questions()
    {
        return $this->belongsToMany(Question::class, 'quiz_questions')->where('is_answerable', true);
    }

    public function quiz_infos()
    {
        return $this->belongsTo(QuizInfo::class, 'quiz_info_id');
    }

    public static function generateTitle()
    {
        $random_alphabet = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 2);

        $random_number = substr(str_shuffle('0123456789'), 0, 4);

        return "{$random_alphabet}{$random_number}";
    }
}
