<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class Usermeta extends Model
{
    //
    protected $table = 'wp_usermeta';
    protected $primaryKey = 'umeta_id';

    public static function getUserCredit($user_id){
        $user_credit = Usermeta::where([
                ['meta_key', '=', 'chenxing_credit'],
                ['user_id', '=', $user_id],
            ])
            ->sum('meta_value');
        $user_credit_void = Usermeta::where([
                ['meta_key', '=', 'chenxing_credit_void'],
                ['user_id', '=', $user_id],
            ])
            ->sum('meta_value');

        return [
            'user_credit' => $user_credit,
            'user_credit_void' => $user_credit_void,
        ];
    }

    public static function getUserCollect($user_id, $count = false, $page = false) {

        $user_meta_query = Usermeta::query();
        $user_collect_value = $user_meta_query->where([
                ['meta_key', '=', 'chenxing_collect'],
                ['user_id', '=', $user_id],
            ])
            ->value('meta_value');

        if ( !is_serialized($user_collect_value) ){
            $user_collect_arr = explode(',', $user_collect_value);
        } else {
            $user_collect_arr = array_column(unserialize($user_collect_value), 'post_id');
        }

        if ($count) {
            $user_collect = count( $user_collect_arr );
        } else {
            if ($page) {
                $user_collect = Posts::whereIn('ID', $user_collect_arr)->simplePaginate($page);
            } else {
                $user_collect = Posts::whereIn('ID', $user_collect_arr)->get()->toArray();
            }
        }

        return $user_collect;
    }

    public static function getUserAvatar($user_id) {
        $user_avatar = Usermeta::where([
                ['meta_key', '=', 'simple_local_avatar'],
                ['user_id', '=', $user_id],
            ])
            ->value('meta_value');
        return $user_avatar;
    }
}
