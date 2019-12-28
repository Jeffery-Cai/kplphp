<?php
declare (strict_types = 1);

namespace app;

use app\admin\logic\User;
use app\admin\model\Menu;
use app\admin\model\Role;
use app\admin\model\User as UserModel;
use app\admin\user\validate\Role as RoleModel;
use think\App;
use think\exception\ValidateException;
use think\facade\View;
use think\Validate;


/**
 * 控制器基础类
 */
abstract class AdminController
{
    use \liliuwei\think\Jump;

    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;
    protected $page;
    protected $size;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
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
                $this->success('请登录','/admin.php/login/index');
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
            }
        }
        $this->page = input('page',1);
        $this->size = input('size',5);
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

    public function get_plugin_class($name)
    {
        return "addons\\{$name}\\plugin";
    }

    # 判断是否登录
    public function is_signin()
    {
        $user = session('user_auth');
//        halt($user);
        if (empty($user)) {
            // 判断是否记住登录
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