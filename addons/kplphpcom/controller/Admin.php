<?php
/**
 * Created by PhpStorm.
 * User: Win10 - Jeffery 13..@qq.com
 * Date: 2019/11/16
 * Time: 14:40
 */
namespace addons\kplphpcom\controller;
use addons\BaseController;
use think\App;
use think\Db;

class Admin extends BaseController
{
    public function initialize()
    {
        parent::initialize();
    }

    # 管理入口
    public function manage()
    {
        return view('../addons/kplphpcom/view/admin/manage.html', []);
    }

    public function link()
    {
        echo '试试访问该插件这个link的东西';
    }
}