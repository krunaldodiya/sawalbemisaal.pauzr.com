<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class Language extends Model
{
    use HasUuid;

    protected $guarded = [];
}
