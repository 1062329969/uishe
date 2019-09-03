<?php

namespace App\Http\Controllers;

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

    public function category(Request $request, $id){
//        echo  $id;
        return view('home.list');
    }
}
