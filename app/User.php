<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

//    protected $primaryKey = "userId";
    const BUYER = 2;
    const SELLER = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone','code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function emails()
    {
        return $this->hasMany(Email::class)->orderBy('id','desc');
    }

    public function telephone()
    {
        return $this->hasOne(Telephone::class);
    }

    public function darkhasts()
    {
        return $this->hasMany(Darkhast::class);
    }

    public function getDetailsDarkhast()
    {
            $darkhasts = $this->darkhasts;
    }

    public function similarDarkhastEstates()
    {
        return $this->belongsToMany(Estate::class,'darkhast_estate','user_id','estate_id');
    }

    public function findForPassport($username) {
        return $this->where('phone', $username)->first();
    }

    public function AauthAcessToken(){
        return $this->hasMany('App\OauthAccessToken', 'user_id', 'id');
    }

    public function shoppingCartItems(){
        return $this->hasMany('App\ShoppingCart', 'user_id', 'id')
                    ->where('expired', false)
                    ->where('wishList', false);
    }

    public function wishlistItems(){
        return $this->hasMany('App\ShoppingCart', 'userId', 'userId')
            ->where('expired', false)
            ->where('wishList', true);
    }

    public function address(){
        return $this->hasOne('App\Address', 'userId', 'userId');
    }

    public function orders(){
        return $this->hasMany('App\Order', 'userId', 'userId')
                    ->orderByDesc('orderDate')
                    ->select(['orderId', 'orderDate', 'totalAmount']);
    }

    public function estates()
    {
        return $this->belongsToMany(Estate::class);
    }
}
