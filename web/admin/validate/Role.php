<?php
/*----------------------------------------------------------------
 * 版权所有 2019~2020 极盾工作室  kplphp地址[ http://www.kplphp.com ]
 * 作者由JefferyCai码云所创造 [ https://gitee.com/JefferyCai ]
 * 当前码云地址 与 操作文档都在 [ https://gitee.com/JefferyCai/kplphp ]
 * QQ群请加 972703635 [ https://jq.qq.com/?_wv=1027&k=5YnmIH8 ]，如有更多服务，请单独加群主: 1345199080
 * 角色验证功能
----------------------------------------------------------------*/
namespace app\admin\validate;

use think\Validate;

/**
 * 角色验证器
 * @package app\admin\validate

 */
class Role extends Validate
{
    // 定义验证规则
    protected $rule = [
        'pid|所属角色'   => 'require',
        'name|角色名称' => 'require|unique:admin_role',
    ];
}
