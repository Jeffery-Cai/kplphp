<?php
/**
 * Created by PhpStorm.
 * User: Win10 - Jeffery 13..@qq.com
 * Date: 2019/11/16
 * Time: 14:40
 */
namespace addons\test\controller;
use think\facade\View;

class Index
{
    # 管理后台入口 - 主页(必须得存在)
    public function manage()
    {
        return View::fetch('../addons/test/view/manage/index');
    }
}