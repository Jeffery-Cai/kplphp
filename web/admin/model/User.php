<?php

namespace app\admin\model;

use think\Model;
use think\helper\Hash;
use app\admin\model\Role as RoleModel;
use think\Db;

/**
 * 后台用户模型
 * @package app\admin\model
 */
class User extends Model
{
    // 设置当前模型对应的完整数据表名称
    protected $name = 'admin_user';

    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 对密码进行加密
    public function setPasswordAttr($value)
    {
        return Hash::make((string)$value);
    }

    // 获取注册ip
    public function setSignupIpAttr()
    {
        return get_client_ip(1);
    }

    /**
     * 用户登录
     * @param string $username 用户名
     * @param string $password 密码
     * @param bool $rememberme 记住登录
     * @return bool|mixed
     */
    public function login($username = '', $password = '', $rememberme = false)
    {
        $username = trim($username);
        $password = trim($password);

        // 匹配登录方式
        if (preg_match("/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $username)) {
            // 邮箱登录
            $map['email'] = $username;
        } elseif (preg_match("/^1\d{10}$/", $username)) {
            // 手机号登录
            $map['mobile'] = $username;
        } else {
            // 用户名登录
            $map['username'] = $username;
        }

        $map['status'] = 1;

        // 查找用户
        $user = $this::where($map)->find();
        if (!$user) {
            return array(0, '用户不存在或被禁用！' );
        } else {
            // 检查是否分配用户组
            if ($user['role'] == 0) {
                return array(0,'禁止访问，原因：未分配角色！');
            }
            // 检查是可登录后台
            if (!RoleModel::where(['id' => $user['role'], 'status' => 1])->value('access')) {
                return array(0,'禁止访问，用户所在角色未启用或禁止访问后台！');
            }
            if (!Hash::check((string)$password, $user['password'])) {
                return array(0, '账号或者密码错误！');
            } else {
                $uid = $user['id'];
                // 更新登录信息
                $user['last_login_time'] = request()->time();
                $user['last_login_ip']   = get_client_ip();
                if ($user->save()) {

                    // 自动登录
                    return $this->autoLogin($this::find($uid), $rememberme);
                } else {
                    // 更新登录信息失败
                    return array(0,'登录信息更新失败，请重新登录！');
                }
            }
        }
        return false;
    }

    /**
     * 自动登录
     * @param object $user 用户对象
     * @param bool $rememberme 是否记住登录，默认7天

     * @return bool|int
     */
    public function autoLogin($user, $rememberme = false)
    {
        // 记录登录SESSION和COOKIES
        $auth = array(
            'uid'             => $user->id,
            'group'           => $user->group,
            'role'            => $user->role,
            'role_name'       => Role::where('id', $user->role)->value('name'),
            'avatar'          => $user->avatar,
            'username'        => $user->username,
            'nickname'        => $user->nickname,
            'last_login_time' => $user->last_login_time,
            'last_login_ip'   => get_client_ip(1),
        );
        session('user_auth', $auth);
        session('user_auth_sign', data_auth_sign($auth));

        // 保存用户节点权限
        if ($user->role != 1) {
            $menu_auth = RoleModel::where('id', session('user_auth.role'))->value('menu_auth');

            $menu_auth = json_decode($menu_auth, true);
            if (!$menu_auth) {
                session('user_auth', null);
                session('user_auth_sign', null);
                return array(0,'未分配任何节点权限！');
            }
        }

        // 记住登录
        if ($rememberme) {
            $signin_token = $user->username.$user->id.$user->last_login_time;
            cookie('uid', $user->id, 24 * 3600 * 7);
            cookie('signin_token', data_auth_sign($signin_token), 24 * 3600 * 7);
        }

        return array(1,$user->id);
    }
}

/**
 * 获取客户端IP地址
 * @param int $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param bool $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function get_client_ip($type = 0, $adv = false) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if($adv){
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    =   array_search('unknown',$arr);
            if(false !== $pos) unset($arr[$pos]);
            $ip     =   trim($arr[0]);
        }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     =   $_SERVER['HTTP_CLIENT_IP'];
        }elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     =   $_SERVER['REMOTE_ADDR'];
        }
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 数据签名认证
 * @param array $data 被认证的数据

 * @return string
 */
function data_auth_sign($data = [])
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