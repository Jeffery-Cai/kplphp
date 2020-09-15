-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2020-08-05 11:16:08
-- 服务器版本： 5.7.26
-- PHP 版本： 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `dbkplphpv2`
--

-- --------------------------------------------------------

--
-- 表的结构 `kpl_addons_manage`
--

CREATE TABLE `kpl_addons_manage` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '插件名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text COMMENT '插件描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text COMMENT '配置信息',
  `version` varchar(16) NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) NOT NULL DEFAULT '' COMMENT '插件唯一标识符',
  `admin` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否有后台管理',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '安装时间',
  `update_time` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `img_url` varchar(255) NOT NULL COMMENT '图片路径'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件表' ROW_FORMAT=DYNAMIC;

--
-- 转存表中的数据 `kpl_addons_manage`
--

INSERT INTO `kpl_addons_manage` (`id`, `name`, `title`, `icon`, `description`, `author`, `author_url`, `config`, `version`, `identifier`, `admin`, `create_time`, `update_time`, `sort`, `status`, `img_url`) VALUES
(73, 'ceshi', '测试插件', 'fa fa-fw fa-globe', '这是一个测试插件，作为开发者参考的文本例子', 'Jeffery(1345199080@qq.com)', 'http://www.cjf.com', NULL, '1.0.1', 'ceshi.cjf.addons', 1, 1593770528, 1593770528, 100, 1, '/11.png');

-- --------------------------------------------------------

--
-- 表的结构 `kpl_admin_menu`
--

