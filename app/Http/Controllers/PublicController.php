<?php

namespace App\Http\Controllers;

use App\Models\Multi_upload;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Overtrue\LaravelUploader\StrategyResolver;

class PublicController extends Controller
{
//    use Msg;

    public function upload(Request $request)
    {
        $request_data = $request->all();
        Multi_upload::save_info($request_data['assets'],'1','news');
        dd($request_data);
    }

    public function upload_file1(Request $request)
    {
        // 设置超时时间为没有限制
        ini_set("max_execution_time", "0");

        $http_type = ((isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';

        $file = $request->file('file');

        $allowed_extensions = ["png", "jpg", "gif", "jpeg", "bmp"];
        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $allowed_extensions)) {
            return json_encode(['error' => 'You may only upload png, jpg or gif or jpeg or bmp.']);
        }

        $destinationPath = 'storage/uploads/'.date('Ymd').'/'; //public 文件夹下面建 storage/uploads 文件夹
        $extension = $file->getClientOriginalExtension();
        $fileName = md5(microtime(true)).'.'.$extension;
        $file->move($destinationPath, $fileName);

        return json_encode(['type' => $extension , 'url' => $http_type.$_SERVER['HTTP_HOST'].'/'.$destinationPath.'/'.$fileName , 'name' => $fileName]);

    }

    public function upload_file(Request $request)
    {
        ini_set('display_errors','on');
        $response = array(
            'message' => '未知上传错误',
            'file_id' => '',
            'path' => '',
            'code' => -1, // 上传结果代码，0表示成功，－1表示失败
            'width' => 0,
            'height' => 0,
            'scale' => 1, // 比例尺
            'type' => 'img', //类型
            'name' => ''
        );


        $item = $request->only([ 'use_model',
            'input_name',
            'config_flag',
            'show_type',
            'is_private',
            'auto_crop',
            'auto_crop_width',
            'auto_crop_height',
            'size',
            'min_width',
            'min_height',]);
        //print_arr($item);

        $file = $request->file($item['input_name']);

        $upload_info = Upload::upload($item,$file);

        //print_arr($upload_info);
        if($upload_info['code'] < 0)
        {
            $response = array(
                'message' => $upload_info['message'],
            );
        }
        else
        {
            $data = $upload_info['message'];

            $response = array(
                'message' => '上传成功',
                'code' => 0, // 上传结果代码，0表示成功，－1表示失败
                'file_info' => $data
            );

        }

        $json_str = json_encode($response);

        echo $json_str;
    }


    //图片上传处理
    public function uploadImg(Request $request)
    {

        //上传文件最大大小,单位M
        $maxSize = 10;
        //支持的上传图片类型
        $allowed_extensions = ["png", "jpg", "gif"];
        //返回信息json
        $data = ['code' => 200, 'msg' => '上传失败', 'data' => ''];
        $file = $request->file('file');

        //检查文件是否上传完成
        if ($file->isValid()) {
            //检测图片类型
            $ext = $file->getClientOriginalExtension();
            if (!in_array(strtolower($ext), $allowed_extensions)) {
                $data['msg'] = "请上传" . implode(",", $allowed_extensions) . "格式的图片";
                return response()->json($data);
            }
            //检测图片大小
            if ($file->getClientSize() > $maxSize * 1024 * 1024) {
                $data['msg'] = "图片大小限制" . $maxSize . "M";
                return response()->json($data);
            }
        } else {
            $data['msg'] = $file->getErrorMessage();
            return response()->json($data);
        }
        $newFile = date('Y') . '/' . date('Y-m-d') . "_" . time() . "_" . uniqid() . "." . $file->getClientOriginalExtension();
//        $disk = QiniuStorage::disk('qiniu');
//        $res = $disk->put($newFile,file_get_contents($file->getRealPath()));
        $path = $file->getRealPath();
        $bool = Storage::disk('local')->put($newFile, file_get_contents($path));

        if ($bool) {
            $data = [
                'code' => 0,
                'msg' => '上传成功',
                'data' => $newFile,
                'url' => env('APP_IMG_URL') . $newFile
            ];
        } else {
            $data['data'] = $file->getErrorMessage();
        }
        return response()->json($data);
    }


}