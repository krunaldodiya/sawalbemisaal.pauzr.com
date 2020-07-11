<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Jamesh\Uuid\HasUuid;
use KD\Wallet\Traits\HasWallet;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasUuid, HasWallet;

    protected $guarded = [];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function quizzes()
    {
        return $this->hasMany(QuizParticipant::class);
    }

    public function quiz_rankings()
    {
        return $this->hasMany(QuizRanking::class);
    }

    public function quiz_answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function device_tokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'topic_subscribers');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }
}
