<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownLog extends Model
{
    //
    protected $table = 'users_down_log';
    protected $primaryKey = 'id';

    public static function getDownLog($pid = 0, $uid = 0, $cont = '', $limit = 0, $offset = 0){

        $query = DownLog::query();

        if( $uid ) {
            $query->where('user_id', $uid);
        } elseif ($pid ){
            $query->where('product_id', $pid);
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
