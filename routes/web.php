<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use \Illuminate\Support\Facades\DB;
$category = DB::table('category')->pluck('alias')->toArray();
//dd($list);
//迁移工具
Route::get('/qianyi/category', 'QianyiController@category');
Route::get('/qianyi/tag', 'QianyiController@movetag');
Route::get('/qianyi/move_comment', 'QianyiController@move_comment');
Route::get('/qianyi/move_posts', 'QianyiController@move_posts');
Route::get('/qianyi/move_users', 'QianyiController@move_users');



Route::get('/', 'HomeController@index');
Route::get('/{category}', function(Request $request, $category) {
    return (new NewsController())->category($request, $category);
})->where('category', implode('|', $category)); // 正则表达式 | 或
Route::get('/huiyuan', 'HomeController@huiyuan');
Route::get('/{id}.html', 'NewsController@item')->where('id', '[0-9]+');
Route::get('/tag/{tag}', 'NewsController@tag');
Route::get('/getNewsList', 'NewsController@getNewsList');
Route::any('/login', 'HomeController@login')->name('login');
Route::any('/reg', 'HomeController@reg')->name('reg');




Route::middleware(['auth:users'])->group(function () {

    Route::get('/user/dofav/{action}', 'UserController@dofav');

    //用户
    Route::get('/user/index', 'UserController@index')->name('user');
    Route::get('/user/checkvip', 'UserController@checkvip')->name('checkvip');
    Route::get('/user/collect', 'UserController@collect')->name('collect');
    Route::get('/user/order', 'UserController@orders')->name('order');
    Route::get('/user/downlog', 'UserController@downlog')->name('downlog');
    Route::get('/user/loginout', 'UserController@loginout')->name('loginout');
});


//文件上传接口，前后台共用
Route::post('uploadImg', 'PublicController@uploadImg')->name('uploadImg');