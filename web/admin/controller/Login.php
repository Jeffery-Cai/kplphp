<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 登录功能
----------------------------------------------------------------*/
namespace app\admin\controller;
use app\AdminController;
use app\admin\model\Menu;
use app\admin\logic\User;
use think\App;
use think\facade\Config;
use think\facade\View;
class Login extends AdminController
{
    public function index()
    {
        if (request()->isPost()) {
            $data = request()->post();
            $rememberme = isset($data['remember-me']) ? true : false;
            $result = $this->validate($data, 'User.signin');
            if(true !== $result){
                $this->error($result);
            }
            if (Config::get('app.captcha_signin')) {
                $captcha = request()->post('captcha', '');
                $captcha == '' && $this->error('请输入验证码');
                if(!captcha_check($captcha, '')){
                    //验证失败
                    $this->error('验证码错误或失效');
                };
            }
            $uid = User::login($data['username'], $data['password'], $rememberme);
            if ($uid[0]!=0) {
                $this->success('登录成功',url('/admin/index'));
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
        halt(123);
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