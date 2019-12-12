<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    const User_Status_Normal = 'normal';
    const User_Status_Lock = 'lock';

    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $rememberTokenName = '';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar_url'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user_weibo()
    {
        return $this->hasOne('App\Models\UsersWeibo', 'user_id', 'id');
    }
    public function user_qq()
    {
        return $this->hasOne('App\Models\UsersQQ', 'user_id', 'id');
    }

}
