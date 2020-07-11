<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class QuizAnswer extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
