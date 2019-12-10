<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersWeibo extends Model
{
    //

    protected $table = 'users_weibo';
    protected $primaryKey = 'id';

    protected $fillable = [
        'openid',
        'access_token'
    ];

    public function user_relation()
    {
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
