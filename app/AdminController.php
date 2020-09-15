<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 后台记得继承于此类，此类可以装逼功能
----------------------------------------------------------------*/
declare (strict_types = 1);
namespace app;
use app\admin\logic\User;
use app\admin\model\Menu;
use app\admin\model\Role;
use app\admin\model\User as UserModel;
use app\common\controller\Kbuilder;
use app\common\model\Csvceshi;
use think\App;
use think\facade\Cookie;
use \think\facade\Db;
use think\exception\ValidateException;
use think\facade\Config;
use think\facade\View;
use think\Validate;

/**
 * 控制器基础类
 */
abstract class AdminController
{
    use \liliuwei\think\Jump;
    protected $request;
    protected $app;
    protected $page;
    protected $size;
    protected $batchValidate = false;
    protected $middleware = [];
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;
        // 控制器初始化
        $this->initialize();
    }

    # 初始化
    protected function initialize()
    {
        // 判断是否登录 || 去掉login控制器
        $iarray = array('Login');
        $uid = $this->is_signin();
        if(!in_array(request()->controller(),$iarray))
        {
            if ($uid<=0 || empty($uid)) {
                header('location:/admin.php/login/index');exit;
//                $this->success('请登录','/admin.php/login/index');  # 提示[你们想改都行]
            }
        }
        $this->role_auth();
        if($uid>0)
        {
            if(!in_array(request()->controller(),$iarray))
            {
                defined('UID') or define('UID',$uid);
                session('role_menu_auth', Role::roleAuth());
                if (!Role::checkAuth()) $this->error('权限不足！');
                View::assign('_location', Menu::getLocation('', true));
                View::assign('sidebar', Menu::getSidebarMenu());
                View::assign('top_menus', Menu::getTopMenu());
            }
        }
        $this->page = input('page',1);
        $this->size = input('size',5);

        $kplphp = Config::get('kplphp');
        View::assign('kplphp',$kplphp);
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                list($validate, $scene) = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }
        return $v->failException(true)->check($data);
    }

    /**
     * 获取插件名称
     * @param $name 插件名称
     * @return string
     */
    public function get_plugin_class($name)
    {
        return "addons\\{$name}\\plugin";
    }

    public function is_signin()
    {
        $user = session('user_auth');
        if (empty($user)) {
            if (cookie('?uid') && cookie('?signin_token')) {
                $UserModel = new UserModel();
                $user = $UserModel::find(cookie('uid'));
                if ($user) {
                    $signin_token = $this->data_auth_sign($user['username'].$user['id'].config('app.key'));
                    if (cookie('signin_token') == $signin_token) {
                        $haltU = User::autoLogin($user);
                        if($haltU[0]!=1)
                        {
                            $this->error($haltU[1]);
                        }
                        return $user['id'];
                    }
                }
            };
            return 0;
        }else{
            return session('user_auth_sign') == $this->data_auth_sign($user) ? $user['uid'] : 0;
        }
    }

    /**
     * @param null $id == 查看的ID值
     * @return mixed
     */
    public function see($id=null)
    {
        if (!$id) $this->error('参数错误');
        # 重新定义查看
        return Kbuilder::sets('see')
            ->setTable(Cookie::get('table'))
            ->getInfo($id)
            ->view();
    }

    public function add()
    {
        $table = '\app\admin\model\\' .Cookie::get('table');
        $model = new $table();
        if(!empty($id))
        {
            $action = $model->find($id);
        }else{
            $action = $model;
        }
        if(request()->isPost())
        {
            $data = request()->post();
            $file = request()->file();
            # 处理上传的文件
            $filek = [];
            $filev = [];
            foreach ($file as $k => $v)
            {
                $filek[] = $k;
                if(!is_array($v))
                {
                    # 单图片提交
                    $filev[] = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $v, 'md5');
                }else{
                    $filesname = [];
                    foreach ($v as $k1 => $v1)
                    {
                        $filesname[] = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $v1, 'md5');
                    }
                    $filev[] = implode(',',$filesname);
                }
            }
            foreach ($filek as $k => $v)
            {
                $data[$v] = $filev[$k];
            }
            $data['status'] = isset($data['status']) && $data['status'] == 'on'?1:0;
            $status = $action->save($data);
            if($status)
            {
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }else{
            # 重新定义查看
            return Kbuilder::sets('form')
                ->setTable(Cookie::get('table'))
                ->getInfo()
                ->view();
        }
    }

    public function edit($id=null)
    {
        if (!$id) $this->error('参数错误');
        $table = '\app\admin\model\\' .Cookie::get('table');
        $model = new $table();
        if(!empty($id))
        {
            $action = $model->find($id);
        }else{
            $action = $model;
        }
        if(request()->isPost())
        {
            $data = request()->post();
            $file = request()->file();
            # 处理上传的文件
            $filek = [];
            $filev = [];
            foreach ($file as $k => $v)
            {
                $filek[] = $k;
                if(!is_array($v))
                {
                    # 单图片提交
                    $filev[] = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $v, 'md5');
                }else{
                    $filesname = [];
                    foreach ($v as $k1 => $v1)
                    {
                        $filesname[] = \think\facade\Filesystem::disk('public')->putFile( 'uploads', $v1, 'md5');
                    }
                    $filev[] = implode(',',$filesname);
                }
            }
            foreach ($filek as $k => $v)
            {
                $data[$v] = $filev[$k];
            }
            $data['status'] = isset($data['status']) && $data['status'] == 'on'?1:0;
            $status = $action->save($data);
            if($status)
            {
                $this->success('操作成功');
            }else{
                $this->error('操作失败');
            }
        }else{
            # 重新定义查看
            return Kbuilder::sets('form')
                ->setTable(Cookie::get('table'))
                ->getInfo($id)
                ->view();
        }
    }

    public function del()
    {

        $table = '\app\admin\model\\' .Cookie::get('table');
        $action = new $table();
        $ids = input('id','');
        if($ids)
        {
            $action->where([['id','in',$ids]])->delete();
            $this->success('操作成功');
        }
        $this->error('操作失败');
    }


    /**
     * 数据签名认证
     * @param array $data 被认证的数据
     */
    public function data_auth_sign($data = [])
    {
        // 数据类型检测
        if(!is_array($data)){
            $data = (array)$data;
        }
        // 排序
        ksort($data);
        // url编码并生成query字符串
        $code = http_build_query($data);
        // 生成签名
        $sign = sha1($code);
        return $sign;
    }

    public function role_auth() {
        session('role_menu_auth', Role::roleAuth());
    }
}