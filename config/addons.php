<?php
return [
    'autoload' => true,
    'hooks' => [
        // 可以定义多个钩子
        'testhook'=>'sets'
    ],
    'route' => [],
    'service' => [],
    'addons_path' => app()->getRootPath() . 'addons/',
    'addons_admin_public_temp' => app()->getRootPath() . 'app/view/admin/public',
];