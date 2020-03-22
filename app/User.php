<?php

namespace App;
use App\Product;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//Add this line
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = 'users'; 

    protected $fillable = [ 
        'name', 
        'email', 
        'password',
        'type',
    ];

    protected $hidden = [
        'password',
    ];

    public $timestamps = false; 

    public function products()
    {
        return $this->hasMany('App\Product');
    }

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
