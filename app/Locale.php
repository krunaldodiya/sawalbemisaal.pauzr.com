<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Jamesh\Uuid\HasUuid;

class Locale extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }
}
