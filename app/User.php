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

    protected $appends = [
        'app_version',
    ];

    public function getAppVersionAttribute($avatar)
    {
        $binary = Binary::orderByDesc('version', 'desc')->first();

        if ($binary) {
            return $binary->version;
        }

        return '1.0.0';
    }

    public function getAvatarAttribute($avatar)
    {
        return $avatar ? $avatar : "default.png";
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function quizzes()
    {
        return $this->hasMany(QuizParticipant::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'follows', 'following_id', 'follower_id');
    }

    public function followings()
    {
        return $this->belongsToMany(User::class, 'follows', 'follower_id', 'following_id');
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

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public static function filterPeriod($period)
    {
        switch ($period) {
            case 'Today':
                return now()->startOfDay();

            case 'This Week':
                return now()->startOfWeek();

            case 'This Month':
                return now()->startOfMonth();

            case 'All Time':
                return null;

            default:
                return now()->startOfDay();
        }
    }
}
