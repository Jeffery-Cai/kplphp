<?php
declare (strict_types = 1);
namespace addons;
use app\AdminController;
use think\App;

/**
 * 控制器基础类
 */
class BaseController extends AdminController
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
     * @param App $app 应用对象
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;
        // 控制器初始化
        $this->initialize();
    }

    # 初始化
    protected function initialize()
    {
        parent::initialize();
    }
}