<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class StoryItem extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function story()
    {
        return $this->belongsTo(Story::class);
    }
}
