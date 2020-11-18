<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class Story extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function items()
    {
        return $this->hasMany(StoryItem::class);
    }
}
