<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    //
    protected $table = 'wp_posts';
    protected $primaryKey = 'ID';

    /**
     * 获取用户作品数
     * @return array
     */
    public static function countUserPosts ($user_id)
    {
        return Posts::where([
                ['post_author', '=', $user_id],
                ['post_type', '=', 'post'],
                ['post_status', '=', 'publish'],
            ])
            ->count();
    }

}
