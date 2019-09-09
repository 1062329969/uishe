<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebOption extends Model
{
    //
    const OP_STATUS_ENABLE = 'enable';
    const OP_STATUS_DISABLED = 'disabled';

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
            ->get()
            ->toArray();
    }

    public static function getOption($type, $json = false){
        $option = WebOption::where([
            ['op_type', '=', $type],
            ['op_status', '=', 'enable'],
        ])
            ->first()
            ->toArray();
        if($json){
            $option['op_value'] = json_decode($option['op_value'], true);
        }

        return $option;
    }

    public function toArray(){
        $item  = parent::toArray();
        $item['op_json'] = json_decode($item['op_json'], true);
        return $item;
    }
}
