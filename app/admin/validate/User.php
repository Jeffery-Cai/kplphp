<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
----------------------------------------------------------------*/
namespace app\admin\validate;
use think\Validate;

/**
 * 用户验证器
 * @package app\admin\validate
 */
class User extends Validate
{
    // 定义验证规则
    protected $rule = [
        'username|登录名' => 'require|alphaNum|unique:admin_user',
        'nickname|昵称'  => 'require|unique:admin_user',
        'role|角色'      => 'require',
        'email|邮箱'     => 'email|unique:admin_user',
        'mobile|手机号'   => 'regex:^1\d{10}|unique:admin_user',
    ];

    // 定义验证提示
    protected $message = [
        'username.require' => '请输入登录名',
        'email.require'    => '邮箱不能为空',
        'email.email'      => '邮箱格式不正确',
        'email.unique'     => '该邮箱已存在',
        'mobile.regex'     => '手机号不正确',
    ];

    // 定义验证场景
    protected $scene = [
        //更新
        'update'  =>  ['email', 'mobile', 'role'],
        //登录
        'signin'  =>  ['username' => 'require', 'password' => 'require'],
    ];
}
