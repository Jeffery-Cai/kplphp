<?php
/**
 * Created by PhpStorm.
 * User: liliuwei
 * Date: 2019/5/23
 * Time: 22:50
 */
return[
    // 默认跳转页面对应的模板文件
//    'dispatch_success_tmpl' => app()->getRootPath().'/vendor/liliuwei/thinkphp-jump/src/tpl/dispatch_jump.tpl',
//    'dispatch_error_tmpl'   => app()->getRootPath().'/vendor/liliuwei/thinkphp-jump/src/tpl/dispatch_jump.tpl',

// kplphp自定义跳转
    'dispatch_success_tmpl' => app()->getRootPath().'/web/view/admin/dispatch_jump.tpl',
    'dispatch_error_tmpl'   => app()->getRootPath().'/web/view/admin/dispatch_jump.tpl',
];
