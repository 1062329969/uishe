<?php

namespace App\Http\Controllers;

use App\Libs\WechatSDK;
use App\Models\Category;
use App\Models\Comments;
use App\Models\News;
use App\Models\Tag;
use App\Models\TagNew;
use App\Models\Thematic;
use App\Models\User;
use App\Models\Usercredit;
use App\Models\UsersDownLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DownloadController extends Controller
{

    public function check(Request $request, $down_type, $id){
//        echo $down_type;
//        echo $id;
        switch ($down_type){
            case 'news':

                $checkNewDownd = $this->checkNewDownd($id);

                if(!is_array($checkNewDownd)){
                    return $checkNewDownd;
                }

                $user = $checkNewDownd['user'];
                $news = $checkNewDownd['news'];
                $user_id = $user->id;

                if ( $news->down_url ) {

                    if ( $news->down_price > 0 ) {
                        $user->credit = $user->credit - $news->down_price;
                        $user->save();

                        Usercredit::insert([
                            'user_id' => $user_id,
                            'content' => '下载素材ID'.$id,
                            'from' => 'new',
                            'edit_credit' => -$news->down_price
                        ]);
                    }

                    UsersDownLog::insert([
                        'new_id' => $id,
                        'new_name' => $news->title,
                        'log_time' => Carbon::now()->toDateTimeString(),
                        'user_id' => $user_id,
                        'user_name' => $user->name,
                        'user_ip' => WechatSDK::get_client_ip(),
                    ]);

                    if ( strpos($news->down_url, 'pan.baidu.com') !== false ) {
                        $down_url = explode('|', $news->down_url);
                        return response()->success([
                            'platfrom' => 'baidu',
                            'url' => trim($down_url[0]),
                            'pwd' => $down_url[1] == '无' ? '' : trim($down_url[1]),
                        ]);
                    } elseif( strpos($news->down_url, 'https') !== false ) {
                        $down_url = explode('|', $news->down_url);
                        return response()->success([
                            'platfrom' => 'remote',
                            'url' => trim($down_url[0]),
                            'pwd' => $down_url[1] == '无' ? '' : trim($down_url[1]),
                        ]);
                    } else {
                        return response()->success([
                            'platfrom' => 'local'
                        ]);
                    }
                }
                break;
        }

    }

    public function checkNewDownd($id){
        $news = News::where([
            'status' => News::Status_Normal,
            'id' => $id,
        ])->first();
        if( !$news || !$news->down_url) {
            return response()->error(500, 'Notfound');
        }

        if ($news->down_type == News::Down_Type_Close) {
            return response()->error(500, 'DownClose');
        }

        $user_id = Auth::guard('users')->id();
        if(!$user_id && $news->down_type != News::Down_Type_Every){
            return response()->error(500, 'Unauthenticated.');
        }

        $user = User::find($user_id);
        if ( $user->user_type < $news->down_level && $news->down_level > 0) {
            return response()->error(500, 'LevelError');
        }

        if ( $user->credit < $news->down_price && $news->down_price > 0) {
            return response()->error(500, 'CreditError');
        }

        return [
            'user' => $user,
            'news' => $news
        ];
    }

    public function newsDownload(Request $request, $id) {

        $new = News::where([
            'status' => News::Status_Normal,
            'id' => $id,
        ])->first();
//        dd($new);
        if( $new == null || count($new->toArray()) == 0 ) {
            return redirect(url('/'.$id.'.html'));
        }

        $checkNewDownd = $this->checkNewDownd($id);
//        $user = $checkNewDownd['user'];
        $news = $checkNewDownd['news'];
//        $user_id = $user->id;

        $down_url = explode('|', $news->down_url)[0];

        $file_name = basename($down_url);
        $ext_name = explode('.', $file_name);
        $ext_name = end($ext_name);

        //以只读和二进制模式打开文件
        $file = fopen ( $down_url, "rb" );

        //告诉浏览器这是一个文件流格式的文件
        Header ( "Content-type: application/octet-stream" );
        //请求范围的度量单位
        Header ( "Accept-Ranges: bytes" );
        //Content-Length是指定包含于请求或响应中数据的字节长度
        Header ( "Accept-Length: " . filesize ( $down_url ) );
        //用来告诉浏览器，文件是可以当做附件被下载，下载后的文件名称为$file_name该变量的值。
        Header ( "Content-Disposition: attachment; filename=" . date('YmdHis').'.'. $ext_name );

        //读取文件内容并直接输出到浏览器
        echo fread ( $file, filesize ( $down_url ) );
        fclose ( $file ); //打开的时候要进行关闭这个文件
        exit ();


    }
}
