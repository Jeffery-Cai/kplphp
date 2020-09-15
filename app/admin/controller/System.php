<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 系统功能
----------------------------------------------------------------*/
namespace app\admin\controller;
use app\admin\model\System as SystemModel;

use app\admin\model\User;
use app\AdminController;
use think\App;

class System extends AdminController
{
    public function index()
    {
        return view('index');
    }

    # 密码设置
    public function set_password()
    {
        if (request()->isPost()) {
            $post = request()->post();
            if(empty($post['password']) || empty($post['newpassword']) || empty($post['comnewpassword']))
            {
                $this->error('都得填写!');
            }
            $uid = session('user_auth.uid');
            $u = User::field('password')->find($uid);
            if(empty($u))
            {
                $this->error('不存在!');
            }
            if (!password_verify((string)$post['password'], $u['password']))
            {
                $this->error('当前密码输入错误!');
            }
            if($post['newpassword']!=$post['comnewpassword'])
            {
                $this->error('确认密码不一致!');
            }
            $d['password'] = password_hash((string)$post['newpassword'], PASSWORD_DEFAULT);
            $status = User::where('id', $uid)->update($d);
            if($status){
                $this->success('修改成功!',url('/login/loginout'));
            }else{
                $this->error('系统繁忙!');
            }
        }
    }

    # 其他设置
    public function other_set()
    {
        $id = input('id',0);
        $systemModel = new SystemModel();
        $action = $systemModel->where(['istype'=>0])->find($id);
        if(request()->isPost())
        {
            $this->success('操作成功');exit;
            $post = request()->post();
            $post['status'] = isset($post['status']) && $post['status'] == 'on'?1:0;
            $systemModel->save($post);
            $this->success('操作成功',url('/system/index'));
        }
        return view('index', ['info' => $action]);
    }
}