<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Jamesh\Uuid\HasUuid;

class Faq extends Model
{
    use HasUuid;

    protected $guarded = [];
}
