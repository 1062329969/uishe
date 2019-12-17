<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Multi_upload extends Model
{
    //

    //子分类
    public function upload_relation()
    {
        return $this->hasMany('App\Models\Upload','id','rid');
    }
}
