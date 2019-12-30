<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 权限功能
----------------------------------------------------------------*/
namespace app\admin\controller;
use app\admin\model\Menu;
use app\admin\model\Module;
use app\admin\model\Role;
use app\admin\model\User;
use think\App;
use think\exception\ErrorException;
use think\exception\ValidateException;
use think\helper\Hash;
use util\Tree;
use app\AdminController;

class Power extends AdminController
{
    public function index()
    {
        $map = [];
        $keyname = trim(input('keyname',''));
        # 用户角色不是超级管理员角色
        /*
        if (session('user_auth.role') != 1) {
            $role_list = Role::getChildsId(session('user_auth.role'));
            $map[] = ['role', 'in', $role_list];
        }
        */
        if(!empty($keyname))
        {
            $map[] = ['username|nickname|email|mobile','like','%'.$keyname.'%'];
        }
        $list = User::where($map)->order('id asc')->page($this->page,$this->size)->select();
        foreach ($list as $k => $v)
        {
            $list[$k]['role_name'] = Role::where('id','=',$v['role'])->value('name');
        }
        if(request()->isPost())
        {
            echo json_encode(['data'=>$list]);exit;
        }
        return view('index', ['list' => $list]);
    }

    public function edit($id=null)
    {
        if(intval($id)==1)halt('不可进入');
        $role = Role::select();
        $userModel = new User();
        if(!empty($id))
        {
            $action = $userModel->find($id);
        }else{
            $action = $userModel;
        }
        if(request()->isPost())
        {
            $post = request()->post();
            // 禁止修改超级管理员的角色和状态
            if ($post['id'] == 1 && $post['role'] != 1) {
                $this->error('禁止修改超级管理员角色');
            }
            // 禁止修改超级管理员的状态
            if ($post['id'] == 1 && $post['status'] != 1) {
                $this->error('禁止修改超级管理员状态');
            }
            try{
                $this->validate($post, 'User');
            }catch (ValidateException $e)
            {
                $this->error($e->getMessage());
            }
            // 如果没有填写密码，则不更新密码
            if ($post['password'] == '' && $id!='') {
                unset($post['password']);
            }else{
                $post['password'] = Hash::make((string)$post['password']);
            }
            // 非超级管理需要验证可选择角色
            if (session('user_auth.role') != 1) {
                if ($post['role'] == session('user_auth.role')) {
                    $this->error('禁止修改为当前角色同级的用户');
                }
                $role_list = Role::getChildsId(session('user_auth.role'));
                if (!in_array($post['role'], $role_list)) {
                    $this->error('权限不足，禁止修改为非法角色的用户');
                }
            }
            $post['status'] = isset($post['status']) && $post['status'] == 'on'?1:0;
            $action->save($post);
            $this->success('操作成功',url('/power/index'));
        }
        return view('edit', ['info' => $action,'role'=>$role]);
    }

    public function del($id=null)
    {
        $info = User::destroy(intval(input('id')));
        if(!($info))$this->error('操作失败');
        $this->success('操作成功',url('/power/index'));
    }

    public function role()
    {
        $map = [];
        $keyname = trim(input('keyname',''));
        if(!empty($keyname))
        {
            $map[] = ['name','like','%'.$keyname.'%'];
        }
        $list = Role::where($map)->order('sort,id asc')->page($this->page,$this->size)->select();
        if(request()->isPost())
        {
            echo json_encode(['data'=>$list]);exit;
        }
        return view('role', ['list' => $list]);
    }

    public function role_edit($id=null)
    {
        if(intval($id)==1)halt('不可进入');
        $roleModel = new Role();
        if(!empty($id))
        {
            $action = $roleModel->find($id);
        }else{
            $action = $roleModel;
        }
        if(request()->isPost())
        {
            $post = request()->post();
            // 验证
            try{
                $this->validate($post, 'Role');
            }catch (ValidateException $e)
            {
                $this->error($e->getMessage());
            }
            $post['status'] = isset($post['status']) && $post['status'] == 'on'?1:0;
            $action->save($post);
            $this->success('操作成功',url('/power/role'));
        }
        return view('role_edit', ['info' => $action]);
    }

    public function role_del()
    {
        $info = Role::destroy(intval(input('id')));
        if(!($info))$this->error('操作失败');
        $this->success('操作成功',url('/power/role'));
    }

    public function fieldchange()
    {
        $id = input('id',0);
        if(intval($id)==1)halt('不可进入');
        $field = input('field','');
        $ifind = User::find($id);
        if(!empty($ifind))
        {
            if($ifind[$field] == 0)
            {
                User::where(['id'=>$id])->update([$field=>1]);
            }
            if($ifind[$field] == 1)
            {
                User::where(['id'=>$id])->update([$field=>0]);
            }
        }
        echo json_encode(1);exit;
    }

