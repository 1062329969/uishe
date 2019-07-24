<?php

namespace App\Http\Controllers;

use App\tag;
use App\Libs\PasswordHash;
use App\Terms;
use App\Termtaxonomy;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QianyiController extends Controller
{
    public function tag(){
        $category = Termtaxonomy::where([
            ['parent', '=', '0'],
            ['taxonomy', '=', 'category'],
        ])->get()->toArray();

        $new_category = $this->get_category($category);
//懒得写递归
        foreach ($new_category as $k => $v){

            $category_child = Termtaxonomy::where([
                ['parent', '=', $v['id']],
                ['taxonomy', '=', 'category'],
            ])->get()->toArray();
            $new_category_child = $this->get_category($category_child);
//            echo $v['id']; echo $v['name'];
//            dump($new_category_child);
            $new_category = array_merge($new_category, $new_category_child);
        }

        Category::insert($new_category);
    }

    public function get_category($category){
        $category_id_arr = array_column($category, 'term_id');

        $terms = Terms::whereIn('term_id', $category_id_arr)->get()->toArray();

        $terms = array_column($terms, NULL, 'term_id');

        $new_category = [];
        foreach ($category as $k => $v){
            $new_category[] = [
                'id' => $v['term_id'],
                'name' => $terms[$v['term_id']]['name'],
                'alias' => $terms[$v['term_id']]['slug'],
                'parent_id' => $v['parent'],
                'count' => $v['count'],
            ];
        }
        return $new_category;
    }

    public function movetag(){
        $tag = Termtaxonomy::where([
            ['parent', '=', '0'],
            ['taxonomy', '=', 'post_tag'],
        ])->get()->toArray();

        $new_tag = $this->get_tag($tag);
        tag::insert($new_tag);
    }

    public function get_tag($tag){
        $tag_id_arr = array_column($tag, 'term_id');

        $terms = Terms::whereIn('term_id', $tag_id_arr)->get()->toArray();

        $terms = array_column($terms, NULL, 'term_id');

        $new_tag = [];
        foreach ($tag as $k => $v){
            $new_tag[] = [
                'id' => $v['term_id'],
                'name' => $terms[$v['term_id']]['name'],
                'alias' => $terms[$v['term_id']]['slug'],
                'parent_id' => $v['parent'],
                'count' => $v['count'],
            ];
        }
        return $new_tag;
    }



}