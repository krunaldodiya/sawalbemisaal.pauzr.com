<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Jamesh\Uuid\HasUuid;

class QuestionTranslation extends Model
{
    use HasUuid;

    protected $guarded = [];

    protected $dates = ['created_at', 'updated_at'];

    public function getQuestionAttribute($value)
    {
        return strip_tags($value);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
