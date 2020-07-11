<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class Binary extends Model
{
    use HasUuid;

    protected $guarded = [];
}
