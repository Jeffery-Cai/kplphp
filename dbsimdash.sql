-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- 主机： localhost
-- 生成日期： 2019-12-13 20:44:44
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
-- 数据库： `dbsimdash`
--

-- --------------------------------------------------------

--
-- 表的结构 `s_addons_manage`
--

CREATE TABLE `s_addons_manage` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '插件名称',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '插件标题',
  `icon` varchar(64) NOT NULL DEFAULT '' COMMENT '图标',
  `description` text NOT NULL COMMENT '插件描述',
  `author` varchar(32) NOT NULL DEFAULT '' COMMENT '作者',
  `author_url` varchar(255) NOT NULL DEFAULT '' COMMENT '作者主页',
  `config` text NOT NULL COMMENT '配置信息',
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
-- 转存表中的数据 `s_addons_manage`
--

INSERT INTO `s_addons_manage` (`id`, `name`, `title`, `icon`, `description`, `author`, `author_url`, `config`, `version`, `identifier`, `admin`, `create_time`, `update_time`, `sort`, `status`, `img_url`) VALUES
(24, 'test', '超级表单报名', 'fa fa-fw fa-globe', '这是一个超级表单插件，一切可以在插件里面进行创建表单项，进行数据处理（只是针对后台创建表单）', 'Jeffery(1345199080@qq.com)', 'http://www.cjf.com', '', '1.0.0', 'test.cjf.addons', 1, 1574408822, 1574408822, 100, 1, 'static/img/index.jpg'),
(25, 'sets', '设置插件', 'fa fa-fw fa-globe', '这是一个超级表单插件，一切可以在插件里面进行创建表单项，进行数据处理（只是针对后台创建表单）', 'Jeffery(1345199080@qq.com)', 'http://www.cjf.com', '', '1.0.1', 'sets.cjf.addons', 1, 1574410078, 1574410078, 100, 1, 'static/img/index.jpg');

-- --------------------------------------------------------

--
-- 表的结构 `s_admin_menu`
--

