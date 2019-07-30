<?php

namespace App\Http\Controllers;

use App\Category;
use App\CategoryNew;
use App\Comments;
use App\News;
use App\Postmeta;
use App\Posts;
use App\tag;
use App\Libs\PasswordHash;
use App\TagNew;
use App\TermRelationships;
use App\Terms;
use App\Termtaxonomy;
use App\User;
use App\WpComments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Rodenastyle\StreamParser\StreamParser;

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
    /*
    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `new_id` int(11) DEFAULT NULL,
    `user_id` int(11) DEFAULT NULL,
    `user_name` varchar(255) DEFAULT NULL,
    `user_ip` varchar(255) DEFAULT NULL,
    `content` varchar(255) DEFAULT NULL,
    `status` varchar(255) DEFAULT NULL,
    `agent` varchar(255) DEFAULT NULL COMMENT '代理，浏览器头部信息',
    `parent_id` int(11) DEFAULT NULL,*/

    public function move_comment(){
        $comments = WpComments::where('comment_approved', 1)->get()->toArray();
        $new_comments = [];
        foreach($comments as $item) {
            $new_comments[] = [
                'created_at' => $item['comment_date'],
                'new_id' => $item['comment_post_ID'],
                'user_id' => $item['user_id'],
                'user_name' => $item['comment_author'],
                'user_ip' => $item['comment_author_IP'],
                'content' => $item['comment_content'],
                'status' => Comments::Comments_Status_Allow,
                'agent' => $item['comment_agent'],
                'parent_id' => $item['comment_parent'],
            ];
        }
        Comments::insert($new_comments);
    }

    public function move_posts(){
        set_time_limit(360);

        $post_main = Posts::where([
            ['post_status', '=', 'publish'],
        ])->get()->toArray();
        foreach ($post_main as $value){
            $this->get_posts($value['ID']);
        }
    }

    public function get_posts($post_id){

        $taxonomy_id = TermRelationships::where('object_id', $post_id)->pluck('term_taxonomy_id');

        $comment_count = Comments::where('new_id', $post_id)->count();

        $taxonomy = Termtaxonomy::whereIn('term_id', $taxonomy_id)->get()->toArray();
        $terms = Terms::whereIn('term_id', $taxonomy_id)->get()->toArray();
        $terms = array_column($terms, NULL, 'term_id');
        
        $post_meta = Postmeta::where('post_id', $post_id)->get()->toArray();

        $post_main = Posts::where([
            ['ID', '=', $post_id],
            ['post_status', '=', 'publish'],
        ])->first();
        if($post_main){
            $post_main->toArray();
        }else{
            return false;
        }

        $tag_new = [];
        $category_new = [];
        foreach ($taxonomy as $item){
            if ($item['taxonomy'] == 'post_tag') {
                $tag_new[] = [
                    'tag_new_id' => $post_id,
                    'tag_id' => $item['term_id'],
                    'tag' => $terms[ $item['term_id'] ]['name']
                ];
            }
            if ($item['taxonomy'] == 'category') {
                $category_new[] = [
                    'cat_new_id' => $post_id,
                    'cat_id' => $item['term_id'],
                    'category' => $terms[ $item['term_id'] ]['name']
                ];
            }
        }

        $post_meta = array_column($post_meta, 'meta_value', 'meta_key');
//        dd($post_meta);
        if (!isset($post_meta['_post_downtype'])){
            $post_meta['_post_downtype'] = 0;
        }

        $new = [
            'id' => $post_main['ID'],
            'created_at' => $post_main['post_date'],
            'admin_id' => $post_main['post_author'],
            'title' => $post_main['post_title'],
            'content' => $post_main['post_content'],
            'status' => News::Status_Normal,
            'comment_status' => News::Comment_Status_On,
            'comment_count' => $comment_count,
            'cover_img' => $post_meta['cx-post-imgurl'] ?? '',
            'down_num' => $post_meta['post_down_numter'] ?? 0,
            'down_type' => News::getDownType($post_meta['_post_downtype']),
            'down_level' => $post_meta['_post_downmianfei'] ?? 0,
            'down_price' => $post_meta['_post_downpay'] ?? 0,
            'down_url' => $post_meta['_post_downurl'] ?? '',
            'views' => $post_meta['views'] ?? 0,
            'like' => $post_meta['bigfa_ding'] ?? 0,
            'collects' => $post_meta['chenxing_post_collects'] ?? 0,
            'category_id' => json_encode(array_column($category_new, 'cat_id'), JSON_UNESCAPED_UNICODE),
            'category' => json_encode(array_column($category_new, 'category'), JSON_UNESCAPED_UNICODE),
            'tag_id' => json_encode(array_column($tag_new, 'tag_id'), JSON_UNESCAPED_UNICODE),
            'tag' => json_encode(array_column($tag_new, 'tag'), JSON_UNESCAPED_UNICODE),
        ];
//        dd($new);
        News::insert($new);
        CategoryNew::insert($category_new);
        TagNew::insert($tag_new);

    }

    public function moveUser(){







    }















}