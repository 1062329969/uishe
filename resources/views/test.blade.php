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
<form action="/upload" method="post">
    @csrf
    <?php
//    echo form_upload_images('assets', 'aaa', ['assets_id' => 7])
    echo form_upload_attaches('assets', 'attach', 1)
    ?>
    <input type="submit" name="btn" value="提交">
</form>
