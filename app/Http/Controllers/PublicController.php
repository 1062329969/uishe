<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicController extends Controller
{
//    use Msg;
    //图片上传处理
    public function uploadImg(Request $request)
    {

        //上传文件最大大小,单位M
        $maxSize = 10;
        //支持的上传图片类型
        $allowed_extensions = ["png", "jpg", "gif"];
        //返回信息json
        $data = ['code'=>200, 'msg'=>'上传失败', 'data'=>''];
        $file = $request->file('file');

        //检查文件是否上传完成
        if ($file->isValid()){
            //检测图片类型
            $ext = $file->getClientOriginalExtension();
            if (!in_array(strtolower($ext),$allowed_extensions)){
                $data['msg'] = "请上传".implode(",",$allowed_extensions)."格式的图片";
                return response()->json($data);
            }
            //检测图片大小
            if ($file->getClientSize() > $maxSize*1024*1024){
                $data['msg'] = "图片大小限制".$maxSize."M";
                return response()->json($data);
            }
        }else{
            $data['msg'] = $file->getErrorMessage();
            return response()->json($data);
        }
        $newFile = date('Y').'/'.date('Y-m-d')."_".time()."_".uniqid().".".$file->getClientOriginalExtension();
//        $disk = QiniuStorage::disk('qiniu');
//        $res = $disk->put($newFile,file_get_contents($file->getRealPath()));
        $path=$file->getRealPath();
        $bool= Storage::disk('local')->put($newFile,file_get_contents($path));

        if($bool){
            $data = [
                'code'  => 0,
                'msg'   => '上传成功',
                'data'  => $newFile,
                'url'   => env('APP_IMG_URL').$newFile
            ];
        }else{
            $data['data'] = $file->getErrorMessage();
        }
        return response()->json($data);
    }



}