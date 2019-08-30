<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    //
    protected $table = 'tag';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public $fillable = ['name','sort','parent_id', 'alias', 'recommend'];

    public static function getRecommendTags(){
        $recommend = Tag::where([
                ['recommend', '=', 'on'],
            ])
            ->select(['name', 'id'])
            ->get()
            ->toArray();
        return $recommend;
    }

    //与文章多对多关联
    public function news()
    {
        return $this->belongsToMany('App\Models\News','tag_news','id','news_id');
    }

}