    public function role_fieldchange()
    {
        $id = input('id',0);
        $field = input('field','');
        $ifind = Role::find($id);
        if(!empty($ifind))
        {
            if($ifind[$field] == 0)
            {
                Role::where(['id'=>$id])->update([$field=>1]);
            }
            if($ifind[$field] == 1)
            {
                Role::where(['id'=>$id])->update([$field=>0]);
            }
        }
        echo json_encode(1);exit;
    }



    # 角色权限菜单编辑
    public function role_menu_set($id = null)
    {
        $info = Role::find($id);
        // 配置分组信息
        $list_group = Menu::getGroup();
        foreach ($list_group as $key => $value) {
            $tab_list[$key]['title'] = $value;
            $tab_list[$key]['url']  = url('index', ['group' => $key]);
        }
        // 获取节点数据
        $data_list = Menu::getMenusByGroup('admin');
        $max_level = $this->request->get('max', 0);
        return view('role_menu_set',['info'=>$info,'menus'=>$this->getNestMenu($data_list, $max_level)]);
    }

    # 角色权限菜单编辑
    public function role_menu_set_edit($id = null,$pid = null)
    {
        $menuModel = new Menu();
        if(!empty($id))
        {
            $action = $menuModel->find($id);
        }else{
            $action = $menuModel;
        }
        if(request()->isPost())
        {
            $post = request()->post();

            // 验证
            try{
                $this->validate($post, 'Menu');
            }catch (ValidateException $e)
            {
                $this->error($e->getMessage());
            }
            $post['status'] = isset($post['status']) && $post['status'] == 'on'?1:0;
            $post['system_menu'] = 1;
            $post['url_type'] = 'module_admin';
            $action->save($post);
            $this->success('操作成功',url('/power/role_menu_set',array('id'=>$id)));
        }
        $menu = Menu::getMenuTree(0, '', 'admin');
        return view('role_menu_set_edit',['info'=>$action,'menuj'=>$menu]);
    }

