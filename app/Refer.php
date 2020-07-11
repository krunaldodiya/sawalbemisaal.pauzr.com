<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class Refer extends Model
{
    use HasUuid;

    protected $guarded = [];
}
