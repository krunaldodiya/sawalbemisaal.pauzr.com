<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class QuizInfo extends Model
{
    use HasUuid;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at', 'expired_at'];

    protected $casts = [
        'prize_distribution' => 'json',
        'expired_at' => 'datetime',
    ];

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

    public function prize_distributions()
    {
        return $this->hasMany(PrizeDistribution::class);
    }
}
