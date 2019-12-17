<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    //
    protected $table = 'upload';

    /**
     * 组织ci upload所需的参数
     * @return array
     */
    private static function get_upload_lib_config($config_flag)
    {
        $config = array();

        $default_params = config('webuploader.upload')[$config_flag];

        $config['upload_path'] = $default_params['path'] . $default_params['path_level'];
        $config['allowed_types'] = $default_params['allow'];
        $config['file_name'] = md5(microtime(true));
        //$config['detect_mime'] = $this->set_detect_mime_switch();

        return $config;
    }

    /**
     * 执行flash的上传操作
     *
     * @param array $params
     * @return array
     */
    public static function upload($params = array(), $file = array())
    {

        $upload_config = self::get_upload_lib_config($params['config_flag']);

        $upload_config['upload_full_path'] = public_path($upload_config['upload_path']);

        /* 没文件直接跳过 */
        if (!$file->isValid()) {
            return array(
                'message' => '无附件上传',
                'code' => -1, // 上传结果代码，0表示成功，－1表示失败
            );
        }

        if ($file->getClientOriginalExtension() && !in_array($file->getClientOriginalExtension(), $upload_config['allowed_types'])) {
            return array(
                'message' => '文件格式有误',
                'code' => -1, // 上传结果代码，0表示成功，－1表示失败
            );
        }


        $extension = $file->getClientOriginalExtension();
        $fileName = $upload_config['file_name'] . '.' . $extension;


        $upload_info = new Upload();
        $upload_info->model = $params['use_model'];
        $upload_info->file_name = $fileName;
        $upload_info->file_type = '';
        $upload_info->file_path = $upload_config['upload_path'];
        $upload_info->full_path = $upload_config['upload_path'] . '/' . $fileName;
        $upload_info->raw_name = $upload_config['file_name'];
        $upload_info->orig_name = rtrim($file->getClientOriginalName(),'.' . $extension);
        $upload_info->client_name = $file->getClientOriginalName();
        $upload_info->file_ext = $extension;
        $upload_info->file_size = $params['size'];
        $res = $upload_info->save();

        if ($res) {
            $file->move($upload_config['upload_full_path'], $fileName);
            return array(
                'message' => $upload_info,
                'code' => 0, // 上传结果代码，0表示成功，－1表示失败
            );
        } else {
            return array(
                'message' => '文件上传失败',
                'code' => -1, // 上传结果代码，0表示成功，－1表示失败
            );
        }


    }
}
