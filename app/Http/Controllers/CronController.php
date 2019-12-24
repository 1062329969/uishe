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
    var $pexels_path_uisheauto;


    public function __construct()
    {
        set_time_limit(3600);   // 最大执行时间 3600 一小时。
        $this->pexels_path = storage_path('collection'. DIRECTORY_SEPARATOR. 'pexels' . DIRECTORY_SEPARATOR . date('Ymd').DIRECTORY_SEPARATOR);//资源路径
        $this->pexels_path_zip = storage_path('collection'. DIRECTORY_SEPARATOR. 'pexels' . DIRECTORY_SEPARATOR . date('Ymd').'_zip'.DIRECTORY_SEPARATOR);//资源路径
        $this->pexels_path_uisheauto = storage_path('collection'. DIRECTORY_SEPARATOR. 'uisheauto' . DIRECTORY_SEPARATOR . date('Ymd').DIRECTORY_SEPARATOR);
        $this->oursketch_path = storage_path('collection'. DIRECTORY_SEPARATOR. 'oursketch' . DIRECTORY_SEPARATOR . date('Ymd').DIRECTORY_SEPARATOR);

    }

    public function daily(){
//        $this->getPexels();
        $this->getOursketch();
    }

    public function getPexels(){

        if (!file_exists($this->pexels_path)){//如果不存在pexels采集 今天的文件夹
            mkdir($this->pexels_path, 0777, true);
            chmod($this->pexels_path,0777);

            mkdir($this->pexels_path_zip, 0777, true);
            chmod($this->pexels_path_zip,0777);
        }

        $pexels = new Pexels(self::Pexels_Key);

        $img = $pexels->search('ui');
        $pexels_img = json_decode($img->getBody(), true);
//        $temp = '{"total_results":257,"page":1,"per_page":15,"photos":[{"id":34140,"width":5472,"height":3648,"url":"https:\/\/www.pexels.com\/photo\/iphone-dark-notebook-pen-34140\/","photographer":"Negative Space","photographer_url":"https:\/\/www.pexels.com\/@negativespace","photographer_id":3738,"src":{"original":"https:\/\/images.pexels.com\/photos\/34140\/pexels-photo.jpg","large2x":"https:\/\/images.pexels.com\/photos\/34140\/pexels-photo.jpg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/34140\/pexels-photo.jpg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/34140\/pexels-photo.jpg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/34140\/pexels-photo.jpg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/34140\/pexels-photo.jpg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/34140\/pexels-photo.jpg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/34140\/pexels-photo.jpg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":39284,"width":4288,"height":2848,"url":"https:\/\/www.pexels.com\/photo\/apple-laptop-notebook-office-39284\/","photographer":"Pixabay","photographer_url":"https:\/\/www.pexels.com\/@pixabay","photographer_id":2659,"src":{"original":"https:\/\/images.pexels.com\/photos\/39284\/macbook-apple-imac-computer-39284.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/39284\/macbook-apple-imac-computer-39284.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/39284\/macbook-apple-imac-computer-39284.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/39284\/macbook-apple-imac-computer-39284.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/39284\/macbook-apple-imac-computer-39284.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/39284\/macbook-apple-imac-computer-39284.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/39284\/macbook-apple-imac-computer-39284.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/39284\/macbook-apple-imac-computer-39284.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":1591060,"width":6016,"height":4000,"url":"https:\/\/www.pexels.com\/photo\/web-text-1591060\/","photographer":"Miguel \u00c1. Padri\u00f1\u00e1n","photographer_url":"https:\/\/www.pexels.com\/@padrinan","photographer_id":2072,"src":{"original":"https:\/\/images.pexels.com\/photos\/1591060\/pexels-photo-1591060.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/1591060\/pexels-photo-1591060.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/1591060\/pexels-photo-1591060.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/1591060\/pexels-photo-1591060.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/1591060\/pexels-photo-1591060.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/1591060\/pexels-photo-1591060.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/1591060\/pexels-photo-1591060.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/1591060\/pexels-photo-1591060.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":270348,"width":1920,"height":1032,"url":"https:\/\/www.pexels.com\/photo\/abstract-business-code-coder-270348\/","photographer":"Pixabay","photographer_url":"https:\/\/www.pexels.com\/@pixabay","photographer_id":2659,"src":{"original":"https:\/\/images.pexels.com\/photos\/270348\/pexels-photo-270348.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/270348\/pexels-photo-270348.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/270348\/pexels-photo-270348.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/270348\/pexels-photo-270348.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/270348\/pexels-photo-270348.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/270348\/pexels-photo-270348.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/270348\/pexels-photo-270348.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/270348\/pexels-photo-270348.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":326501,"width":5184,"height":3456,"url":"https:\/\/www.pexels.com\/photo\/apple-computer-desk-devices-326501\/","photographer":"Tranmautritam","photographer_url":"https:\/\/www.pexels.com\/@tranmautritam","photographer_id":8509,"src":{"original":"https:\/\/images.pexels.com\/photos\/326501\/pexels-photo-326501.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/326501\/pexels-photo-326501.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/326501\/pexels-photo-326501.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/326501\/pexels-photo-326501.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/326501\/pexels-photo-326501.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/326501\/pexels-photo-326501.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/326501\/pexels-photo-326501.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/326501\/pexels-photo-326501.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":34225,"width":2312,"height":1542,"url":"https:\/\/www.pexels.com\/photo\/spider-web-34225\/","photographer":"Pixabay","photographer_url":"https:\/\/www.pexels.com\/@pixabay","photographer_id":2659,"src":{"original":"https:\/\/images.pexels.com\/photos\/34225\/spider-web-with-water-beads-network-dewdrop.jpg","large2x":"https:\/\/images.pexels.com\/photos\/34225\/spider-web-with-water-beads-network-dewdrop.jpg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/34225\/spider-web-with-water-beads-network-dewdrop.jpg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/34225\/spider-web-with-water-beads-network-dewdrop.jpg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/34225\/spider-web-with-water-beads-network-dewdrop.jpg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/34225\/spider-web-with-water-beads-network-dewdrop.jpg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/34225\/spider-web-with-water-beads-network-dewdrop.jpg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/34225\/spider-web-with-water-beads-network-dewdrop.jpg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":34600,"width":5472,"height":3648,"url":"https:\/\/www.pexels.com\/photo\/coffee-writing-computer-blogging-34600\/","photographer":"Negative Space","photographer_url":"https:\/\/www.pexels.com\/@negativespace","photographer_id":3738,"src":{"original":"https:\/\/images.pexels.com\/photos\/34600\/pexels-photo.jpg","large2x":"https:\/\/images.pexels.com\/photos\/34600\/pexels-photo.jpg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/34600\/pexels-photo.jpg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/34600\/pexels-photo.jpg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/34600\/pexels-photo.jpg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/34600\/pexels-photo.jpg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/34600\/pexels-photo.jpg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/34600\/pexels-photo.jpg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":109371,"width":3777,"height":2034,"url":"https:\/\/www.pexels.com\/photo\/computer-keyboard-laptop-screen-109371\/","photographer":"Monoar Rahman","photographer_url":"https:\/\/www.pexels.com\/@monoar-rahman-22660","photographer_id":22660,"src":{"original":"https:\/\/images.pexels.com\/photos\/109371\/pexels-photo-109371.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/109371\/pexels-photo-109371.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/109371\/pexels-photo-109371.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/109371\/pexels-photo-109371.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/109371\/pexels-photo-109371.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/109371\/pexels-photo-109371.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/109371\/pexels-photo-109371.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/109371\/pexels-photo-109371.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":160107,"width":5472,"height":3648,"url":"https:\/\/www.pexels.com\/photo\/gray-laptop-computer-showing-html-codes-in-shallow-focus-photography-160107\/","photographer":"Negative Space","photographer_url":"https:\/\/www.pexels.com\/@negativespace","photographer_id":3738,"src":{"original":"https:\/\/images.pexels.com\/photos\/160107\/pexels-photo-160107.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/160107\/pexels-photo-160107.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/160107\/pexels-photo-160107.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/160107\/pexels-photo-160107.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/160107\/pexels-photo-160107.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/160107\/pexels-photo-160107.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/160107\/pexels-photo-160107.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/160107\/pexels-photo-160107.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":67112,"width":6016,"height":4000,"url":"https:\/\/www.pexels.com\/photo\/light-smartphone-macbook-mockup-67112\/","photographer":"Caio Resende","photographer_url":"https:\/\/www.pexels.com\/@caio","photographer_id":7780,"src":{"original":"https:\/\/images.pexels.com\/photos\/67112\/pexels-photo-67112.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/67112\/pexels-photo-67112.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/67112\/pexels-photo-67112.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/67112\/pexels-photo-67112.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/67112\/pexels-photo-67112.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/67112\/pexels-photo-67112.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/67112\/pexels-photo-67112.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/67112\/pexels-photo-67112.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":7358,"width":3000,"height":2092,"url":"https:\/\/www.pexels.com\/photo\/macbook-laptop-smartphone-apple-7358\/","photographer":"Startup Stock Photos","photographer_url":"https:\/\/www.pexels.com\/@startup-stock-photos","photographer_id":2672,"src":{"original":"https:\/\/images.pexels.com\/photos\/7358\/startup-photos.jpg","large2x":"https:\/\/images.pexels.com\/photos\/7358\/startup-photos.jpg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/7358\/startup-photos.jpg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/7358\/startup-photos.jpg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/7358\/startup-photos.jpg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/7358\/startup-photos.jpg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/7358\/startup-photos.jpg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/7358\/startup-photos.jpg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":270360,"width":3088,"height":2056,"url":"https:\/\/www.pexels.com\/photo\/business-code-coding-computer-270360\/","photographer":"Pixabay","photographer_url":"https:\/\/www.pexels.com\/@pixabay","photographer_id":2659,"src":{"original":"https:\/\/images.pexels.com\/photos\/270360\/pexels-photo-270360.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/270360\/pexels-photo-270360.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/270360\/pexels-photo-270360.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/270360\/pexels-photo-270360.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/270360\/pexels-photo-270360.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/270360\/pexels-photo-270360.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/270360\/pexels-photo-270360.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/270360\/pexels-photo-270360.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":251225,"width":5141,"height":3055,"url":"https:\/\/www.pexels.com\/photo\/information-sign-on-shelf-251225\/","photographer":"Tranmautritam","photographer_url":"https:\/\/www.pexels.com\/@tranmautritam","photographer_id":8509,"src":{"original":"https:\/\/images.pexels.com\/photos\/251225\/pexels-photo-251225.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/251225\/pexels-photo-251225.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/251225\/pexels-photo-251225.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/251225\/pexels-photo-251225.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/251225\/pexels-photo-251225.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/251225\/pexels-photo-251225.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/251225\/pexels-photo-251225.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/251225\/pexels-photo-251225.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":230544,"width":5342,"height":3561,"url":"https:\/\/www.pexels.com\/photo\/person-using-black-and-white-smartphone-and-holding-blue-card-230544\/","photographer":"PhotoMIX Ltd.","photographer_url":"https:\/\/www.pexels.com\/@wdnet","photographer_id":21063,"src":{"original":"https:\/\/images.pexels.com\/photos\/230544\/pexels-photo-230544.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/230544\/pexels-photo-230544.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/230544\/pexels-photo-230544.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/230544\/pexels-photo-230544.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/230544\/pexels-photo-230544.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/230544\/pexels-photo-230544.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/230544\/pexels-photo-230544.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/230544\/pexels-photo-230544.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false},{"id":114907,"width":3840,"height":2160,"url":"https:\/\/www.pexels.com\/photo\/silver-laptop-next-to-coffe-cup-smartphone-and-glasses-114907\/","photographer":"Monoar Rahman","photographer_url":"https:\/\/www.pexels.com\/@monoar-rahman-22660","photographer_id":22660,"src":{"original":"https:\/\/images.pexels.com\/photos\/114907\/pexels-photo-114907.jpeg","large2x":"https:\/\/images.pexels.com\/photos\/114907\/pexels-photo-114907.jpeg?auto=compress&cs=tinysrgb&dpr=2&h=650&w=940","large":"https:\/\/images.pexels.com\/photos\/114907\/pexels-photo-114907.jpeg?auto=compress&cs=tinysrgb&h=650&w=940","medium":"https:\/\/images.pexels.com\/photos\/114907\/pexels-photo-114907.jpeg?auto=compress&cs=tinysrgb&h=350","small":"https:\/\/images.pexels.com\/photos\/114907\/pexels-photo-114907.jpeg?auto=compress&cs=tinysrgb&h=130","portrait":"https:\/\/images.pexels.com\/photos\/114907\/pexels-photo-114907.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=1200&w=800","landscape":"https:\/\/images.pexels.com\/photos\/114907\/pexels-photo-114907.jpeg?auto=compress&cs=tinysrgb&fit=crop&h=627&w=1200","tiny":"https:\/\/images.pexels.com\/photos\/114907\/pexels-photo-114907.jpeg?auto=compress&cs=tinysrgb&dpr=1&fit=crop&h=200&w=280"},"liked":false}],"next_page":"https:\/\/api.pexels.com\/v1\/search\/?page=2&per_page=15&query=web"}';
        $img_list = json_decode($pexels_img, true)['photos'];

        $category = Category::where('alias', 'img')->first();

        foreach ($img_list as $item){

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

            if (!file_exists($this->pexels_path_uisheauto)){
                mkdir($this->pexels_path_uisheauto, 0777, true);
                chmod($this->pexels_path_uisheauto,0777);
            }

            copy(reset($new_file_path), $this->pexels_path_uisheauto.reset($new_file_name)); //入库的图 复制到其他目录
            copy(end($new_file_path), $this->pexels_path_uisheauto.end($new_file_name)); //入库的图 复制到其他目录

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

            $file_name = saveWebFile( 'https://cdn.oursketch.com/' . rawurlencode($old_file_name), $this->oursketch_path . $old_file_name, $old_file_name);


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
                'content' => '<img class="alignnone size-medium" src="'. $content_img .'"  />',
                'status' => News::Status_Normal,
                'comment_status' => News::Comment_Status_On,
                'cover_img' => $item['cover_img'],
                'down_type' => News::Down_Type_Login,
                'down_level' => 0,
                'down_price' => 0,
                'down_url' => $file_name . '|无',
                'category_id' => $category->id,
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
die;
        }
    }
}