    # 保存节点
    public function save_menu()
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            if (!empty($data)) {
                $menus = $this->parseMenu($data['menus']);
                foreach ($menus as $menu) {
                    if ($menu['pid'] == 0) {
                        continue;
                    }
                    Menu::update($menu);
                }
                $this->success('操作成功',url('/power/role_menu_set'));
            } else {
                $this->error('操作失败');
            }
        }
    }

    # 删除节点
    public function role_menu_set_del()
    {
        $info = Menu::destroy(intval(input('id')));
        if(!($info))$this->error('操作失败');
        $this->success('操作成功',url('/power/role_menu_set'));
    }

    # 权限分配
    public function role_set($id=null)
    {
        $info = Role::find($id);
        $modules = Module::where('status','=', 1)->column('title','name');
        $map     = [];
        // 当前用户能分配的所有菜单
        $menus = Menu::where('module', 'in', array_keys($modules))
            ->where($map)
            ->order('module,sort,id')
            ->column('id,pid,sort,url_value,title,icon,module');
        // 按模块分组菜单
        $moduleMenus = [];
        foreach ($menus as $key => $menu) {
            if (!isset($moduleMenus[$menu['module']])) {
                $moduleMenus[$menu['module']] = [
                    'title' => isset($modules[$menu['module']]) ? $modules[$menu['module']] : '未知',
                    'menus' => [$menu]
                ];
            } else {
                $moduleMenus[$menu['module']]['menus'][] = $menu;
            }
        }

        // 层级化每个模块的菜单
        foreach ($moduleMenus as $key => $module) {
            $menu = Tree::toLayer($module['menus']);
            $moduleMenus[$key]['menus'] = $this->buildJsTree($menu, $info);
        }
//        halt($moduleMenus);
        return view('role_set',['menuj'=>$moduleMenus,'curr_tab'=>current(array_keys($moduleMenus))]);
    }

    # 角色分配权限保存
    public function role_set_edit()
    {
        if ($this->request->isPost()) {
            $post = $this->request->post();
            if (!isset($post['menu_auth'])) {
                $post['menu_auth'] = [];
            } else {
                $post['menu_auth'] = explode(',', $post['menu_auth']);
            }
            // 非超级管理员检查可添加的节点权限
            if (session('user_auth.role') != 1) {
                $menu_auth = Role::where('id','=', session('user_auth.role'))->value('menu_auth');
                $menu_auth = json_decode($menu_auth, true);
                $menu_auth = array_intersect($menu_auth, $post['menu_auth']);
                $post['menu_auth'] = $menu_auth;
            }
            if (Role::update($post)) {
                // 更新成功，循环处理子角色权限
                Role::resetAuth($post['id'], $post['menu_auth']);
                $this->role_auth();
                $this->success('操作成功',url('/power/role_menu_set'));
            } else {
                $this->error('操作失败');
            }
        }
    }


    # 参考别的框架做法
    private function getNestMenu($lists = [], $max_level = 0, $pid = 0, $curr_level = 1)
    {
        $result = '';
        foreach ($lists as $key => $value) {
            if ($value['pid'] == $pid) {
                $disable  = $value['status'] == 0 ? 'dd-disable' : '';
                // 组合节点
                $result .= '<li class="dd-item dd3-item '.$disable.'" data-id="'.$value['id'].'">';
                $result .= '<div class="dd-handle dd3-handle"> </div><div class="dd3-content"><i class="'.$value['icon'].'"></i> '.$value['title'];
                if ($value['url_value'] != '') {
                    $result .= '<span class="link"><i class="fa fa-link"></i> '.$value['url_value'].'</span>';
                }
                $result .= '<span class="action">';
                $result .= '<a href="'.url('/power/role_menu_set_edit', ['module' => $value['module'], 'pid' => $value['id']]).'" data-toggle="tooltip" data-original-title="新增子节点"><i class="list-icon fa fa-plus fa-fw"></i></a><a href="'.url('/power/role_menu_set_edit', ['id' => $value['id'],'pid'=>$value['pid']]).'" data-toggle="tooltip" data-original-title="编辑"><i class="list-icon fa fa-pencil fa-fw"></i></a>';
//                if ($value['status'] == 0) {
//                    // 启用
//                    $result .= '<a href="javascript:void(0);" data-ids="'.$value['id'].'" class="enable" data-toggle="tooltip" data-original-title="启用"><i class="list-icon fa fa-check-circle-o fa-fw"></i></a>';
//                } else {
//                    // 禁用
//                    $result .= '<a href="javascript:void(0);" data-ids="'.$value['id'].'" class="disable" data-toggle="tooltip" data-original-title="禁用"><i class="list-icon fa fa-ban fa-fw"></i></a>';
//                }
                $result .= '<a onclick="del_menu('.$value['id'].',this)" href="javascript:void(0)" data-toggle="tooltip" data-original-title="删除" class="ajax-get confirm"><i class="list-icon fa fa-times fa-fw"></i></a></div>';
                $result .= '</span>';

                if ($max_level == 0 || $curr_level != $max_level) {
                    unset($lists[$key]);
                    // 下级节点
                    $children = $this->getNestMenu($lists, $max_level, $value['id'], $curr_level + 1);
                    if ($children != '') {
                        $result .= '<ol class="dd-list">'.$children.'</ol>';
                    }
                }

                $result .= '</li>';
            }
        }
        return $result;
    }

    private function parseMenu($menus = [], $pid = 0)
    {
        $sort   = 1;
        $result = [];
        foreach ($menus as $menu) {
            $result[] = [
                'id'   => (int)$menu['id'],
                'pid'  => (int)$pid,
                'sort' => $sort,
            ];
            if (isset($menu['children'])) {
                $result = array_merge($result, $this->parseMenu($menu['children'], $menu['id']));
            }
            $sort ++;
        }
        return $result;
    }

    private function buildJsTree($menus = [], $user = [])
    {
        $result = '';
        if (!empty($menus)) {
            $option = [
                'opened'   => true,
                'selected' => false,
                'icon'     => '',
            ];
            foreach ($menus as $menu) {
                $option['icon'] = $menu['icon'];
                if (isset($user['menu_auth'])) {
                    $option['selected'] = in_array($menu['id'], $user['menu_auth']) ? true : false;
                }
                if (isset($menu['child'])) {
                    $result .= '<li id="'.$menu['id'].'" data-jstree=\''.json_encode($option).'\'>'.$menu['title'].($menu['url_value'] == '' ? '' : ' ('.$menu['url_value'].')').$this->buildJsTree($menu['child'], $user).'</li>';
                } else {
                    $result .= '<li id="'.$menu['id'].'" data-jstree=\''.json_encode($option).'\'>'.$menu['title'].($menu['url_value'] == '' ? '' : ' ('.$menu['url_value'].')').'</li>';
                }
            }
        }

        return '<ul>'.$result.'</ul>';
    }
}