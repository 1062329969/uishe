<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VipOption extends Model
{
    //

    const Option_Status_On = 'on';
    const Option_Status_Off = 'off';
    const Option_Spread_Day = 90;

    protected $table = 'vip_option';
    protected $primaryKey = 'id';
}
