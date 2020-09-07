<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class QuizInfo extends Model
{
    use HasUuid;

    protected $guarded = [];

    protected $casts = [
        'expired_at' => 'datetime',
        'prize_distribution' => 'json',
    ];

    public function prize_distributions()
    {
        return $this->hasMany(PrizeDistribution::class);
    }
}
