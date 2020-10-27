<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Jamesh\Uuid\HasUuid;

class PrizeDistribution extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function quiz_infos()
    {
        return $this->belongsTo(QuizInfo::class, 'quiz_info_id');
    }
}
