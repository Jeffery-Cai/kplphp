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
use app\admin\logic\User;
use think\App;
use think\captcha\facade\Captcha;
use think\facade\View;

class Login extends AdminController
{
    public function index()
    {

        if (request()->isPost()) {
            $post = request()->post();
            $rememberme = isset($post['remember_me']) ? true : false;
            $result = $this->validate($post, 'User.signin');
            if(true !== $result){
                $this->error($result);
            }
            $uid = User::login($post['username'], $post['password'], $rememberme);
            if ($uid[0]!=0) {
                $this->success('登录成功',url('/admin/index'));
            } else {
                $this->error($uid[1]);
            }
        } else {
            if ($this->is_signin()) {
                $this->redirect(url('/admin/index'));
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
        $this->redirect(url('/login/index'));
    }

}