CREATE TABLE `kpl_admin_menu` (
  `id` int(11) UNSIGNED NOT NULL,
  `pid` int(11) UNSIGNED NOT NULL DEFAULT '2' COMMENT '上级菜单id',
  `controller` varchar(50) CHARACTER SET latin1 DEFAULT NULL COMMENT '控制器模块',
  `action` varchar(50) CHARACTER SET latin1 DEFAULT NULL COMMENT '方法',
  `module` varchar(16) NOT NULL DEFAULT 'admin' COMMENT '模块名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '菜单图标',
  `url_type` varchar(16) NOT NULL DEFAULT '' COMMENT '链接类型（link：外链，module：模块）',
  `url_value` varchar(255) NOT NULL DEFAULT '' COMMENT '链接地址',
  `url_target` varchar(16) NOT NULL DEFAULT '_self' COMMENT '链接打开方式：_blank,_self',
  `online_hide` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '网站上线后是否隐藏',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `system_menu` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为系统菜单，系统菜单不可删除',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `params` varchar(255) NOT NULL DEFAULT '' COMMENT '参数'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='菜单';

--
-- 转存表中的数据 `kpl_admin_menu`
--

INSERT INTO `kpl_admin_menu` (`id`, `pid`, `controller`, `action`, `module`, `title`, `icon`, `url_type`, `url_value`, `url_target`, `online_hide`, `create_time`, `update_time`, `sort`, `system_menu`, `status`, `params`) VALUES
(41, 38, 'ceshi', 'del', 'admin', '删除', '', 'module_admin', '/ceshi/del', '_self', 0, 1593486566, 1593682786, 3, 1, 1, ''),
(2, 0, 'admin', 'index', 'admin', '系统功能', 'fa fa-fw fa-cog', 'module_admin', '/admin/index', '_self', 0, 0, 1593682786, 1, 1, 1, ''),
(3, 1, 'addonsmanage', 'index', 'admin', '已安装插件', 'fa fa-fw fa-puzzle-piece', 'module_admin', '/addonsmanage/index', '_self', 0, 0, 1593682786, 1, 1, 1, ''),
(4, 0, 'power', 'index', 'admin', '权限管理', 'fa fa-fw fa-key', 'module_admin', '/power/index', '_self', 0, 0, 1593682786, 2, 1, 1, ''),
(5, 4, 'power', 'index', 'admin', '用户设置', 'fa fa-fw fa-user', 'module_admin', '/power/index', '_self', 0, 0, 1593682786, 1, 1, 1, ''),
(6, 4, 'power', 'role', 'admin', '角色设置', 'fa fa-fw fa-users', 'module_admin', '/power/role', '_self', 0, 0, 1593682786, 2, 1, 1, ''),
(7, 2, 'admin', 'index', 'admin', '仪表盘', 'fa fa-fw fa-bars', 'module_admin', '/admin/index', '_self', 0, 0, 1593682786, 1, 1, 1, '?t=1'),
(8, 4, 'power', 'role_menu_set', 'admin', '菜单设置', 'fa fa-fw fa-bars', 'module_admin', '/power/role_menu_set', '_self', 0, 0, 1593682786, 3, 1, 1, ''),
(9, 8, 'power', 'role_menu_set_edit', 'admin', '编辑', 'fa fa-fw fa-users', 'module_admin', '/power/role_menu_set_edit', '_self', 0, 0, 1593682786, 1, 1, 1, ''),
(10, 6, 'power', 'role_edit', 'admin', '编辑', 'fa fa-fw fa-users', 'module_admin', '/power/role_edit', '_self', 0, 0, 1593682786, 1, 1, 1, ''),
(11, 5, 'power', 'edit', 'admin', '编辑', 'fa fa-fw fa-align-left', 'module_admin', '/power/edit', '_self', 0, 1576238109, 1593682786, 1, 1, 1, ''),
(15, 6, 'power', 'role_set', 'admin', '权限分配', 'fa fa-fw fa-users', 'module_admin', '/power/role_set', '_self', 0, 0, 1593682786, 3, 1, 1, ''),
(13, 8, 'power', 'save_menu', 'admin', '菜单保存节点', 'fa fa-fw fa-users', 'module_admin', '/power/save_menu', '_self', 0, 0, 1593682786, 2, 1, 1, ''),
(14, 8, 'power', 'role_menu_set_del', 'admin', '菜单删除节点', 'fa fa-fw fa-users', 'module_admin', '/power/role_menu_set_del', '_self', 0, 0, 1593682786, 3, 1, 1, ''),
(16, 15, 'power', 'role_set_edit', 'admin', '权限分配保存', 'fa fa-fw fa-users', 'module_admin', '/power/role_set_edit', '_self', 0, 0, 1593682786, 1, 1, 1, ''),
(17, 6, 'power', 'fieldchange', 'admin', '状态设置', 'fa fa-fw fa-users', 'module_admin', '/power/fieldchange', '_self', 0, 0, 1593682786, 4, 1, 1, ''),
(18, 6, 'power', 'role_fieldchange', 'admin', '角色设置', 'fa fa-fw fa-users', 'module_admin', '/power/role_fieldchange', '_self', 0, 0, 1593682786, 5, 1, 1, ''),
(20, 2, 'admin', 'index', 'admin', '配置设置', 'fa fa-fw fa-cog', 'module_admin', '/system/index', '_self', 0, 1576739950, 1593682786, 2, 1, 1, ''),
(44, 0, 'ceshi', 'index', 'admin', '案例管理', 'fa fa-file-text-o', 'module_admin', '/ceshi/index', '_self', 0, 1576834922, 1593682786, 4, 1, 1, ''),
(22, 44, 'ceshi', 'import_csv', 'admin', '导入CSV表', 'fa fa-file-text-o', 'module_admin', '/ceshi/import_csv', '_self', 0, 1576834922, 1593682786, 2, 1, 1, ''),
(23, 22, 'ceshi', 'post_file_csv', 'admin', '提交', '', 'module_admin', '/ceshi/post_file_csv', '_self', 0, 1576835335, 1593682786, 1, 1, 1, ''),
(25, 6, 'power', 'role_del', 'admin', '删除', 'fa fa-fw fa-align-left', 'module_admin', '/power/role_del', '_self', 0, 1576238109, 1593682786, 2, 1, 1, ''),
(26, 5, 'power', 'del', 'admin', '删除', 'fa fa-fw fa-align-left', 'module_admin', '/power/del', '_self', 0, 1576238109, 1593682786, 2, 1, 1, ''),
(38, 44, 'ceshi', 'index', 'admin', '首页', 'fa fa-fw fa-th-list', 'module_admin', '/ceshi/index', '_self', 0, 1579145812, 1593682786, 1, 1, 1, ''),
(27, 20, 'system', 'set_password', 'admin', '密码设置', '', 'module_admin', '/system/set_password', '_self', 0, 1577515399, 1593682786, 1, 1, 1, ''),
(32, 7, 'system', 'add', 'admin', '新增', '', 'module_admin', '/admin/add', '_self', 0, 1577670716, 1593682786, 1, 1, 1, ''),
(34, 3, 'addons_manage', 'uninstall', 'admin', '插件卸载', '', 'module_admin', '/addonsmanage/uninstall', '_self', 0, 1578651183, 1593682786, 1, 1, 1, ''),
(35, 3, 'addons_manage', 'install', 'admin', '插件安装', '', 'module_admin', '/addonsmanage/install', '_self', 0, 1578651183, 1593682786, 2, 1, 1, ''),
(40, 38, 'ceshi', 'edit', 'admin', '编辑', '', 'module_admin', '/ceshi/edit', '_self', 0, 1593486535, 1593682786, 2, 1, 1, ''),
(39, 38, 'ceshi', 'see', 'admin', '查看', 'fa fa-fw fa-bath', 'module_admin', '/ceshi/see', '_self', 0, 1593486480, 1593682786, 1, 1, 1, ''),
(1, 43, 'addonsmanage', 'index', 'admin', '插件管理', 'fa fa-fw fa-puzzle-piece', 'module_admin', '', '_self', 0, 0, 1593682786, 1, 1, 1, ''),
(42, 22, 'upload', 'file', 'admin', '上传', '', 'module_admin', '/upload/file', '_self', 0, 1593486566, 1593682786, 2, 1, 1, ''),
(43, 0, 'addonsmanage', 'index', 'admin', '插件管理', 'fa fa-fw fa-bath', 'module_admin', '/addonsmanage/index', '_self', 0, 1593570405, 1593682786, 3, 1, 1, ''),
(46, 35, 'addons_manage', 'checkdes', 'admin', '查看详情', '', 'module_admin', '/addonsmanage/checkdes', '_self', 0, 1578651183, 1593682786, 1, 1, 1, ''),
(50, 38, 'ceshi', 'add', 'admin', '添加', '', 'module_admin', '/ceshi/add', '_self', 0, 1593486535, 1593682786, 2, 1, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `kpl_admin_module`
--

CREATE TABLE `kpl_admin_module` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '模块名称（标识）',
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '模块标题',
  `icon` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '图标',
  `description` text CHARACTER SET utf8 NOT NULL COMMENT '描述',
  `author` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text CHARACTER SET utf8 COMMENT '配置信息',
  `access` text CHARACTER SET utf8 COMMENT '授权配置',
  `version` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '版本号',
  `identifier` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '模块唯一标识符',
  `system_module` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为系统模块',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `kpl_admin_module`
--

INSERT INTO `kpl_admin_module` (`id`, `name`, `title`, `icon`, `description`, `author`, `author_url`, `config`, `access`, `version`, `identifier`, `system_module`, `create_time`, `update_time`, `sort`, `status`) VALUES
(1, 'admin', '系统', 'fa fa-fw fa-gear', '系统模块，KPLPHP的核心模块', 'kplphp', 'http://www.kplphp.com', '', '', '1.0.0', 'admin.kplphp.module', 1, 1468204902, 1468204902, 100, 1),
(2, 'user', '用户', 'fa fa-fw fa-gear', '系统模块，KPLPHP的核心模块', 'kplphp', 'http://www.kplphp.com', '', '', '1.0.0', 'admin.kplphp.module', 1, 1468204902, 1468204902, 100, 1);

-- --------------------------------------------------------

--
-- 表的结构 `kpl_admin_role`
--

CREATE TABLE `kpl_admin_role` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '角色id',
  `pid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级角色',
  `name` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '角色名称',
  `description` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '角色描述',
  `menu_auth` text CHARACTER SET utf8 COMMENT '菜单权限',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) DEFAULT '1' COMMENT '状态',
  `access` tinyint(4) UNSIGNED NOT NULL DEFAULT '1' COMMENT '是否可登录后台',
  `default_module` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '默认访问模块'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `kpl_admin_role`
--

INSERT INTO `kpl_admin_role` (`id`, `pid`, `name`, `description`, `menu_auth`, `sort`, `create_time`, `update_time`, `status`, `access`, `default_module`) VALUES
(1, 0, '超级管理员', '系统默认创建的角色，拥有最高权限', '', 0, 1476270000, 1468117612, 1, 1, 0),
(6, 0, '客服角色', '客服角色', '[\"7\",\"20\",\"3\",\"1\",\"23\",\"22\",\"21\",\"2\"]', 0, 1577503757, 1593656173, 1, 1, 0),
(20, 0, 'halo', 'halo', '[\"2\",\"4\",\"5\",\"7\",\"11\",\"21\",\"22\",\"23\",\"32\"]', 0, 1577673518, 1577678790, 1, 1, 0),
(7, 0, '测试组', '测试组', '[\"1\",\"2\",\"4\",\"5\",\"6\",\"7\",\"8\",\"15\",\"20\",\"21\",\"22\",\"32\"]', 0, 1577507226, 1577673207, 1, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `kpl_admin_system`
--

CREATE TABLE `kpl_admin_system` (
  `id` int(11) NOT NULL,
  `name` varchar(250) DEFAULT NULL COMMENT '名称',
  `kvalue` text NOT NULL,
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL DEFAULT 'text' COMMENT '类型',
  `istype` tinyint(2) NOT NULL DEFAULT '0' COMMENT '0系统设置字段1其他设置字段',
  `key` varchar(150) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统设置表';

--
-- 转存表中的数据 `kpl_admin_system`
--

INSERT INTO `kpl_admin_system` (`id`, `name`, `kvalue`, `create_time`, `update_time`, `type`, `istype`, `key`) VALUES
(1, '站点标题', '0', 0, 0, 'text', 0, NULL),
(2, '测试1', '2', 0, 0, 'text', 0, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `kpl_admin_user`
--

CREATE TABLE `kpl_admin_user` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '用户名',
  `nickname` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '昵称',
  `password` varchar(96) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '密码',
  `email` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '邮箱地址',
  `email_bind` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否绑定邮箱地址',
  `mobile` varchar(11) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '手机号码',
  `mobile_bind` tinyint(1) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否绑定手机号码',
  `avatar` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '头像',
  `money` decimal(11,2) UNSIGNED NOT NULL DEFAULT '0.00' COMMENT '余额',
  `score` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '积分',
  `role` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '角色ID',
  `group` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '部门id',
  `signup_ip` bigint(20) UNSIGNED NOT NULL DEFAULT '0' COMMENT '注册ip',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `last_login_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '最后一次登录时间',
  `last_login_ip` varchar(20) NOT NULL DEFAULT '0' COMMENT '登录ip',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '状态：0禁用，1启用'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `kpl_admin_user`
--

INSERT INTO `kpl_admin_user` (`id`, `username`, `nickname`, `password`, `email`, `email_bind`, `mobile`, `mobile_bind`, `avatar`, `money`, `score`, `role`, `group`, `signup_ip`, `create_time`, `update_time`, `last_login_time`, `last_login_ip`, `sort`, `status`) VALUES
(1, 'admin', '超级管理员', '$2y$10$2htWLSKjWsQEFIQrCfBvvuGVKhMuOaAi1JnxdfwhW1caDL.TZcyym', '', 0, '', 0, 0, '0.00', 0, 1, 0, 0, 1476065410, 1593656159, 1593656159, '192.168.0.145', 100, 1),
(4, 'kefu8', '客服1', '$2y$10$N.KPFC1IHunaRv6wIjzNEOIMB/CkI6qaXR6izXUJAn2Ffemr7hPey', '1345199080@qq.com', 0, '13112157790', 0, 0, '0.00', 0, 6, 0, 0, 1476065410, 1593483355, 1577174284, '192.168.2.132', 100, 1),
(19, 'halo', 'halo', '$2y$10$tH8AZsPbNYb12mE0PmMkJePpI7TK3OHToSsyjxpBWjWwye6xJSaM2', '1347199080@qq.com', 0, '12112157790', 0, 0, '0.00', 0, 20, 0, 0, 1577673554, 1577775142, 1577775142, '192.168.2.132', 100, 1),
(16, 'ceshi', '测试', '$2y$10$lcwLyFR3CzGyynowwv39JuS8jRX2oir2t9EcDZ6dnQUddZ2HPKIp2', '1345199081@qq.com', 0, '13112158790', 0, 0, '0.00', 0, 7, 0, 0, 1577507157, 1577673338, 1577673337, '192.168.2.132', 100, 1),
(42, 'adminces', '213123', '$2y$10$rY1Og5aA4yssvcpLbMoOae.9VEyPa3H3qHerJy7kE7r4P6li8SYey', '123456@qq.com', 0, '13112157722', 0, 0, '0.00', 0, 6, 0, 0, 1593514372, 1593514450, 1593514450, '192.168.0.145', 100, 1);

-- --------------------------------------------------------

--
-- 表的结构 `kpl_admin_view`
--

CREATE TABLE `kpl_admin_view` (
  `id` int(11) NOT NULL,
  `action` varchar(150) NOT NULL COMMENT '访问哪个方法',
  `create_time` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `kpl_csvceshi`
--

CREATE TABLE `kpl_csvceshi` (
  `id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '学校名称',
  `telphone` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '电话号码',
  `username` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '学生名称',
  `phone` varchar(24) DEFAULT NULL COMMENT '手机号码',
  `create_time` datetime DEFAULT NULL,
  `password` varchar(200) NOT NULL,
  `status` int(11) DEFAULT NULL,
  `hobby` varchar(255) DEFAULT NULL,
  `start_time` varchar(200) NOT NULL,
  `touxiang` varchar(255) DEFAULT NULL,
  `hobby1` varchar(255) DEFAULT NULL,
  `hobby2` varchar(255) DEFAULT NULL,
  `hobby3` varchar(255) DEFAULT NULL,
  `hobby4` varchar(255) DEFAULT NULL,
  `hobby5` varchar(255) DEFAULT NULL,
  `hobby6` varchar(255) DEFAULT NULL,
  `start_time1` varchar(255) DEFAULT NULL,
  `touxiang1` varchar(255) DEFAULT NULL,
  `images` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='csv测试导入数据';

--
-- 转存表中的数据 `kpl_csvceshi`
--

INSERT INTO `kpl_csvceshi` (`id`, `name`, `telphone`, `username`, `phone`, `create_time`, `password`, `status`, `hobby`, `start_time`, `touxiang`, `hobby1`, `hobby2`, `hobby3`, `hobby4`, `hobby5`, `hobby6`, `start_time1`, `touxiang1`, `images`) VALUES
(1, '广州中山学院', '020-123123', '郭德生', '13112158888', '2019-12-21 10:41:41', '', 0, 'KPLPHP,kplphp,HTML5,CSS3', '2020-07-28 12:00  - 2020-07-28 11:59 ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, '北京清华学院', '020-787878', '蔡文德', '15878974549', '2019-12-21 10:41:41', '', 0, 'KPLPHP,kplphp,HTML5,CSS3', '2020-02-08 20:00:00', NULL, '0', '0', '0', '0', '0', NULL, '', NULL, NULL),
(3, '广东岭南职业学院', '020-231234', '李元芳', '17898154757', '2019-12-21 10:41:41', '', 0, 'KPLPHP,kplphp,HTML5,CSS3', '2020-06-30 12:00  - 2020-06-30 11:59 ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, '123213213213213123', NULL, NULL, NULL, '2020-07-03 19:03:52', '', 0, 'KPLPHP,kplphp,HTML5,CSS3', 'Invalid date', NULL, '0', '0', '0', '0', '0', NULL, '2020-07-03 12:00  - 2020-07-03 11:59 ', NULL, ''),
(5, '123123', NULL, NULL, NULL, '2020-07-03 19:05:18', '', 0, 'KPLPHP,kplphp,HTML5,CSS3', 'Invalid date', NULL, '0', '0', '0', '0', '0', NULL, '2020-07-03 12:00  - 2020-07-03 11:59 ', NULL, '');

-- --------------------------------------------------------

--
-- 表的结构 `kpl_file`
--

CREATE TABLE `kpl_file` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL COMMENT 'image'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='kplphp上传文件库';

--
-- 转存表中的数据 `kpl_file`
--

INSERT INTO `kpl_file` (`id`, `filename`, `type`) VALUES
(9, 'storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(10, 'storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(7, 'storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(8, 'storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(11, 'storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(12, 'storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(13, 'storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(14, 'storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(15, 'storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(16, 'storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(17, 'storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(18, 'storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(19, 'storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(20, 'storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(21, 'storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(22, 'storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(23, 'storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(24, 'storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(25, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(26, '/storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(27, '/storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(28, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(29, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(30, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(31, '/storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(32, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(33, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(34, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(35, '/storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(36, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(37, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(38, '/storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(39, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(40, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(41, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(42, '/storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(43, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(44, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(45, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(46, '/storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(47, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(48, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(49, '/storage/uploads/8c\\4a76e930d59e7d6bc00a248e18c751.png', 'image'),
(50, '/storage/uploads/be\\4ae050950f7249a06268ee82d13e43.png', 'image'),
(51, '/storage/uploads/6e\\9025fc8aa563b95ef337f74e63e9ba.png', 'image'),
(52, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(53, '/storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(54, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(55, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(56, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(57, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(63, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(59, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(60, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(62, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image'),
(64, '/storage/uploads/db\\abc7353da6dc7578c668445b1f0193.jpg', 'image'),
(65, '/storage/uploads/5f\\8982024f9155cece4168d9243a0eb5.jpg', 'image'),
(66, '/storage/uploads/86\\3710fb53d4397eb711a811d0d0070d.jpeg', 'image');

--
-- 转储表的索引
--

--
-- 表的索引 `kpl_addons_manage`
--
ALTER TABLE `kpl_addons_manage`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `kpl_admin_menu`
--
ALTER TABLE `kpl_admin_menu`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `kpl_admin_module`
--
ALTER TABLE `kpl_admin_module`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `kpl_admin_role`
--
ALTER TABLE `kpl_admin_role`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `kpl_admin_system`
--
ALTER TABLE `kpl_admin_system`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `kpl_admin_user`
--
ALTER TABLE `kpl_admin_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `kpl_admin_view`
--
ALTER TABLE `kpl_admin_view`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `kpl_csvceshi`
--
ALTER TABLE `kpl_csvceshi`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `kpl_file`
--
ALTER TABLE `kpl_file`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `kpl_addons_manage`
--
ALTER TABLE `kpl_addons_manage`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- 使用表AUTO_INCREMENT `kpl_admin_menu`
--
ALTER TABLE `kpl_admin_menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- 使用表AUTO_INCREMENT `kpl_admin_module`
--
ALTER TABLE `kpl_admin_module`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `kpl_admin_role`
--
ALTER TABLE `kpl_admin_role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色id', AUTO_INCREMENT=21;

--
-- 使用表AUTO_INCREMENT `kpl_admin_system`
--
ALTER TABLE `kpl_admin_system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `kpl_admin_user`
--
ALTER TABLE `kpl_admin_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- 使用表AUTO_INCREMENT `kpl_admin_view`
--
ALTER TABLE `kpl_admin_view`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- 使用表AUTO_INCREMENT `kpl_csvceshi`
--
ALTER TABLE `kpl_csvceshi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用表AUTO_INCREMENT `kpl_file`
--
ALTER TABLE `kpl_file`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
