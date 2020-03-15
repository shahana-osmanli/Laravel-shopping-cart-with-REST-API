<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//Add this line
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'users'; //her yeni Class yaradanda table adi mutleqdi

    protected $fillable = [ //postmani qosh
        'name', 
        'email', 
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = false; //bu hisseleri kechen defe demishdim

    //bu funksyalar JWT(JSON WEB TOKEN) default funksyalaridi

    //**************** */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function setPasswordAttribute($password)
    {
        if ( !empty($password) ) {
            $this->attributes['password'] = bcrypt($password);
        }
    } 
    ///************** */
}
