<?php
namespace app\admin\controller;
use app\admin\model\System as SystemModel;

use app\AdminController;
use think\App;

class System extends AdminController
{
    public function index()
    {
        $id = input('id',0);
        $systemModel = new SystemModel();
        $action = $systemModel->where(['istype'=>0])->find($id);
        if(request()->isPost())
        {
            $post = request()->post();
            $post['status'] = isset($post['status']) && $post['status'] == 'on'?1:0;
            $systemModel->save($post);
            $this->success('操作成功',url('/system/index'));
        }
        return view('index', ['info' => $action]);
    }
}