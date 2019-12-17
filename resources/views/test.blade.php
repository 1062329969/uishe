<?php
/**
 * Created by PhpStorm.
 * User: liucg
 * Date: 2019/12/16
 * Time: 7:41 PM
 */
?>
@include('home.common.top')
<script src="/js/MyWebUploader.js"></script>
<?php
echo form_upload_image('assets','aaa',['assets_id'=>7])
?>

