<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'make:kplphp_curd'    => app\command\KplphpCurd::class,
        'make:kplphp_kbuilder'    => app\command\KplphpKbuilderCurd::class,
    ],
];
