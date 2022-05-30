<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class School extends Authenticatable implements JWTSubject
{
    use Notifiable;

    public $timestamps = false;

    protected $table = "table_school";

    protected $fillable = [
        'name', 'phone', 'email', 'fax', 'address', 'website', 'logo', 'postal_code', 'about', 'mission', 'vision', 'password'
    ];

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
}