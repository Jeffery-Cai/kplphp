<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 插件模型功能
----------------------------------------------------------------*/
namespace app\admin\model;
use think\Model;
use think\facade\Config;

class Addonsmanage extends Model
{
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;
    protected $name = 'addons_manage';

    public static function getAll($keyword = '', $status = '')
    {
        $result = cache('plugin_all');
        if (!$result) {
            // 获取插件目录下的所有插件目录
            $dirs = array_map('basename', glob(Config::get('addons.addons_path').'*', GLOB_ONLYDIR));
            if ($dirs === false || !file_exists(Config::get('addons.addons_path'))) {
                return '插件目录不可读或者不存在';
            }
            // 读取数据库插件表
            $plugins = self::order('id asc')->column('*','name');
            // 读取未安装的插件
            foreach ($dirs as $plugin) {
                if (!isset($plugins[$plugin])) {
                    $plugins[$plugin]['name'] = $plugin;

                    // 获取插件类名
                    $class = get_plugin_class($plugin);
                    // 插件类不存在则跳过实例化
                    if (!class_exists($class)) {
                        // 插件的入口文件不存在！
                        $plugins[$plugin]['status'] = '-2';
                        continue;
                    }

                    // 实例化插件
                    $obj = new $class;
                    // 插件插件信息缺失
                    if (!isset($obj->info) || empty($obj->info)) {
                        // 插件信息缺失！
                        $plugins[$plugin]['status'] = '-3';
                        continue;
                    }

                    // 插件插件信息不完整
                    if (!self::checkInfo($obj->info)) {
                        $plugins[$plugin]['status'] = '-4';
                        continue;
                    }

                    // 插件未安装
                    $plugins[$plugin] = $obj->info;
                    $plugins[$plugin]['status'] = '-1';

                }
            }
            // 数量统计
            $total = [
                'all' => count($plugins), // 所有插件数量
                '-2'  => 0,               // 错误插件数量
                '-1'  => 0,               // 未安装数量
                '0'   => 0,               // 未启用数量
                '1'   => 0,               // 已启用数量
            ];
//            $plugins = $this->order('sort asc,id desc')->select();
            // 过滤查询结果和统计数量
            foreach ($plugins as $key => $value) {
                // 统计数量
                if (in_array($value['status'], ['-2', '-3', '-4'])) {
                    // 已损坏数量
                    $total['-2']++;
                } else {
                    $total[(string)$value['status']]++;
                }

                // 过滤查询
                if ($status != '') {
                    if ($status == '-2') {
                        // 过滤掉非已损坏的插件
                        if (!in_array($value['status'], ['-2', '-3', '-4'])) {
                            unset($plugins[$key]);
                            continue;
                        }
                    } else if ($value['status'] != $status) {
                        unset($plugins[$key]);
                        continue;
                    }
                }
                if ($keyword != '') {
                    if (stristr($value['name'], $keyword) === false && (!isset($value['title']) || stristr($value['title'], $keyword) === false) && (!isset($value['author']) || stristr($value['author'], $keyword) === false)) {
                        unset($plugins[$key]);
                        continue;
                    }
                }
            }
            // 处理状态及插件按钮
            foreach ($plugins as &$plugin) {
                switch ($plugin['status']) {
                    case '-4': // 插件信息不完整
                        $plugin['title'] = '插件信息不完整';
                        $plugin['bg_color'] = 'danger';
                        $plugin['status_class'] = 'text-danger';
                        $plugin['status_info'] = '<i class="fa fa-times"></i> 已损坏';
                        $plugin['actions'] = '<button class="btn btn-sm btn-noborder btn-danger" type="button" disabled>不可操作</button>';
                        break;
                    case '-3': // 插件信息缺失
                        $plugin['title'] = '插件信息缺失';
                        $plugin['bg_color'] = 'danger';
                        $plugin['status_class'] = 'text-danger';
                        $plugin['status_info'] = '<i class="fa fa-times"></i> 已损坏';
                        $plugin['actions'] = '<button class="btn btn-sm btn-noborder btn-danger" type="button" disabled>不可操作</button>';
                        break;
                    case '-2': // 入口文件不存在
                        $plugin['title'] = '入口文件不存在';
                        $plugin['bg_color'] = 'danger';
                        $plugin['status_class'] = 'text-danger';
                        $plugin['status_info'] = '<i class="fa fa-times"></i> 已损坏';
                        $plugin['actions'] = '<button class="btn btn-sm btn-noborder btn-danger" type="button" disabled>不可操作</button>';
                        break;
                    case '-1': // 未安装
                        $plugin['bg_color'] = 'info';
                        $plugin['actions'] = '<a class="btn btn-sm btn-noborder btn-success ajax-get confirm" href="'.url('install', ['name' => $plugin['name']]).'">安装</a>';
                        $plugin['status_class'] = 'text-info';
                        $plugin['status_info'] = '<i class="fa fa-fw fa-th-large"></i> 未安装';
                        break;
                    case '0': // 禁用
                        $plugin['bg_color'] = 'warning';
                        $plugin['actions'] = '<a class="btn btn-sm btn-noborder btn-success ajax-get confirm" href="'.url('enable', ['ids' => $plugin['id']]).'">启用</a> ';
                        $plugin['actions'] .= '<a class="btn btn-sm btn-noborder btn-danger ajax-get confirm" data-tips="如果包括数据库，将同时删除数据库！" href="'.url('uninstall', ['name' => $plugin['name']]).'">卸载</a> ';
                        if (isset($plugin['config']) && $plugin['config'] != '') {
                            $plugin['actions'] .= '<a class="btn btn-sm btn-noborder btn-info" href="'.url('config', ['name' => $plugin['name']]).'">设置</a> ';
                        }
                        if ($plugin['admin'] != '0') {
                            $plugin['actions'] .= '<a class="btn btn-sm btn-noborder btn-primary" href="'.url('manage', ['name' => $plugin['name']]).'">管理</a> ';
                        }
                        $plugin['status_class'] = 'text-warning';
                        $plugin['status_info'] = '<i class="fa fa-ban"></i> 已禁用';
                        break;
                    case '1': // 启用
                        $plugin['bg_color'] = 'success';
                        $plugin['actions'] = '<a class="btn btn-sm btn-noborder btn-warning ajax-get confirm" href="'.url('disable', ['ids' => $plugin['id']]).'">禁用</a> ';
                        $plugin['actions'] .= '<a class="btn btn-sm btn-noborder btn-danger ajax-get confirm" data-tips="如果包括数据库，将同时删除数据库！" href="'.url('uninstall', ['name' => $plugin['name']]).'">卸载</a> ';
                        if (isset($plugin['config']) && $plugin['config'] != '') {
                            $plugin['actions'] .= '<a class="btn btn-sm btn-noborder btn-info" href="'.url('config', ['name' => $plugin['name']]).'">设置</a> ';
                        }
                        if ($plugin['admin'] != '0') {
                            $plugin['actions'] .= '<a class="btn btn-sm btn-noborder btn-primary" href="'.url('manage', ['name' => $plugin['name']]).'">管理</a> ';
                        }
                        $plugin['status_class'] = 'text-success';
                        $plugin['status_info'] = '<i class="fa fa-check"></i> 已启用';
                        break;
                    default: // 未知
                        $plugin['title'] = '未知';
                        break;
                }
            }

            $result = ['total' => $total, 'plugins' => $plugins];

        }
        return $result;
    }

    // 检查信息
    private static function checkInfo($info = '')
    {
        $default_item = ['name','title','author','version'];
        foreach ($default_item as $item) {
            if (!isset($info[$item]) || $info[$item] == '') {
                return false;
            }
        }
        return true;
    }
}

function get_plugin_class($name)
{
    return "addons\\{$name}\\plugin";
}