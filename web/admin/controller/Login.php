<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 登录功能
----------------------------------------------------------------*/
namespace app\admin\controller;
use app\admin\model\Role;
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
            $rememberme = isset($data['remember_me']) ? true : false;
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
                header('location:'.url('/admin/index'));exit;
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
}