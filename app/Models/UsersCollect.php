<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersCollect extends Model
{
    //

    protected $table = 'users_collect';
    protected $primaryKey = 'id';

    public static function getCollect($pid = 0, $uid = 0, $cont = '', $limit = 0, $offset = 0){
        $query = UsersCollect::query();

        if( $uid ) {
            $query->where('user_id', $uid);
        } elseif ($pid ){
            $query->where('collect_id', $pid);
        }
        if($cont == 'cont'){
            $check = $query->count();
        }else{
            $query->orderBy('id', 'DESC');
            $check = $query->simplePaginate(9);
        }
        return $check;
    }
}
