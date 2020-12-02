<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
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
