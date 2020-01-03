<?php
/**
 * Created by PhpStorm.
 * User: Win10 - Jeffery 13..@qq.com
 * Date: 2019/11/16
 * Time: 14:37
 */
namespace addons\test;	// 注意命名空间规范

use think\Addons;

/**
 * 插件测试
 * @author byron sampson
 */
class Plugin extends Addons	// 需继承think\Addons类
{
    // 该插件的基础信息
    public $info = [
        // 插件名[必填]
        'name'        => 'test',
        // 插件标题[必填]
        'title'       => '超级表单报名',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
        'identifier'  => 'test.cjf.addons',
        // 插件图标[选填]
        'icon'        => 'fa fa-fw fa-globe',
        // 插件图片
        'img_url'     => 'static/img/index.jpg',
        // 插件描述[选填]
        'description' => '这是一个超级表单插件，一切可以在插件里面进行创建表单项，进行数据处理（只是针对后台创建表单）',
        // 插件作者[必填]
        'author'      => 'Jeffery(1345199080@qq.com)',
        // 作者主页[选填]
        'author_url'  => 'http://www.cjf.com',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
        'version'     => '1.0.0',
        // 是否有后台管理功能
        'admin'       => '1',
    ];

    public $database_prefix = 'kpl_';

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

}