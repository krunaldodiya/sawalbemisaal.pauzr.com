<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class Invitation extends Model
{
    use HasUuid;

    protected $guarded = [];
}
