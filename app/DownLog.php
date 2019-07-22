<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DownLog extends Model
{
    //
    protected $table = 'wp_chenxing_down_log';
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
            if( !$limit && !$offset){
                $query->orderBy('id', 'DESC');
            }else{
                $query->orderBy('id', 'DESC')->offset($offset)->limit($limit);
            }
            $check = $query->get()->toArray();
        }
        return $check;
    }

}
