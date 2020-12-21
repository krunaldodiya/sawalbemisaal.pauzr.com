<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Jamesh\Uuid\HasUuid;

class Redeem extends Model
{
    use HasUuid;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'float',
    ];

    public function getImageAttribute()
    {
        return Storage::url($this->image);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
