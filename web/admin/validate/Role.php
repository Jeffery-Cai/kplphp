<?php

namespace app\admin\user\validate;

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
