<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Usercredit extends Model
{
    //
    protected $table = 'users_credit_log';
    protected $primaryKey = 'id';

    const Credit_From_Null = 'unknown';
    const Credit_From_Buy = 'recharge';

    public static function getCredit($uid = 0, $cont = '', $limit = 0, $offset = 0){

        $query = Usercredit::query();

        if( $uid ) {
            $query->where('user_id', $uid);
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
