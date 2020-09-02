<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Jamesh\Uuid\HasUuid;

class Locale extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
