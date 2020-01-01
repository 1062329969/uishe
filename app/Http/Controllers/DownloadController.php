<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comments;
use App\Models\News;
use App\Models\Tag;
use App\Models\TagNew;
use App\Models\Thematic;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DownloadController extends Controller
{

    public function check(Request $request, $down_type, $id){
//        echo $down_type;
//        echo $id;
        switch ($down_type){
            case 'news':
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
                if ( $user->user_type < $news->down_level && $news->down_type == News::Down_Type_Vip) {
                    return response()->error(500, 'LevelError');
                }

                if ( $user->credit < $news->down_price && $news->down_type == News::Down_Type_Integral) {
                    return response()->error(500, 'CreditError');
                }

                if ( $news->down_url ) {
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

    public function newsDownload(Request $request, $id) {

        $new = News::where([
            'status' => News::Status_Normal,
            'id' => $id,
        ])->first();
        if( $new->isEmpty() ) {

        }

    }
}
