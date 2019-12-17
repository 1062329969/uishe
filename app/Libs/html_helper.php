<?php
/**
 * Created by PhpStorm.
 * User: liucg
 * Date: 2019/12/16
 * Time: 7:33 PM
 */

if (!function_exists('form_upload_images')) {
    function form_upload_images_bak()
    {
        $str = '<link rel="stylesheet" type="text/css" href="' . URL::asset('css/webuploader.css') . '">
<script type="text/javascript" src="' . URL::asset('js/webuploader.min.js') . '"></script>
<div id="uploader-demo">
  <div id="fileList" class="uploader-list"></div>
  <div id="filePicker">选择图片</div>
</div>
<div id="show_img" style="display:none;">
  <img id="thumb_img" src="" alt="图片" height="100px">
</div>
<script>
    var $list = $("#fileList");   //这几个初始化全局的百度文档上没说明，好蛋疼
    var thumbnailWidth = 100;   //缩略图高度和宽度 （单位是像素），当宽高度是0~1的时候，是按照百分比计算，具体可以看api文档
    var thumbnailHeight = 100;
    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: true,
        formData: {
            // 这里的token是外部生成的长期有效的，如果把token写死，是可以上传的。
            _token:\'' . csrf_token() . '\'
        },
        // swf文件路径
        swf: \'http://cdn.staticfile.org/webuploader/0.1.0/Uploader.swf\', //加载swf文件，路径一定要对
        // 文件接收服务端。
        server: \'' . url('upload/upload_file') . '\',
        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: {
            id : \'#filePicker\',
            multiple : false
        },
        // 只允许选择图片文件。
        accept: {
            title: \'Images\',
            extensions: \'gif,jpg,jpeg,bmp,png\',
            mimeTypes: \'image/\'
        }
    });
    // 文件上传过程中创建进度条实时显示。
    uploader.on( \'uploadProgress\', function( file, percentage ) {
        var $li = $( \'#\'+file.id ),$percent = $li.find(\'.progress span\');

        // 避免重复创建
        if ( !$percent.length ) {
            $percent = $(\'<p class="progress"><span></span></p>\').appendTo( $li ).find(\'span\');
        }

        $percent.css( \'width\', percentage * 100 + \'%\' );
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( \'uploadSuccess\', function(file,response) {
        var imgurl = response.url;
        $(\'#thumb_img\').attr(\'src\',imgurl);

        $(\'#show_img\').css(\'display\',\'block\');

        $( \'#\'+file.id ).addClass(\'upload-state-done\');
    });

    // 文件上传失败，显示上传出错。
    uploader.on( \'uploadError\', function(file,response) {
        var fileerror = response.error;

        var $li = $( \'#\'+file.id ),$error = $li.find(\'div.error\');

        // 避免重复创建
        if ( !$error.length ) {
            $error = $(\'<div class="error"></div>\').appendTo( $li );
        }

        $error.text(\'上传失败\'+fileerror);
    });

    // 完成上传完了，成功或者失败，先删除进度条。
    uploader.on( \'uploadComplete\', function( file ) {
        $( \'#\'+file.id ).find(\'.progress\').remove();
    });
</script>
';
        return $str;
    }


    /**
     * 将KB转成可阅读格式
     *
     * @param int $num
     * @return string
     */
    function num_kbunit($num)
    {
        $bitunit = array(
            ' KB',
            ' MB',
            ' GB'
        );
        for ($key = 0, $count = count($bitunit); $key < $count; $key++) {
            if ($num >= pow(2, 10 * $key) - 1) // 1024B 会显示为 1KB
            {
                $num_bitunit_str = (ceil($num / pow(2, 10 * $key) * 100) / 100) . " $bitunit[$key]";
            }
        }

        return $num_bitunit_str;
    }


    /**
     * 跟据id取得图片信息
     *
     * @param string $alpha_id
     * @return array
     */
    function get_info($alpha_id)
    {
        if (!$alpha_id) return false;

        $info = \App\Models\Upload::find($alpha_id);

        if (!$info) return false;

        $new_info = array();
        /* alpha_id	model 属于哪个模型	file_name 已上传的文件名（包括扩展名）	file_type 文件的Mime类型	file_path 不包括文件名的文件绝对路径
        full_path 包括文件名在内的文件绝对路径	raw_name 不包括扩展名在内的文件名部分	orig_name 上传的文件最初的文件名	client_name 上传的文件在客户端的文件名
        file_ext 文件扩展名包括.	file_size 图像大小，单位是kb	is_image 是否是图像1是,0不是	image_width	image_height	image_type 文件类型，即文件扩展名不包括.
        image_size_str 一个包含width和height的字符串	pid 图片父id	thumb 缩略图标识	crop_mode 裁剪模式,manual手动,auto自动	is_private 是否为内部文件 */
        $new_info['id'] = $info['id'];
        $new_info['file_name'] = $info['file_name'];
        $new_info['full_path'] = $info['full_path'];
        $new_info['client_name'] = $info['orig_name'];
        $new_info['file_ext'] = ltrim(strtolower($info['file_ext']), '.');
        $new_info['file_size'] = num_kbunit($info['file_size']);

        $new_info['is_image'] = $info['is_image'];
        $new_info['image_width'] = $info['image_width'];
        $new_info['image_height'] = $info['image_height'];
        $new_info['image_size_str'] = $info['image_size_str'];
        return $new_info;
    }


    /**
     * 跟据关联id取得文件列表
     *
     * @param int $rid
     * @return array
     */
    function get_list($rid, $use_model = false)
    {
        if ($use_model) {
            $has_data = \App\Models\Multi_upload::with('upload_relation')->where('rid', $rid)->where('model', $use_model)->get();
        }

        if (!$has_data->toArray()) {
            return false;
        }
        //p($has_data);

        $new_data = array();

        foreach ($has_data as $item) {
            $new_info = array();
            $new_info['id'] = $item->upload_relation->id;
            $new_info['file_name'] = $item->title;
            $new_info['full_path'] = $item->upload_relation->full_path;
            $new_info['client_name'] = $item->upload_relation->orig_name;
            $new_info['file_ext'] = ltrim(strtolower($item->upload_relation->file_ext), '.');
            $new_info['file_size'] = num_kbunit($item->upload_relation->file_size);

//            $new_info['is_image'] = $item['is_image'];
//            $new_info['image_width'] = $item['image_width'];
//            $new_info['image_height'] = $item['image_height'];
//            $new_info['image_size_str'] = $item['image_size_str'];

            $new_data[] = $new_info;
        }

        return $new_data;
    }


    /**
     * @param $input_name
     * @param $use_model
     * @param $item
     * @param array $params
     * @return string
     */
    function show_webUpload($input_name, $use_model, $item, $params = array(), $limit = 0, $key = false)
    {
        $file_info = array();
        if ($item) {
            if ($limit) {
                //单文件
                if ($key)
                    $file_info = get_info($item[$key . '_id']);
                else
                    $file_info = get_info($item[$input_name . '_id']);
                if ($file_info)
                    $file_info = array($file_info);
            } else {
                //多文件
                $file_info = get_list($item,$use_model);
            }
        }

        $params['input_name'] = $input_name;
        $params['use_model'] = $use_model;

        //单文件框固定值
        $params['swf_multi'] = false;
        $input_str = '<div class="webupload-con" id="' . $input_name . '"><div class="uploader-list">';
        if ($file_info)
            foreach ($file_info as $v) {
                $input_str .= '<div class="item">
<span onclick="" class="webuploadinfo">' . $v['client_name'] . '.' . $v['file_ext'] . '</span>
<div class="webuploadinfodiv"><span class="webuploadsize">' . $v['file_size'] . '</span>
<span class="webuploadstate">已上传</span>
<div class="webuploadDbtn">删除</div></div>';
                if ($limit) {
                    $input_str .= '<input type="hidden" name="' . $input_name . '" value="' . $v['full_path'] . '">';
                    $input_str .= '<input type="hidden" name="' . $input_name . '_id" value="' . $v['id'] . '">';
                } else {
                    $input_str .= '<input type="hidden" name="' . $input_name . '[' . $v['id'] . ']" value="' . $v['client_name'] . '">';
                }
                $input_str .= '</div>';
            }
        $input_str .= '</div></div>';
        $input_str .= webUpload_script($params, $limit);

        return $input_str;
    }

    function webUpload_script($params, $limit = 0)
    {
        $config_info = config('webuploader.upload')[$params['config_flag']];
//        dump($config_info);
//        dd($params);
        $str = "<script>
    $(function () {
        powerWebUpload($('#" . $params['input_name'] . "'),{
            auto: false,limit:$limit, name: '" . $params['input_name'] . "', allowType:'." . implode(' ', $config_info['allow']) . "',
            accept: {
//                title: 'Images',
                extensions: '" . implode(',', $config_info['allow']) . "',
//                mimeTypes: 'image/*'
            },
            datas: {
                use_model: '" . $params['use_model'] . "',
                show_type: '" . $params['show_type'] . "',
//                is_private: 1,
                input_name: 'file',
                  _token:'" . csrf_token() . "',
                config_flag: '" . $params['config_flag'] . "',
            }
        });
    });
</script>";
        return $str;
    }


    function form_upload_attaches($input_name, $use_model, $rid, $params = array())
    {

        $params['config_flag'] = isset($params['config_flag']) ? $params['config_flag'] : 'attach';
        $params['show_type'] = 'attach';

        return show_webUpload($input_name, $use_model, $rid, $params);
    }

    function form_upload_attach($input_name, $use_model, $rid, $params = array())
    {

        $params['config_flag'] = isset($params['config_flag']) ? $params['config_flag'] : 'attach';
        $params['show_type'] = 'attach';

        return show_webUpload($input_name, $use_model, $rid, $params, 1);
    }

    function form_upload_image($input_name, $use_model, $rid, $params = array())
    {

        $params['config_flag'] = isset($params['config_flag']) ? $params['config_flag'] : 'img';
        $params['show_type'] = 'img';

        return show_webUpload($input_name, $use_model, $rid, $params, 1);
    }

    function form_upload_images($input_name, $use_model, $rid, $params = array())
    {

        $params['config_flag'] = isset($params['config_flag']) ? $params['config_flag'] : 'img';
        $params['show_type'] = 'img';

        return show_webUpload($input_name, $use_model, $rid, $params);
    }
}