<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comments;
use App\Models\News;
use App\Models\Tag;
use App\Models\TagNew;
use App\Models\Thematic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    //

    public function category(Request $request, $category)
    {
        $request = $request->toArray();
        $request['category'] = $category;
        return $this->getNewsAllList($request);
    }

    public function tag(Request $request, $tag)
    {
        $request = $request->toArray();
        $request['tag'] = $tag;
        return $this->getNewsAllList($request);
    }

    public function getNewsList(Request $request)
    {
        $request = $request->toArray();
        return $this->getNewsAllList($request);
    }

    public function getNewsAllList($request)
    {
        if (!is_array($request)) {
            $request = $request->toArray();
        }
        $new_query = News::query();
        $new_query->where('status', 'normal');
        $cat_new_id = [];
        if (isset($request['category'])) {
            $category_request = $request['category'];
            $category_id = Category::where('alias', $request['category'])->value('id');
//            $category_info = Category::where('alias', $request['category'])->with('relation_cat')->first();
//            $cat_new_id = $category_info->relation_cat->pluck('cat_new_id');
//            if (!empty($cat_new_id->toArray())) {
//                $new_query->whereIn('id', $cat_new_id);
//            }

            $category_list = Category::where([
//                ['parent_id', '=', $category_info['id']]
                ['parent_id', '=', $category_id]
            ])->pluck('name');

            $new_query->whereHas('category', function ($query) use ($category_request) {
                $query->where('alias',$category_request);
            });

        }
        $tag_new_id = [];
        if (isset($request['tag'])) {
            $tag_request = $request['tag'];
//            $tag_id = Tag::where('name', $request['tag'])->value('id');
//            $tag_new_id = TagNew::where('tag_id', $tag_id)->pluck('tag_new_id')->toArray();
//            $new_query->whereIn('id', $tag_new_id);


            $new_query->whereHas('tags', function ($query) use ($tag_request) {
                $query->where('name',$tag_request);
            });

        }

        if (isset($request['search'])) {
            $new_query->where('title', 'like', '%' . $request['search'] . '%');
        }

       $new_query->select(['id', 'cover_img', 'title','tag']);
        if (isset($request['orderby']) && in_array($request['orderby'], ['created_at', 'comment_count', 'views', 'like'])) {
            $new_query->orderBy($request['orderby'], 'desc');
        }

        $data = $new_query->orderBy('id', 'desc')->paginate(20);
        $tag_list = [];
        foreach ($data as $key => $item) {
            $tag_list = array_merge($tag_list, json_decode($item->tag));
        }

        $recommend_tag = Tag::where('recommend', Tag::Tag_Recommend_On)->pluck('name');
        $data = [
            'data' => $data,
            'category' => $request['category'] ?? NULL,
            'tag' => $request['tag'] ?? NULL,
            'tag_list' => array_unique($tag_list),
            'recommend_tag' => $recommend_tag
        ];
        if (isset($category_list)) {
            $data['category_list'] = $category_list;
        }
        return view('home.list', $data);
    }


    public function item(Request $request, $id)
    {
        $new = News::find($id);
        if(!$new){
            return redirect('404');
        }
        $user = Auth::user();
        $vip = true;
        if ($user === NULL || $user->vip == 0) {
            $vip = false;
        }

        if (!$vip) {
            $news_recommend = News::getRecommendNews();
            $tags_recommend = Tag::getRecommendTags();
            $comments_recommend = Comments::getRecommendComments();
        }
        $recommend_tag = Tag::where('recommend', Tag::Tag_Recommend_On)->pluck('name');
        // 专题
        $thematic = Thematic::get();
        return view('home.png', [
            'new' => $new,
            'vip' => $vip,
            'news_recommend' => $news_recommend,
            'tags_recommend' => $tags_recommend,
            'comments_recommend' => $comments_recommend,
            'recommend_tag' => $recommend_tag,
            'thematic' => $thematic
        ]);
//        return view('templet');
    }

    public function test()
    {
        $roles = 'material';
        $list = News::whereHas('category', function ($query) use ($roles) {
            $query->where('alias',$roles);
        })->get();

        dd($list);
    }

    public function save_commont(Request $request, $from, $id){
        comment
        comment_post_ID
        author
        email


`new_id` int(11) DEFAULT NULL COMMENT '文章id',
  `user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `user_name` varchar(255) DEFAULT NULL COMMENT '用户名',
  `user_ip` varchar(255) DEFAULT NULL COMMENT '用户ip',
  `content` varchar(255) DEFAULT NULL COMMENT '内容',
  `status` varchar(255) DEFAULT NULL COMMENT '状态',
  `agent` varchar(255) DEFAULT NULL COMMENT '代理，浏览器头部信息',
  `parent_id` int(11) DEFAULT NULL,
  `recommend` varchar(255) DEFAULT NULL COMMENT '推荐',
  `mail_notify` int(11) DEFAULT '0',
    }
}
