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
use think\facade\Config;
use think\facade\Db;
use think\facade\View;
use think\facade\cache;
use util\Sql;
class AddonsManage extends AdminController
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
                $PluginModel = new \app\admin\model\AddonsManage();
                $result = $PluginModel->getAll($keyword, $status);
                if ($result['plugins'] === false) {
                    $this->error($PluginModel->getError());
                }
                View::assign('list', $result['plugins']);
                View::assign('total', $result['total']);
                return View::fetch();
                break;
            case 'online':
                break;
        }
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
        // 实例化插件
        $plugin = new $plugin_class;
        // 插件预安装
        if(!$plugin->install()) {
            $this->error ('插件预安装失败!原因：'. $plugin->getError());
        }
        // 执行安装插件sql文件
        $sql_file = realpath(Config::get('app.addons_path').$name.'/install.sql');
        if (file_exists($sql_file)) {
            if (isset($plugin->database_prefix) && $plugin->database_prefix != '') {
                $sql_statement = Sql::getSqlFromFile($sql_file, false, [$plugin->database_prefix => Config::get('app.dbprefix')]);
            } else {
                $sql_statement = Sql::getSqlFromFile($sql_file);
            }
            if (!empty($sql_statement)) {
                foreach ($sql_statement as $value) {
                    Db::execute($value);
                }
            }
        }
        $plugin_info = $plugin->info;
        $plugin_info['status'] = 1;
        $info = \app\admin\model\AddonsManage::where(array('identifier'=>$plugin_info['identifier']))->find();
        if(!$info)
        {
            // 将插件信息写入数据库
            if (\app\admin\model\AddonsManage::create($plugin_info)) {
                header('location:/addonsManage/index');
                exit;
            } else {
                header('location:/addonsManage/index');
                exit;
            }
        }
        header('location:/addonsManage/index');
        exit;
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
        // 实例化插件
        $plugin = new $class;
        if(!$plugin->uninstall()) {
            $this->error ('插件预卸载失败!原因：'. $plugin->getError());
        }
        // 执行卸载插件sql文件
        $sql_file = realpath(Config::get('app.addons_path').$plug_name.'/uninstall.sql');
        if (file_exists($sql_file)) {
            if (isset($plugin->database_prefix) && $plugin->database_prefix != '') {
                $sql_statement = Sql::getSqlFromFile($sql_file, true, [$plugin->database_prefix => Config::get('app.dbprefix')]);
            } else {
                $sql_statement = Sql::getSqlFromFile($sql_file, true);
            }
            if (!empty($sql_statement)) {
                Db::execute($sql_statement);
            }
        }
        $plugin_info = $plugin->info;
        $info = \app\admin\model\AddonsManage::where(array('identifier'=>$plugin_info['identifier']))->find();
        if($info)
        {
            // 删除插件信息
            if (\app\admin\model\AddonsManage::where('name', $plug_name)->delete()) {
                header('location:/addonsManage/index');
                exit;
            } else {
                header('location:/addonsManage/index');
                exit;
            }
        }
        header('location:/addonsManage/index');
        exit;
    }
}