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
        return $this->hasOne(User::class, 'id', 'host_id');
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
        return $this->belongsToMany(Question::class, 'quiz_questions')
            ->withPivot('answer')
            ->where('is_answerable', true);
    }

    public function quiz_infos()
    {
        return $this->belongsTo(QuizInfo::class, 'quiz_info_id');
    }

    public static function generateTitle()
    {
        $number = '0123456789';
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        function generateAlphaNumeric($alphanumeric, $length_of_string)
        {
            return substr(
                str_shuffle($alphanumeric),
                0,
                $length_of_string
            );
        }

        $random_alphabet = generateAlphaNumeric($alphabet, 2);
        $random_number = generateAlphaNumeric($number, 4);

        return "{$random_alphabet}{$random_number}";
    }
}
