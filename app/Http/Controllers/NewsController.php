<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comments;
use App\Models\News;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    //
    public function item(Request $request, $id){

        $new = News::find($id);
        $user = Auth::user();
        $vip = true;
        if ($user === NULL || $user->vip==0){
            $vip = false;
        }

        if ( !$vip ){
            $news_recommend = News::getRecommendNews();
            $tags_recommend = Tag::getRecommendTags();
            $comments_recommend = Comments::getRecommendComments();
        }
        return view('home.png');
//        return view('templet');
    }

    public function category(Request $request, $category){
        $category_id = Category::where('alias', $category)->value('id');
        $data = News::where([
            ['category_id', '=', $category_id],
            ['status', '=', 'normal']
        ])
        ->select(['id', 'cover_img', 'title'])
        ->orderBy('id', 'desc')
        ->simplePaginate(4);

        $category_list = Category::where([
            ['parent_id', '=', $category_id]
        ])
        ->pluck('name');

        $tag = News::where([
            ['category_id', '=', $category_id],
            ['status', '=', 'normal']
        ])->pluck('tag');
        $tag_list = [];
        foreach ($tag as $key=>$item){
            $tag_list = array_merge($tag_list, json_decode($item, JSON_UNESCAPED_UNICODE));
        }

        return view('home.list', [
            'data' => $data,
            'category_list' => $category_list,
            'tag_list' => array_unique($tag_list)
        ]);
    }
}
