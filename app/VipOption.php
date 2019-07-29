<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VipOption extends Model
{
    //

    const Option_Status_On = 'on';
    const Option_Status_Off = 'off';

    const Option_Date_Type_Days = 'days';
    const Option_Date_Type_Years = 'years';
    const Option_Date_Type_Lifelong = 'lifelong';

    protected $table = 'vip_option';
    protected $primaryKey = 'id';
}
