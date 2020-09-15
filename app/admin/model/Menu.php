<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 菜单模型功能
----------------------------------------------------------------*/
namespace app\admin\model;

use app\admin\model\Role as RoleModel;
use liliuwei\think\Jump;
use think\Model;
use think\Exception;
use util\Tree;

/**
 * 节点模型
 * @package app\admin\model
 */
class Menu extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_menu';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 将节点url转为小写
    public function setUrlValueAttr($value)
    {
        return strtolower(trim($value));
    }

    /**
     * 递归修改所属模型
     * @param int $id 父级节点id
     * @param string $module 模型名称

     * @return bool
     */
    public static function changeModule($id = 0, $module = '')
    {
        if ($id > 0) {
            $ids = self::where('pid', $id)->column('id');
            if ($ids) {
                foreach ($ids as $id) {
                    self::where('id', $id)->setField('module', $module);
                    self::changeModule($id, $module);
                }
            }
        }
        return true;
    }

    /**
     * 获取树形节点
     * @param int $id 需要隐藏的节点id
     * @param string $default 默认第一个节点项，默认为“顶级节点”，如果为false则不显示，也可传入其他名称
     * @param string $module 模型名

     * @return mixed
     */
    public static function getMenuTree($id = 0, $default = '', $module = '')
    {
        $result[0] = '顶级节点';
        $where = [
            ['status', '>=', 0]
        ];
        if ($module != '') {
            $where[] = ['module', '=', $module];
        }

        // 排除指定节点及其子节点
        if ($id !== 0) {
            $hide_ids = array_merge([$id], self::getChildsId($id));
            $where[]  = ['id', 'not in', $hide_ids];
        }

        // 获取节点
        $menus = Tree::toList(self::where($where)->order('pid,id')->column('id,pid,title'));
        foreach ($menus as $menu) {
            $result[$menu['id']] = $menu['title_display'];
        }

        // 设置默认节点项标题
        if ($default != '') {
            $result[0] = $default;
        }

        // 隐藏默认节点项
        if ($default === false) {
            unset($result[0]);
        }

        return $result;
    }

    /**
     * 获取顶部节点
     * @param string $max 最多返回多少个
     * @param string $cache_tag 缓存标签

     * @return array
     */
    public static function getTopMenu($max = '', $cache_tag = '')
    {
        $cache_tag .= '_role_'.session('user_auth.role');
        $menus = cache($cache_tag);
        if (!$menus) {
            $map['status'] = 1;
            $map['pid']    = 0;
            $list_menu     = self::where($map)->order('sort,id')->column('id,pid,module,title,url_value,url_type,url_target,icon,params');
            $i             = 0;
            $menus         = [];
            foreach ($list_menu as $key => &$menu) {
                if ($max != '' && $i >= $max) {
                    break;
                }
                // 没有访问权限的节点不显示
                if (!RoleModel::checkAuth($menu['id'])) {
                    continue;
                }
                if ($menu['url_value'] != '' && ($menu['url_type'] == 'module_admin' || $menu['url_type'] == 'module_home')) {
                    $url = explode('/', $menu['url_value']);
                    $menu['controller'] = $url[1];
                    $menu['action']     = $url[2];
                }
                $menus[$key] = $menu;
                $i++;
            }
        }
        return $menus;
    }

    /**
     * 获取侧栏节点
     * @param string $id 模块id
     * @param string $module 模块名
     * @param string $controller 控制器名

     * @return array|mixed
     */
    public static function getSidebarMenu($id = '', $module = 'admin', $controller = '')
    {
        $module     = $module == '' ? request()->module() : $module;
        $controller = $controller == '' ? request()->controller() : $controller;
        $cache_tag  = strtolower('_sidebar_menus_' . $module . '_' . $controller).'_role_'.session('user_auth.role');
        $menus      = cache($cache_tag);
        if (!$menus) {
            // 获取当前节点地址
            $location = self::getLocation($id);
            // 当前顶级节点id
            $top_id = $location[0]['id'];
            // 获取顶级节点下的所有节点
            $map = [
                'status' => 1
            ];


            $menus = self::where($map)->order('sort,id')->column('id,controller,action,pid,module,title,url_value,url_type,url_target,icon,params');
            // 解析模块链接
            foreach ($menus as $key => &$menu) {
                // 没有访问权限的节点不显示
                if (!RoleModel::checkAuth($menu['id'])) {
                    unset($menus[$key]);
                    continue;
                }
            }
            $menus = Tree::toLayer($menus, $top_id, 2);
        }
        return $menus;
    }

    /**
     * 获取指定节点ID的位置
     * @param string $id 节点id，如果没有指定，则取当前节点id
     * @param bool $del_last_url 是否删除最后一个节点的url地址
     * @param bool $check 检查节点是否存在，不存在则抛出错误
     * @return array
     * @throws \think\Exception
     */
    public static function getLocation($id = '', $del_last_url = false, $check = true,$model='admin')
    {
        $controller = request()->controller();
        $action     = request()->action();

        # 插件不需要限制菜单权限
        $pathinfo = request()->pathinfo();
        $expathinfo = explode('/',$pathinfo);
        $addons = $expathinfo[0];
        if($addons == 'addons') {
            $id = 34;
        }
        if ($id != '') {
            $cache_name = 'location_menu_'.$id;
        } else {
            $cache_name = 'location_'.$model.'_'.$controller.'_'.$action;
        }
        $location = cache($cache_name);
        if (!$location) {
            $map = [
                ['pid', '<>', 0],
                ['url_value', '=', strtolower('/'.trim(preg_replace("/[A-Z]/", "_\\0", $controller), "_").'/'.$action)]
            ];

            // 当前操作对应的节点ID
            $curr_id = $id == '' ? self::where($map)->value('id') : $id;
            // 获取节点ID是所有父级节点
            $location = Tree::getParents(self::column('id,pid,title,url_value,params'), $curr_id);
            if ($check && empty($location)) {
                throw new Exception('请添加菜单!', 9001);
            }
            // 剔除最后一个节点url
            if ($del_last_url) {
                $location[count($location) - 1]['url_value'] = '';
            }
        }
        return $location;
    }

    /**
     * 根据分组获取节点
     * @param string $group 分组名称
     * @param bool|string $fields 要返回的字段
     * @param array $map 查找条件

     * @return array
     */
    public static function getMenusByGroup($group = '', $fields = true, $map = [])
    {
        $map['module'] = $group;
        return self::where($map)->order('sort,id')->column('*', 'id');
    }

    /**
     * 获取节点分组

     * @return array
     */
    public static function getGroup()
    {
        $map['status'] = 1;
        $map['pid']    = 0;
        $menus = self::where($map)->order('id,sort')->column('module,title');
        return $menus;
    }

    /**
     * 获取所有子节点id
     * @param int $pid 父级id

     * @return array
     */
    public static function getChildsId($pid = 0)
    {
        $ids = self::where('pid', $pid)->column('id');
        foreach ($ids as $value) {
            $ids = array_merge($ids, self::getChildsId($value));
        }
        return $ids;
    }

    /**
     * 获取所有父节点id
     * @param int $id 节点id

     * @return array
     */
    public static function getParentsId($id = 0)
    {
        $pid  = self::where('id', $id)->value('pid');
        $pids = [];
        if ($pid != 0) {
            $pids[] = $pid;
            $pids = array_merge($pids, self::getParentsId($pid));
        }
        return $pids;
    }

    /**
     * 根据节点id获取上下级的所有id
     * @param int $id 节点id

     * @return array
     */
    public static function getLinkIds($id = 0)
    {
        $childs  = self::getChildsId($id);
        $parents = self::getParentsId($id);
        return array_merge((array)(int)$id, $childs, $parents);
    }
}

function admin_url($url = '', $vars = '', $suffix = true, $domain = false) {
    $url = url($url, $vars, $suffix, $domain);
    if (defined('ENTRANCE') && ENTRANCE == 'admin') {
        return $url;
    } else {
        return preg_replace('/\/index.php/', '/'.ADMIN_FILE, $url);
    }
}
function home_url($url = '', $vars = '', $suffix = true, $domain = false) {
    $url = url($url, $vars, $suffix, $domain);
    if (defined('ENTRANCE') && ENTRANCE == 'admin') {
        $base_file = request()->baseFile();
        $base_file = substr($base_file, strripos($base_file, '/') + 1);
        return preg_replace('/\/'.$base_file.'/', '/index.php', $url);
    } else {
        return $url;
    }
}