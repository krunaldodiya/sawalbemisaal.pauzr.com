<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class Category extends Model
{
    use HasUuid;

    protected $guarded = [];
}
