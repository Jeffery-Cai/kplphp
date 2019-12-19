<?php
namespace app\admin\controller;

use app\AdminController;
use think\facade\View;
class Admin extends AdminController
{
    public function index()
    {
//        $action = $this->request->controller();
        // 模板变量赋值
        View::assign('name','ThinkPHP');
        View::assign('email','thinkphp@qq.com');
        // 或者批量赋值
        View::assign([
            'name'  => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ]);
        // 模板输出
        return View::fetch();
    }

    public function hello($name = 'ThinkPHP6')
    {
        return 'hello,' . $name;
    }

    public function addonss()
    {
        echo $this->app->addons->getAddonsPath();
    }


}
