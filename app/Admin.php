<?php

namespace App;

use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use Notifiable, ThrottlesLogins, HasApiTokens;
    protected $fillable = ['name', 'username', 'role', 'email', 'phone', 'password'];

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function estates()
    {
        return $this->belongsToMany(Estate::class, 'estate_partner', 'user_id', 'estate_id')->select('estate_partner.estate_id');
    }
}
