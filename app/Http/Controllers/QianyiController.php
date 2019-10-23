<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryNew;
use App\Models\Comments;
use App\Models\News;
use App\Models\Postmeta;
use App\Models\Posts;
use App\Models\Tag;
use App\Libs\PasswordHash;
use App\Models\TagNew;
use App\Models\TermRelationships;
use App\Models\Terms;
use App\Models\Termtaxonomy;
use App\Models\User;
use App\Models\Usercredit;
use App\Models\UsersCollect;
use App\Models\UsersQQ;
use App\Models\UsersWeibo;
use App\Models\WpComments;
use App\Models\WpMessage;
use App\Models\WpVip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Rodenastyle\StreamParser\StreamParser;

class QianyiController extends Controller
{
    public function category(){
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
        ])->limit(1000)->orderBy('ID', 'desc')->get()->toArray();
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
                $category_new = [
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
        if(isset($post_meta['cx-post-imgurl'])){
            $cover_img = json_decode($post_meta['cx-post-imgurl'], true);
            $cover_img = reset($cover_img);
        }else{
            $cover_img = '';
        }
        if( !isset($category_new['cat_id']) || empty($category_new['cat_id']) ){
            return false;
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
            'cover_img' => $cover_img,
            'down_num' => $post_meta['post_down_numter'] ?? 0,
            'down_type' => News::getDownType($post_meta['_post_downtype']),
            'down_level' => $post_meta['_post_downmianfei'] ?? 0,
            'down_price' => $post_meta['_post_downpay'] ?? 0,
            'down_url' => $post_meta['_post_downurl'] ?? '',
            'views' => $post_meta['views'] ?? 0,
            'like' => $post_meta['bigfa_ding'] ?? 0,
            'collects' => $post_meta['chenxing_post_collects'] ?? 0,
            'category_id' => $category_new['cat_id'] ?? 0,
            'category' => $category_new['category'] ?? '',
            'tag_id' => json_encode(array_column($tag_new, 'tag_id'), JSON_UNESCAPED_UNICODE),
            'tag' => json_encode(array_column($tag_new, 'tag'), JSON_UNESCAPED_UNICODE),
        ];
//        dd($new);
        News::insert($new);
        CategoryNew::insert($category_new);
        TagNew::insert($tag_new);

    }

    public function move_users(){
        set_time_limit(360);

        $user = DB::table('wp_users')->get()->toArray();
        foreach ($user as $value){
            $this->optionUserData((array)$value);
        }

    }

    public function optionUserData($user){

        $user_meta = DB::table('wp_usermeta')->where('user_id', $user['ID'])->get()->toArray();

        $user_meta = array_column($user_meta, 'meta_value', 'meta_key');

        $vip = WpVip::where('user_id', $user['ID'])->first();
        if(!$vip){
            $vip['user_type'] = 0;
            $vip['startTime'] = NULL;
            $vip['endTime'] = NULL;
        }

        DB::transaction();
        $user_data = [
            'name' => $user['login'],
            'password' => $user['pass'],
            'nicename' => $user['nicename'],
            'email' => $user['email'],
            'url' => $user['url'],
            'created_at' => $user['registered'],
            'registered' => $user['registered'],
            'display_name' => $user['display_name'],
            'nickname' => $user['nickname'],
            'last_login_time' => $user['last_login_time'],
            'avatar_url' => $user_meta['simple_local_avatar'] ?? '',
            'credit' => $user_meta['chenxing_credit'] ?? 0,
            'user_type' => $vip['user_type'],
            'startTime' => $vip['startTime'],
            'endTime' => $vip['endTime'],
        ];

        $insert = User::insert($user_data);
        if (!$insert) {
            DB::rollBack();
        }

        if (isset($user_meta['chenxing_collect'])){
            $user_collect = [];
            foreach (explode(',', $user_meta) as $item) {
                $news = News::find($item);
                if ($news) {
                    $user_collect[] = [
                        'user_id' => $user['ID'],
                        'collect_id' => $item,
                        'title' => $news['title'],
                        'img' => $news['cover_img'],
                    ];
                }
            }

            $collect_insert = UsersCollect::insert($user_collect);
            if (!$collect_insert) {
                DB::rollBack();
            }
        }

        if (isset($user_meta['chenxing_qq_openid'])) {
            $qq_openid_data = [
                'openid' => $user_meta['chenxing_qq_openid'],
                'access_token' => $user_meta['chenxing_qq_access_token'],
                'user_id' => $user['ID'],
                'created_at' => $user_meta['chenxing_daily_sign'],
            ];

            $qq_res = UsersQQ::insert($qq_openid_data);
            if (!$qq_res){
                DB::rollBack();
            }
        }

        if (isset($user_meta['chenxing_weibo_openid'])) {
            $weibo_openid_data = [
                'openid' => $user_meta['chenxing_weibo_openid'],
                'access_token' => $user_meta['chenxing_weibo_access_token'],
                'user_id' => $user['ID'],
                'created_at' => $user_meta['chenxing_daily_sign'],
            ];

            $weibo_res = UsersWeibo::insert($weibo_openid_data);
            if (!$weibo_res){
                DB::rollBack();
            }
        }

    }


    public function move_message(){

        set_time_limit(360);

        $user = DB::table('wp_users')->get()->toArray();
        foreach ($user as $value){

            $users = (array)$value;
            $message = WpMessage::where('user_id', $users['ID'])->get();

            if($message){
                $message = $message->toArray();

                foreach ($message as $item) {
                    $new_message[] = [
                        'id' => $item['msg_id'],
                        'user_id' => $item['user_id'],
                        'created_at' => $item['msg_date'],
                        'content' => $item['msg_title'],
                    ];
                }
                $mssage_res = Usercredit::insert($new_message);
                if (!$mssage_res){
                    DB::rollBack();
                }
            }

        }

    }






}