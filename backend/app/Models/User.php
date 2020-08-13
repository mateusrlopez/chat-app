<?php

namespace App\Models;

use App\Mail\ResetPasswordMail;
use App\Models\Pivot\UserChannel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function sendPasswordResetNotification($token)
    {
        $url = config('app.url') . "/reset-password?email={$this->email}&token=$token";
        Mail::to($this)->queue(new ResetPasswordMail($url));
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    
    public function channels()
    {
        return $this->belongsToMany(Channel::class)->withPivot(['admin'])->withTimestamps()->using(UserChannel::class);
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function invitesReceived()
    {
        return $this->belongsTo(Invite::class, 'invited_id');
    }
}
