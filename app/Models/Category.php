<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $table = 'category';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = ['name','sort','parent_id', 'alias'];

    //子分类
    public function childs()
    {
        return $this->hasMany('App\Models\Category','parent_id','id');
    }

    //所有子类
    public function allChilds()
    {
        return $this->childs()->with('allChilds');
    }

    public function news()
    {
        return $this->belongsToMany('App\Models\News','category_new','id','category_id');
    }

    //子分类
    public function relation_cat()
    {
        return $this->hasMany('App\Models\CategoryNew','cat_id','id');
    }

    /*//分类下所有的文章
    public function news()
    {
        return $this->hasMany('App\Models\News');
    }*/
}
