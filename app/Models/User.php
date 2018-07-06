<?php

namespace App\Models;

use App\Mail\PasswordReset;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Mail;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'votekey',
        'verification_code'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'votekey',
        'password',
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function songs()
    {
        return $this->hasMany(Song::class);
    }

    /**
     * Create email verification code.
     *
     * @return string
     */
    public function createVerificationCode(): string
    {
        $this->update(['verification_code' => str_random(40)]);
        return  $this->verification_code;
    }

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return is_null($this->verification_code);
    }

    /**
     * @return bool
     */
    public function hasLegacyEmail(): bool
    {
        return ($this->email && !Str::contains($this->email, '@'));
    }

    /**
     * Get the e-mail address where password reset links are sent.
     *
     * @return string
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        //@todo switch with laravel notification
        Mail::to($this->getEmailForPasswordReset())->send(new PasswordReset($this,$token));
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function($user) {
            foreach ($user->songs()->get() as $song) {
                $song->delete();
            }
        });
    }

}
