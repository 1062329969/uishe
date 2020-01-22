<?php

namespace App\Http\Controllers;



use App\Libs\Pexels;
use App\Libs\ZipFolder;
use App\Models\Category;
use App\Models\CategoryNew;
use App\Models\News;
use App\Models\Tag;
use App\Models\TagNew;
use QL\QueryList;
use ShaoZeMing\LaravelTranslate\Facade\Translate;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CronController extends Controller
{
    const Pexels_Key = '563492ad6f91700001000001c412d9cbdfd345a19d33b97ecc12f3e1';
    var $applet_path;
    var $pexels_path_zip;
    var $uisheauto_path;


    public function __construct()
    {
        set_time_limit(3600);   // 最大执行时间 3600 一小时。
        $this->pexels_path = storage_path('collection'. DIRECTORY_SEPARATOR. 'pexels' . DIRECTORY_SEPARATOR . date('Ymd').DIRECTORY_SEPARATOR);//资源路径
        $this->pexels_path_zip = storage_path('collection'. DIRECTORY_SEPARATOR. 'pexels' . DIRECTORY_SEPARATOR . date('Ymd').'_zip'.DIRECTORY_SEPARATOR);//资源路径
        $this->uisheauto_path = storage_path('collection'. DIRECTORY_SEPARATOR. 'uisheauto' . DIRECTORY_SEPARATOR . date('Ymd').DIRECTORY_SEPARATOR);
        $this->oursketch_path = storage_path('collection'. DIRECTORY_SEPARATOR. 'oursketch' . DIRECTORY_SEPARATOR . date('Ymd').DIRECTORY_SEPARATOR);

        if (!file_exists($this->uisheauto_path)){
            mkdir($this->uisheauto_path, 0777, true);
            chmod($this->uisheauto_path,0777);
        }
    }

    public function daily(){
        $this->getPexels();
//        $this->getOursketch();
    }

    public function getPexels(){

        if (!file_exists($this->pexels_path)){//如果不存在pexels采集 今天的文件夹
            mkdir($this->pexels_path, 0777, true);
            chmod($this->pexels_path,0777);

            mkdir($this->pexels_path_zip, 0777, true);
            chmod($this->pexels_path_zip,0777);
        }

        $pexels = new Pexels(self::Pexels_Key);

        $img = $pexels->search('web',2);
        $img_list = json_decode($img->getBody(), true)['photos'];
//        dd($img_list);

        $category = Category::where('alias', 'img')->first();

        foreach ($img_list as $item){
            sleep(1);
            if(!file_exists($this->pexels_path . $item['id'] . DIRECTORY_SEPARATOR)){ // 创建这个id的文件夹
                mkdir($this->pexels_path . $item['id'] . DIRECTORY_SEPARATOR, 0777, true);
                chmod($this->pexels_path . $item['id'] . DIRECTORY_SEPARATOR,0777);
            }

            $url = $item['url'];
            $tag = explode('-', str_replace("https://www.pexels.com/photo/", "", $url));
            array_pop($tag);

            $result = Translate::setFromAndTo('en', 'zh')->translate(implode('|', $tag));
            $tag_zh = explode('|', $result);
            $title = str_replace("|", "", $result);

            $news = News::where('title', $title)->first();
            if($news){ //如果这个url已经存在了 跳过
                continue;
            }
            $new_file_path = [];
            $new_file_name = [];
            foreach ($item['src'] as $src_item){

                $parse_url = parse_url($src_item);
                $file_name = basename($parse_url['path']);
                $ext = strrchr($file_name, ".");

                //记录新文件名数组
                if(!isset($parse_url['query'])){    // 如果是原图 不带参数
                    $new_file_name[] = $file_name = 'uishe_'.implode('_', $tag).$ext;
                }else{                              // 如果不是原图 参数作为文件名拼接
                    $query = $parse_url['query'];
                    $queryParts = explode('&', $query);
                    $params = array();
                    foreach ($queryParts as $param) {
                        $query_item = explode('=', $param);
                        $params[$query_item[0]] = $query_item[1];
                    }
                    $file_name = 'uishe_';
                    $file_name_query = [];
                    $file_name_query[] = isset($params['dpr']) ? 'dpr_'.$params['dpr'] :  '';
                    $file_name_query[] = isset($params['fit']) ? 'fit_'.$params['fit'] :  '';
                    $file_name_query[] = isset($params['h']) ? 'h_'.$params['h'] :  '';
                    $file_name_query[] = isset($params['w']) ? 'w_'.$params['w'] :  '';
                    $new_file_name[] = $file_name .= implode('_', array_filter($file_name_query)).$ext;
                }


                // 将网络图片保存到本地 //记录新路径数组
                $new_file_path[] = saveImageFromHttp($src_item, $this->pexels_path. DIRECTORY_SEPARATOR . $item['id'] . DIRECTORY_SEPARATOR .$file_name);
//                dump($res);
            }
            $content_img = env('COLLECTION_IMG_URL').date('Ymd').'/'.reset($new_file_name); //最大的图作为内容
            $cover_img = env('COLLECTION_IMG_URL').date('Ymd').'/'.end($new_file_name); // 最小的作为缩略图
            $zip_path = $this->pexels_path_zip . date('Ymd') . '_' . $item['id'] . ".zip";
            $from_path = $this->pexels_path. DIRECTORY_SEPARATOR . $item['id'];
            $this->makeZip($zip_path, $from_path);  // 打包


            copy(reset($new_file_path), $this->uisheauto_path.reset($new_file_name)); //入库的图 复制到其他目录
            copy(end($new_file_path), $this->uisheauto_path.end($new_file_name)); //入库的图 复制到其他目录

            foreach ($new_file_path as $del_item){ // 删除不必要的图
                @unlink($del_item);
            }
            rmdir($this->pexels_path. DIRECTORY_SEPARATOR . $item['id']); // 删除不必要的目录

            $tag_list = Tag::whereIn('name', $tag_zh)->get()->keyBy('name')->toArray();

            $tag_id = [];
            $tag_new_id = [];
            foreach ($tag_zh as $tag_index => $tag_item) {
                if ( isset($tag_list[$tag_item]) ){
                    $tag_id[] = $tag_item_id = $tag_list[$tag_item]['id'];
                    Tag::where('id', $tag_item_id)->increment('count');
                }else{
                    $tag_id[] = $tag_item_id = Tag::insertGetId([
                        'name' => $tag_item,
                        'alias' => $tag[$tag_index],
                        'count' => 1,
                    ]);
                }

                $tag_new_id[] = [
                    'tag_id' => $tag_item_id,
                    'tag' => $tag_item
                ];
            }

            dump($tag_id);
            dump($tag_zh);
            die;
            $news_id = News::insertGetId([
                'admin_id' => 1,
                'title' => $title,
                'content' => '<img class="alignnone size-medium" src="'. $content_img .'" width="'. $item['width'] .'" height="'. $item['height'] .'" />',
                'status' => News::Status_Normal,
                'comment_status' => News::Comment_Status_On,
                'cover_img' => $cover_img,
                'down_type' => News::Down_Type_Login,
                'down_level' => 0,
                'down_price' => 0,
                'down_url' => $zip_path . '|无',
                'category_id' => $category->id,
                'category' => $category->alias,
                'tag_id' => json_encode($tag_id),
                'tag' => json_encode($tag_zh, JSON_UNESCAPED_UNICODE),
            ]);

            CategoryNew::insert([
                'cat_new_id' => $news_id,
                'cat_id' => $category->id,
                'category' => $category->alias
            ]);
            foreach ($tag_new_id as $tag_new_item) {
                $tag_new_item['tag_new_id'] = $news_id;
            }

            TagNew::insert($tag_new_item);
            die;
        }
        return response()->json($img_list);
    }

    public function makeZip($zip_path, $from_path){
        $zip = new ZipFolder();
        $res = $zip->zip($zip_path, $from_path );
        if($res){
            return $zip_path;
        }else{
            return false;
        }
    }

    public function getOursketch(){
        if (!file_exists($this->oursketch_path)){
            if(!file_exists($this->oursketch_path . DIRECTORY_SEPARATOR)){ // 创建这个id的文件夹
                mkdir($this->oursketch_path . DIRECTORY_SEPARATOR, 0777, true);
                chmod($this->oursketch_path . DIRECTORY_SEPARATOR,0777);
            }
        }

        $category = Category::where('alias', 'img')->first();

        $url = 'https://oursketch.com/resource?category=ui&page=1';
        $cache_path = storage_path('collection'. DIRECTORY_SEPARATOR. 'oursketch_tmp');
        $ql = QueryList::get($url, null, [
            'cache' => $cache_path,
        ]);

        $rules = [
            // 采集文章标题
            'cover_img' => ['.cover a img','data-src'],
            // 采集文章作者
            'title' => ['.list-info>a','title'],
            // 采集文章内容
            'down_url' => ['.download','href'],
            'detail_url' => ['.list-info>a','href']
        ];

        $range = '.resource-list>li';
        $all = $ql->rules($rules)->range($range)->query()->getData();
        $ql->destruct();

        /*$rules_detail = [
            'content' => ['.content img', 'data-src']
        ];*/
//        $ql = QueryList::rules($rules_detail);
        $ql = new QueryList();
        foreach ($all as $item) {
            $detail_url = 'https://oursketch.com' . $item['detail_url'];
            $content_img = $ql->get($detail_url)->find('.content img')->attr('data-src');
            $tag = $ql->get($detail_url)->rules([
                'tag' => ['.tag-box a', 'text']
            ])->query()->getData()->toArray();
            $ql->destruct();

            $tag = array_column($tag, 'tag');

            $down_url_arr = explode('/', $item['down_url']);
            $old_file_name = end($down_url_arr);
            $down_file_name = saveWebFile( 'https://cdn.oursketch.com/' . rawurlencode($old_file_name), $this->oursketch_path . $old_file_name, array(
                "authority: cdn.oursketch.com,method: GET,path: /" . rawurlencode($old_file_name),
                "Postman-Token: 11208879-c82c-4648-93f7-6c3f008aaa98",
                "accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3",
                "accept-encoding: gzip, deflate, br",
                "accept-language: zh-CN,zh;q=0.9",
                "cache-control: no-cache,no-cache",
                "cookie: Hm_lvt_70a1d60c3498fd09334af15ab61ef4d8=1576941384; Hm_lpvt_70a1d60c3498fd09334af15ab61ef4d8=1577023784",
                "pragma: no-cache",
                "referer: https://oursketch.com/resource?category=ui",
                "sec-fetch-mode: navigate",
                "sec-fetch-site: same-site",
                "sec-fetch-user: ?1",
                "upgrade-insecure-requests: 1",
                "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/76.0.3809.132 Safari/537.36"
            ));

            



            $content_img_arr = explode('/', 'https:' . $content_img);
            $content_img_old_file_name = end($content_img_arr);
            $ext = strrchr($content_img_old_file_name, ".");
            $content_img_new_name = date('YmdHis').rand('10000','99999') . $ext;
            $file_name = saveWebFile( 'https:' . $content_img, $this->uisheauto_path . $content_img_new_name, [
                "Referer: " . $detail_url,
                "Sec-Fetch-Mode: no-cors",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36",
            ]);
            $content_img_new_url = env('COLLECTION_IMG_URL').date('Ymd').'/'.$content_img_new_name; //最大的图作为内容

            $cover_img_arr = explode('/', 'https:' . $item['cover_img']);
            $cover_img_old_file_name = end($cover_img_arr);
            $cover_img_new_name = date('YmdHis').rand('10000','99999') . $ext;
            $file_name = saveWebFile( 'https:' . $item['cover_img'], $this->uisheauto_path . $cover_img_new_name, [
                "Referer: https://oursketch.com/resource?category=ui",
                "Sec-Fetch-Mode: no-cors",
                "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.120 Safari/537.36",
            ]);
            $cover_img_new_url = env('COLLECTION_IMG_URL').date('Ymd').'/'.$cover_img_new_name; // 最小的作为缩略图
            
            $tag_list = Tag::whereIn('name', $tag)->get()->keyBy('name')->toArray();

            $tag_id = [];
            $tag_new_id = [];
            foreach ($tag as $tag_index => $tag_item) {
                if ( isset($tag_list[$tag_item]) ){
                    $tag_id[] = $tag_item_id = $tag_list[$tag_item]['id'];
                    Tag::where('id', $tag_item_id)->increment('count');
                }else{
                    $tag_id[] = $tag_item_id = Tag::insertGetId([
                        'name' => $tag_item,
                        'alias' => $tag_item,
                        'count' => 1,
                    ]);
                }

                $tag_new_id[] = [
                    'tag_id' => $tag_item_id,
                    'tag' => $tag_item
                ];
            }

            $news_id = News::insertGetId([
                'admin_id' => 1,
                'title' => $item['title'],
                'content' => '<img class="alignnone size-medium" src="'. $content_img_new_url .'"  />',
                'status' => News::Status_Normal,
                'comment_status' => News::Comment_Status_On,
                'cover_img' => $cover_img_new_url,
                'down_type' => News::Down_Type_Login,
                'down_level' => 0,
                'down_price' => 0,
                'down_url' => $down_file_name . '|无',
                'category_id' => $category->name,
                'category' => $category->alias,
                'tag_id' => json_encode($tag_id),
                'tag' => json_encode($tag, JSON_UNESCAPED_UNICODE),
            ]);

            CategoryNew::insert([
                'cat_new_id' => $news_id,
                'cat_id' => $category->id,
                'category' => $category->alias
            ]);
            foreach ($tag_new_id as $tag_new_item) {
                $tag_new_item['tag_new_id'] = $news_id;
            }

            TagNew::insert($tag_new_item);
            echo $news_id;
die;
        }
    }
}
