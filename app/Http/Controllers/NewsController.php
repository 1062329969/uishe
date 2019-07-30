<?php

namespace App\Http\Controllers;

use App\Comments;
use App\News;
use App\Tag;
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
            $tags_recommend = Comments::getRecommendComments();
        }

    }

}
