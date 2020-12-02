<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class Follow extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function followers()
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function followings()
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
