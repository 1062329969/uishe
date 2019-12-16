<?php
/**
 * Created by PhpStorm.
 * User: liucg
 * Date: 2019/12/16
 * Time: 8:05 PM
 */

return [
    'upload' => array(
        'img' => array(
            'path' => '/storage/upload/images/',
            'allow' => array(
                'gif',
                'jpg',
                'jpeg',
                'png'
            ),
            'path_level' => date('Ym'),
            'support_crop' => true,
            'show_type' => 'img',
            /* 'thumb' => array(
                    'ms' => array(100, 100),
                    's' => array(150, 150),
                    'b' => array(800, 800),
            ), */
        ),
        'attachs' => array(
            'path' => '/storage/upload/attachs/',
            'allow' => array(
                'doc',
                'docx',
                'txt',
                'xls',
                'xlsx',
                'ppt',
                'pptx',
                'pdf',
                'jpg',
                'gif',
                'jpeg',
                'png',
                'rar',
                'zip'
            ),
            'path_level' => date('Ym'),
            'show_type' => 'attach',
        ),
    )
];