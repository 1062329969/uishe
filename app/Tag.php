<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $table = 'tag';
    protected $primaryKey = 'id';

    public static function getRecommendTags(){
        $recommend = News::where([
                ['recommend', '=', 'on'],
            ])
            ->select(['name', 'id'])
            ->get()
            ->toArray();
        return $recommend;
    }

}
