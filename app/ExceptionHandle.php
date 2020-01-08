<?php
namespace app;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /*
    public function read_dirs($path) {
        // 判断path是否存在
        if(!file_exists($path)){
            halt([ 'code'=>'1001','msg' =>'path is not exits!']);
            // 判断path是否为目录
        }elseif (!is_dir($path)){
            halt(['code' => '1002','msg'  => 'is not dir' ]);
        }else{
            $dir_handle = opendir($path);
            $Mdir=array();
            while(false !== $file=readdir($dir_handle)) {
                if ($file=='.' || $file=='..') continue;
                //输出该文件
                $Mdir[]=strtolower(explode('.',$file)[0]);
                // 判断当前file是否为目录
                if(is_dir($path . '/' . $file)) {
                    // file为目录时进行递归遍历
                    read_dirs($path . '/' . $file);
                }
            }
            closedir($dir_handle);
            return $Mdir;
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response

    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制
        $Mdir= $this->read_dirs('../app/index/controller');
        if (in_array(strtolower($request->controller()),$Mdir)){

            return parent::render($request, $e);
        }else{
            return redirect('/');
        }
    }
    */
    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制

        // 其他错误交给系统处理
        return parent::render($request, $e);
    }
}