CREATE TABLE `s_admin_menu` (
  `id` int(11) UNSIGNED NOT NULL,
  `pid` int(11) UNSIGNED NOT NULL DEFAULT '2' COMMENT '上级菜单id',
  `controller` varchar(50) NOT NULL COMMENT '控制器模块',
  `action` varchar(50) NOT NULL COMMENT '方法',
  `module` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT 'admin' COMMENT '模块名称',
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '菜单标题',
  `icon` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '菜单图标',
  `url_type` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '链接类型（link：外链，module：模块）',
  `url_value` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '链接地址',
  `url_target` varchar(16) CHARACTER SET utf8 NOT NULL DEFAULT '_self' COMMENT '链接打开方式：_blank,_self',
  `online_hide` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '网站上线后是否隐藏',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `sort` int(11) NOT NULL DEFAULT '100' COMMENT '排序',
  `system_menu` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否为系统菜单，系统菜单不可删除',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `params` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '参数'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='菜单';

--
-- 转存表中的数据 `s_admin_menu`
--

INSERT INTO `s_admin_menu` (`id`, `pid`, `controller`, `action`, `module`, `title`, `icon`, `url_type`, `url_value`, `url_target`, `online_hide`, `create_time`, `update_time`, `sort`, `system_menu`, `status`, `params`) VALUES
(1, 2, 'AddonsManage', 'index', 'admin', '插件管理', 'fa fa-fw fa-puzzle-piece', 'module_admin', '', '_self', 0, 0, 1576240999, 2, 1, 1, ''),
(2, 0, 'System', 'index', 'admin', '系统菜单', '', 'module_admin', '/system/index', '_self', 0, 0, 1576152128, 1, 1, 1, ''),
(3, 1, 'AddonsManage', 'index', 'admin', '已安装插件', 'fa fa-fw fa-puzzle-piece', 'module_admin', '/addons_manage/index', '_self', 0, 0, 1576240999, 1, 1, 1, ''),
(4, 2, 'Power', 'index', 'admin', '权限管理', 'fa fa-fw fa-key', 'module_admin', '', '_self', 0, 0, 1576240999, 1, 1, 1, ''),
(5, 4, 'Power', 'index', 'admin', '用户设置', 'fa fa-fw fa-user', 'module_admin', '/power/index', '_self', 0, 0, 1576240999, 1, 1, 1, ''),
(6, 4, 'Power', 'role', 'admin', '角色设置', 'fa fa-fw fa-users', 'module_admin', '/power/role', '_self', 0, 0, 1576240999, 2, 1, 1, ''),
(7, 0, 'Admin', 'index', 'admin', '仪表盘', '', 'module_admin', '/admin/index', '_self', 0, 0, 1576152263, 1, 1, 1, '?t=1'),
(8, 4, 'Power', 'role_menu_set', 'admin', '菜单设置', 'fa fa-fw fa-bars', 'module_admin', '/power/role_menu_set', '_self', 0, 0, 1576240999, 3, 1, 1, ''),
(9, 8, 'Power', 'role_menu_set_edit', 'admin', '编辑', 'fa fa-fw fa-users', 'module_admin', '/power/role_menu_set_edit', '_self', 0, 0, 1576240999, 1, 1, 1, ''),
(10, 6, 'Power', 'role_edit', 'admin', '编辑', 'fa fa-fw fa-users', 'module_admin', '/power/role_edit', '_self', 0, 0, 1576240999, 1, 1, 1, ''),
(11, 5, 'power', 'edit', 'admin', '编辑', 'fa fa-fw fa-align-left', '', '/power/edit', '_self', 0, 1576238109, 1576240999, 1, 1, 1, ''),
(13, 8, 'Power', 'save_menu', 'admin', '菜单保存节点', 'fa fa-fw fa-users', 'module_admin', '/power/save_menu', '_self', 0, 0, 1576240999, 2, 1, 1, ''),
(14, 8, 'Power', 'role_menu_set_del', 'admin', '菜单删除节点', 'fa fa-fw fa-users', 'module_admin', '/power/role_menu_set_del', '_self', 0, 0, 1576240999, 3, 1, 1, '');

-- --------------------------------------------------------

--
-- 表的结构 `s_admin_module`
--

CREATE TABLE `s_admin_module` (
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
-- 转存表中的数据 `s_admin_module`
--

INSERT INTO `s_admin_module` (`id`, `name`, `title`, `icon`, `description`, `author`, `author_url`, `config`, `access`, `version`, `identifier`, `system_module`, `create_time`, `update_time`, `sort`, `status`) VALUES
(1, 'admin', '系统', 'fa fa-fw fa-gear', '系统模块，DolphinPHP的核心模块', 'DolphinPHP', 'http://www.dolphinphp.com', '', '', '1.0.0', 'admin.dolphinphp.module', 1, 1468204902, 1468204902, 100, 1),
(2, 'user', '用户', 'fa fa-fw fa-user', '用户模块，DolphinPHP自带模块', 'DolphinPHP', 'http://www.dolphinphp.com', '', '', '1.0.0', 'user.dolphinphp.module', 1, 1468204902, 1468204902, 100, 1);

-- --------------------------------------------------------

--
-- 表的结构 `s_admin_role`
--

CREATE TABLE `s_admin_role` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '角色id',
  `pid` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '上级角色',
  `name` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '角色名称',
  `description` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '' COMMENT '角色描述',
  `menu_auth` text CHARACTER SET utf8 COMMENT '菜单权限',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `status` tinyint(2) NOT NULL DEFAULT '1' COMMENT '状态',
  `access` tinyint(4) UNSIGNED NOT NULL DEFAULT '0' COMMENT '是否可登录后台',
  `default_module` int(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT '默认访问模块'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `s_admin_role`
--

INSERT INTO `s_admin_role` (`id`, `pid`, `name`, `description`, `menu_auth`, `sort`, `create_time`, `update_time`, `status`, `access`, `default_module`) VALUES
(1, 0, '超级管理员', '系统默认创建的角色，拥有最高权限', '', 0, 1476270000, 1468117612, 1, 1, 0),
(2, 0, '客服管理员', '系统默认创建的角色，拥有客服系统权限', '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\",\"10\",\"11\",\"12\",\"13\",\"14\",\"15\",\"16\",\"17\",\"18\",\"32\",\"33\",\"34\",\"35\",\"36\",\"37\",\"38\",\"39\",\"40\",\"41\",\"42\",\"43\",\"44\",\"45\",\"46\",\"47\",\"48\",\"49\",\"50\",\"51\",\"52\",\"53\",\"54\",\"55\",\"56\",\"57\",\"60\",\"61\",\"62\",\"64\",\"65\",\"66\",\"69\",\"70\",\"77\",\"78\",\"79\",\"80\",\"81\",\"183\",\"184\",\"185\",\"186\",\"187\",\"188\",\"189\",\"190\",\"191\",\"192\",\"193\",\"194\",\"195\",\"207\",\"208\",\"209\",\"210\",\"211\",\"212\",\"213\",\"222\",\"223\",\"224\",\"225\",\"226\",\"227\",\"228\",\"229\",\"230\",\"231\",\"232\",\"233\",\"236\",\"237\",\"238\",\"239\",\"240\",\"241\",\"242\",\"250\",\"259\"]', 0, 1476270000, 1576134115, 0, 1, 0);

-- --------------------------------------------------------

--
-- 表的结构 `s_admin_user`
--

CREATE TABLE `s_admin_user` (
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
-- 转存表中的数据 `s_admin_user`
--

INSERT INTO `s_admin_user` (`id`, `username`, `nickname`, `password`, `email`, `email_bind`, `mobile`, `mobile_bind`, `avatar`, `money`, `score`, `role`, `group`, `signup_ip`, `create_time`, `update_time`, `last_login_time`, `last_login_ip`, `sort`, `status`) VALUES
(1, 'admin', '超级管理员', '$2y$10$Brw6wmuSLIIx3Yabid8/Wu5l8VQ9M/H/CG3C9RqN9dUCwZW3ljGOK', '', 0, '', 0, 0, '0.00', 0, 1, 0, 0, 1476065410, 1576118708, 1576118707, '192.168.2.132', 100, 1),
(4, 'admin123', '超级管理员2', '$2y$10$Brw6wmuSLIIx3Yabid8/Wu5l8VQ9M/H/CG3C9RqN9dUCwZW3ljGOK', '1345199080@qq.com', 0, '13112157790', 0, 0, '0.00', 0, 2, 0, 0, 1476065410, 1576123439, 1575364126, '192.168.2.132', 100, 1),
(5, 'admin', '超级管理员2', '$2y$10$Brw6wmuSLIIx3Yabid8/Wu5l8VQ9M/H/CG3C9RqN9dUCwZW3ljGOK', '1345199080@qq.com', 0, '13112157790', 0, 0, '0.00', 0, 1, 0, 0, 1476065410, 1576123446, 1575118268, '192.168.2.132', 100, 0),
(6, 'admin', '超级管理员3', '$2y$10$Brw6wmuSLIIx3Yabid8/Wu5l8VQ9M/H/CG3C9RqN9dUCwZW3ljGOK', '', 0, '', 0, 0, '0.00', 0, 1, 0, 0, 1476065410, 1595118268, 1575117268, '192.168.2.132', 100, 1),
(7, 'admin', '超级管理员4', '$2y$10$Brw6wmuSLIIx3Yabid8/Wu5l8VQ9M/H/CG3C9RqN9dUCwZW3ljGOK', '', 0, '', 0, 0, '0.00', 0, 1, 0, 0, 1476065410, 1575118268, 1575218268, '192.168.2.132', 100, 0),
(9, 'admin', '超级管理员5', '', '', 0, '', 0, 0, '0.00', 0, 1, 0, 0, 1576120973, 1576120973, 0, '0', 100, 0),
(10, 'admin123', '超级管理员1', '', '123456@qq.com', 0, '13112157790', 0, 0, '0.00', 0, 2, 0, 0, 1576120987, 1576120987, 0, '0', 100, 0),
(11, 'admin123', '超级管理员1', '', '123456@qq.com', 0, '13112157790', 0, 0, '0.00', 0, 2, 0, 0, 1576121024, 1576121024, 0, '0', 100, 0),
(12, 'admin123', '超级管理员1', '', '123456@qq.com', 0, '13112157112', 0, 0, '0.00', 0, 2, 0, 0, 1576121143, 1576121312, 0, '0', 100, 0),
(14, '', 'feng', '', '1345199080@qq.com', 0, '13112157790', 0, 0, '0.00', 0, 1, 0, 0, 1576123457, 1576123457, 0, '0', 100, 0);

--
-- 转储表的索引
--

--
-- 表的索引 `s_addons_manage`
--
ALTER TABLE `s_addons_manage`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- 表的索引 `s_admin_menu`
--
ALTER TABLE `s_admin_menu`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `s_admin_module`
--
ALTER TABLE `s_admin_module`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `s_admin_role`
--
ALTER TABLE `s_admin_role`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `s_admin_user`
--
ALTER TABLE `s_admin_user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `s_addons_manage`
--
ALTER TABLE `s_addons_manage`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- 使用表AUTO_INCREMENT `s_admin_menu`
--
ALTER TABLE `s_admin_menu`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- 使用表AUTO_INCREMENT `s_admin_module`
--
ALTER TABLE `s_admin_module`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `s_admin_role`
--
ALTER TABLE `s_admin_role`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '角色id', AUTO_INCREMENT=4;

--
-- 使用表AUTO_INCREMENT `s_admin_user`
--
ALTER TABLE `s_admin_user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
