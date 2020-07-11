<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Jamesh\Uuid\HasUuid;

class Topic extends Model
{
    use HasUuid;

    protected $guarded = [];

    public function subscribers()
    {
        return $this->belongsToMany(User::class, 'topic_subscribers');
    }

    public static function addTopic($notifiable_type, $notifiable_id = null)
    {
        $name = implode('_', array_filter([$notifiable_type, $notifiable_id]));

        Topic::create(['name' => $name, 'notifiable_type' => $notifiable_type, 'notifiable_id' => $notifiable_id]);
    }
}
