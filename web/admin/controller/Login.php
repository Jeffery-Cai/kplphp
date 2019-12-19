<?php
/**
 * Created by PhpStorm.
 * User: Win10 - Jeffery 13..@qq.com
 * Date: 2019/11/20
 * Time: 16:53
 */
namespace app\admin\controller;
use app\AdminController;
use app\admin\model\Menu;
use app\admin\model\Role;
use app\admin\model\User as UserModel;
use think\App;
use think\facade\Config;
use think\facade\View;
class Login extends AdminController
{
    public function index()
    {
        if (request()->isPost()) {
            // 获取post数据
            $data = request()->post();
            $rememberme = isset($data['remember-me']) ? true : false;

            // 验证数据
            $result = $this->validate($data, 'User.signin');
            if(true !== $result){
                // 验证失败 输出错误信息
                $this->error($result);
            }

            // 验证码
            if (Config::get('app.captcha_signin')) {
                $captcha = request()->post('captcha', '');
                $captcha == '' && $this->error('请输入验证码');
                if(!captcha_check($captcha, '')){
                    //验证失败
                    $this->error('验证码错误或失效');
                };
            }

            // 登录
            $UserModel = new UserModel();
            $uid = $UserModel->login($data['username'], $data['password'], $rememberme);
            if ($uid[0]!=0) {
                // 记录行为
//                action_log('user_signin', 'admin_user', $uid, $uid);
                $this->success('登录成功',url('/admin/index'));
//                header('location:'.url('/admin/index'));exit;
            } else {
                $this->error($uid[1]);
            }
        } else {
            if ($this->is_signin()) {
                $this->jumpUrl();
            } else {
                return View::fetch();
            }
        }
    }

    public function loginout()
    {
        session(null);
        cookie('uid', null);
        cookie('signin_token', null);
        $this->success('退出登录',url('/login/index'));
    }


    /**
     * 跳转到第一个有权限访问的url
     * @return mixed|string
     */
    private function jumpUrl()
    {
        if (session('user_auth.role') == 1) {

            header('location:'.url('/admin/index'));exit;
        }
        $default_module = Role::where('id', session('user_auth.role'))->value('default_module');
        $menu = Menu::find($default_module);
        if (!$menu) {
            $this->error('当前角色未指定默认跳转模块！');
        }

        if ($menu['url_type'] == 'link') {
            header('location:'.$menu['url_value']);exit;
        }

        $menu_url = explode('/', $menu['url_value']);
        role_auth();
        $url = action('/admin/ajax/getSidebarMenu', ['module_id' => $default_module, 'module' => $menu['module'], 'controller' => $menu_url[1]]);
        if ($url == '') {
            $this->error('权限不足');
        } else {
            header('location:'.$url);exit;
        }
    }
}