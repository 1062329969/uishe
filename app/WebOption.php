<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebOption extends Model
{
    //
    protected $table = 'web_option';
    protected $primaryKey = 'id';

    public static function getIndexMenu(){
        return WebOption::where([
            ['op_type', '=', 'category'],
            ['op_status', '=', 'enable'],
        ])
            ->orderBy('op_sort', 'asc')
            ->get();
    }

    public static function getLinks(){
        return WebOption::where([
            ['op_type', '=', 'links'],
            ['op_status', '=', 'enable'],
        ])
            ->orderBy('op_sort', 'asc')
            ->get();
    }

    public static function getBanner(){
        return WebOption::where([
            ['op_type', '=', 'banner'],
            ['op_status', '=', 'enable'],
        ])
            ->orderBy('op_sort', 'asc')
            ->get();
    }
}
