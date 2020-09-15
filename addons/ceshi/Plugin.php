<?php
/**
 * Created by PhpStorm.
 * User: Win10 - Jeffery 13..@qq.com
 * Date: 2019/11/16
 * Time: 14:37
 */
namespace addons\ceshi;	// 注意命名空间规范
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
        'name'        => 'ceshi',
        // 插件标题[必填]
        'title'       => '测试插件',
        // 插件唯一标识[必填],格式：插件名.开发者标识.plugin
        'identifier'  => 'ceshi.cjf.addons',
        // 插件图标[选填]
        'icon'        => 'fa fa-fw fa-globe',
        // 插件图片
        'img_url' => '/11.png',
        // 插件描述[必填]
        'description' => '这是一个测试插件，作为开发者参考的文本例子',
        // 插件作者[必填]
        'author'      => 'Jeffery(1345199080@qq.com)',
        // 作者主页[选填]
        'author_url'  => 'http://www.cjf.com',
        // 插件版本[必填],格式采用三段式：主版本号.次版本号.修订版本号
        'version'     => '1.0.1',
        // 是否有后台管理功能
        'admin'       => '1',
    ];

    #数据库前缀 =  如果有定义数据库字段的话，则默认使用该数据库前缀
    public $database_prefix = 'kkk_';


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


    /**
     * 实现的testhook钩子方法
     * @return mixed
     */
    public function testhook($param)
    {
        // 调用钩子时候的参数信息
//        print_r($param);
        // 当前插件的配置信息，配  置信息存在当前目录的config.php文件中，见下方
        print_r($this->getConfig(true));
        // 可以返回模板，模板文件默认读取的为插件目录中的文件。模板名不能为空！
        return $this->fetch('info');
    }


}