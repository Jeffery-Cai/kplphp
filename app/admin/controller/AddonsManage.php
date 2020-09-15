<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 插件管理功能
----------------------------------------------------------------*/
namespace app\admin\controller;
use app\AdminController;
use app\admin\model\Addonsmanage as AddonsmanageModel;
use think\App;
use think\facade\Config;
use think\facade\Db;
use think\facade\View;
use util\Sql;
class Addonsmanage extends AdminController
{
    public function index($group = 'local')
    {
        switch ($group) {
            case 'local':
                $keyword = $this->request->get('keyword', '');
                if (input('?param.status') && input('param.status') != '_all') {
                    $status = input('param.status');
                } else {
                    $status  = '';
                }
                $result = AddonsmanageModel::getAll($keyword, $status);
                if ($result['plugins'] === false) {
                    $this->error(AddonsmanageModel::getError());
                }
                View::assign('list', $result['plugins']);
                View::assign('total', $result['total']);
                return View::fetch();
                break;
            case 'online':
                break;
        }
    }

    # 查看详细信息
    public function checkdes($title,$name)
    {
        return json(['title'=>$title,'des'=>@file_get_contents(root_path().'public/addons/'.$name.'/des.html')]);
    }

    # add
    public function add()
    {
        halt('正在测试中....');
    }

    # 安装
    public function install($name = '')
    {
        ini_set('max_execution_time', '0');
        ini_set('memory_limit', '1024M');
        $plug_name = trim($name);
        if ($plug_name == '') $this->error('插件不存在！');
        $plugin_class = $this->get_plugin_class($plug_name);
        if (!class_exists($plugin_class)) {
            $this->error ('插件不存在！');
        }
        # 实例化插件
        $plugin = new $plugin_class;
        # 插件预安装
        if(!$plugin->install()) {
            $this->error ('插件预安装失败!原因：'. $plugin->getError());
        }
        $plugin_info = $plugin->info;
        $info = AddonsManageModel::where(['identifier'=>$plugin_info['identifier']])->find();
        if(empty($info))
        {
            $sql_file = realpath(Config::get('addons.addons_path').$name.'/install.sql');
            if (file_exists($sql_file)) {
                if (isset($plugin->database_prefix) && $plugin->database_prefix != '') {
                    $sql_statement = Sql::getSqlFromFile($sql_file, false, [$plugin->database_prefix => Config::get('database.connections.mysql.prefix')]);
                } else {
                    $sql_statement = Sql::getSqlFromFile($sql_file);
                }
                if (!empty($sql_statement)) {
                    # 用sql语句写入方式
                    foreach ($sql_statement as $value) {
                        Db::execute($value);
                    }
                }
            }
            # 写入插件式
            $plugin_info['status'] = 1;
            if (AddonsManageModel::create($plugin_info)) {
                $this->addonscopy($name);
                $this->success('操作成功',url('/addonsmanage/index'));
            } else {
                $this->error('操作失败');
            }
        }else{
            $this->error('已存在该插件，如想重新安装请卸载！');
        }
    }

    # 卸载
    public function uninstall($name = '')
    {
        $plug_name = trim($name);
        if ($plug_name == '') $this->error ('插件不存在！');
        $class = $this->get_plugin_class($plug_name);
        if (!class_exists($class)) {
            $this->error ('插件不存在！');
        }
        # 实例化插件
        $plugin = new $class();
        if(!$plugin->uninstall()) {
            $this->error ('插件预卸载失败!原因：'. $plugin->getError());
        }
        $plugin_info = $plugin->info;
        $info = AddonsManageModel::where(['identifier'=>$plugin_info['identifier']])->find();
        if($info)
        {
            # 执行卸载插件sql文件
            $sql_file = realpath(Config::get('addons.addons_path').$plug_name.'/uninstall.sql');
            if (file_exists($sql_file)) {
                if (isset($plugin->database_prefix) && $plugin->database_prefix != '') {
                    $sql_statement = Sql::getSqlFromFile($sql_file, true, [$plugin->database_prefix => Config::get('database.connections.mysql.prefix')]);
                } else {
                    $sql_statement = Sql::getSqlFromFile($sql_file, true);
                }
                if (!empty($sql_statement)) {
                    Db::execute($sql_statement);
                }
            }
            $this->addonsdel($name);
            AddonsManageModel::where(['identifier'=>$plugin_info['identifier']])->delete();
            $this->success('操作成功',url('/addonsmanage/index'));
        }else{
            $this->error('不存在该插件');
        }
    }

    # 复制目录
    public function addonscopy($name="")
    {
        if(!$name)return;
        $srcdir = root_path().'addons/'.$name.'/static';
        $dstdir = root_path().'public/addons/'.$name;
        copydir($srcdir,$dstdir);
    }

    # 删除目录
    public function addonsdel($name="")
    {
        if(!$name)return;
        $dstdir = root_path().'public/addons/'.$name;
        delDirAndFile($dstdir);
    }

}
// $srcdir 源目录  $dstdir 目标目录
function copydir($srcdir,$dstdir){
    if(!file_exists($dstdir)){
        @mkdir($dstdir);
    }
    $files=scandir($srcdir);
    foreach ($files as $file) {
        if($file!='.' && $file!='..'){
            $srcf=$srcdir.'/'.$file;
            $dstf=$dstdir.'/'.$file;
            if(is_dir($srcf)){
                copydir($srcf,$dstf);
            }
            else{
                @copy($srcf,$dstf);
            }
        }
    }
}

function delDirAndFile($path, $delDir = true)
{
    if (is_array($path)) {
        foreach ($path as $subPath) {
            delDirAndFile($subPath, $delDir);
        }
    }
    if (is_dir($path)) {
        $handle = @opendir($path);
        if ($handle) {
            while (false !== ($item = readdir($handle))) {
                if ($item != "." && $item != "..") {
                    @is_dir("$path/$item") ? delDirAndFile("$path/$item", $delDir) : unlink("$path/$item");
                }
            }
            @closedir($handle);
            if ($delDir) {
                return @rmdir($path);
            }
        }
    } else {
        if (@file_exists($path)) {
            return @unlink($path);
        } else {
            return false;
        }
    }
}