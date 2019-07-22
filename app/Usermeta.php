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

    public static function getUserCollect($user_id) {
        $user_credit = Usermeta::where([
                ['meta_key', '=', 'chenxing_collect'],
                ['user_id', '=', $user_id],
            ])
            ->sum('meta_value');
        return $user_credit;
    }
}
