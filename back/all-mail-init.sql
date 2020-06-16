/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : all-mail

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-06-16 17:57:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for s_email
-- ----------------------------
DROP TABLE IF EXISTS `s_email`;
CREATE TABLE `s_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_type_id` int(11) NOT NULL COMMENT '邮箱类型',
  `phone_id` int(11) DEFAULT NULL COMMENT '手机id',
  `machine_id` int(11) DEFAULT NULL COMMENT '机器id',
  `email_name` varchar(128) NOT NULL COMMENT '邮箱名',
  `udid` varchar(128) DEFAULT NULL COMMENT 'udid',
  `email_password` varchar(255) DEFAULT NULL,
  `reg_status` tinyint(4) DEFAULT '2' COMMENT '注册状态: 2/未注册 0/失败 1/成功',
  `use_status` tinyint(2) DEFAULT '0' COMMENT '使用状态:0/未使用 1/已使用 2/停止使用',
  `is_get` tinyint(2) DEFAULT '0' COMMENT '是否已导出: 0/未 1/已 导出',
  `fail_msg` varchar(128) DEFAULT NULL COMMENT '失败原因',
  `imapsvr` varchar(128) DEFAULT NULL COMMENT 'imap地址',
  `pop3svr` varchar(128) DEFAULT NULL COMMENT '接收服务器',
  `smtpsvr` varchar(128) DEFAULT NULL COMMENT '发送服务器',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_email` (`email_type_id`,`email_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=4001 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s_email
-- ----------------------------
INSERT INTO `s_email` VALUES ('3', '1', null, 'noashashehkau@outlook.com', '', '7dh18XTu', '2', '0', '0', '', 'imap.rambler.ru', 'pop.rambler.ru', 'smtp.rambler.ru', '2020-06-16 17:55:12', null, null);

-- ----------------------------
-- Table structure for s_email_type
-- ----------------------------
DROP TABLE IF EXISTS `s_email_type`;
CREATE TABLE `s_email_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL COMMENT '邮箱名',
  `imapsvr` varchar(128) DEFAULT NULL COMMENT 'imap地址',
  `pop3svr` varchar(128) DEFAULT NULL COMMENT '接收服务器',
  `smtpsvr` varchar(128) DEFAULT NULL COMMENT '发送服务器',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s_email_type
-- ----------------------------
INSERT INTO `s_email_type` VALUES ('1', '俄罗斯邮箱', 'imap.rambler.ru', 'pop.rambler.ru', 'smtp.rambler.ru', '2020-05-27 16:46:25', '2020-05-27 16:55:11', null);

-- ----------------------------
-- Table structure for s_ip_address
-- ----------------------------
DROP TABLE IF EXISTS `s_ip_address`;
CREATE TABLE `s_ip_address` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(64) DEFAULT NULL COMMENT 'ip地址',
  `use_status` tinyint(2) DEFAULT '0' COMMENT '使用状态:0/未使用 1/已使用',
  `used_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s_ip_address
-- ----------------------------
INSERT INTO `s_ip_address` VALUES ('1', '192.168.0.1', '0', null, '2020-06-15 16:43:40', '2020-06-15 18:51:49', null);
INSERT INTO `s_ip_address` VALUES ('2', '127.0.0.1', '0', null, '2020-06-15 17:07:16', '2020-06-15 18:52:10', null);

-- ----------------------------
-- Table structure for s_job_log
-- ----------------------------
DROP TABLE IF EXISTS `s_job_log`;
CREATE TABLE `s_job_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_id` int(11) DEFAULT NULL COMMENT '手机表id',
  `phone_sn` varchar(128) DEFAULT NULL COMMENT 'phone_sn',
  `udid` varchar(128) DEFAULT NULL COMMENT 'udid',
  `des` varchar(128) DEFAULT NULL COMMENT '描述信息',
  `status` tinyint(2) DEFAULT NULL COMMENT '状态:1/成功 0/失败',
  `program_version` varchar(32) DEFAULT NULL COMMENT '程序版本',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s_job_log
-- ----------------------------

-- ----------------------------
-- Table structure for s_machine
-- ----------------------------
DROP TABLE IF EXISTS `s_machine`;
CREATE TABLE `s_machine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` int(11) DEFAULT NULL,
  `HWModelStr` varchar(255) DEFAULT NULL,
  `ModelNumber` varchar(128) DEFAULT NULL,
  `PhoneModel` varchar(64) DEFAULT NULL,
  `ProductType` varchar(128) DEFAULT NULL,
  `use_status` tinyint(2) DEFAULT '0' COMMENT '使用状态: 0/未使用 1/已使用',
  `bt` varchar(128) DEFAULT NULL,
  `imei` varchar(128) DEFAULT NULL,
  `sn` varchar(255) DEFAULT NULL,
  `udid` varchar(255) DEFAULT NULL,
  `wifi` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s_machine
-- ----------------------------
INSERT INTO `s_machine` VALUES ('11', null, '', 'ME342LL/A', 'iphone5s', 'iPhone6,2', '0', 'e4:98:d6:55:ba:8b', '', 'DNPLHRHCFNJK', 'bc5beec36e81753f31bce97ec3ec7cf006f3a071', 'e4:98:d6:55:ba:8a', '2020-05-27 16:18:13', '2020-06-09 15:56:59', null);

-- ----------------------------
-- Table structure for s_migrations
-- ----------------------------
DROP TABLE IF EXISTS `s_migrations`;
CREATE TABLE `s_migrations` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of s_migrations
-- ----------------------------
INSERT INTO `s_migrations` VALUES ('20181226023705', 'Role', '2020-05-24 19:41:53', '2020-05-24 19:41:53', '0');
INSERT INTO `s_migrations` VALUES ('20181226023922', 'Node', '2020-05-24 19:41:53', '2020-05-24 19:41:54', '0');
INSERT INTO `s_migrations` VALUES ('20181226024737', 'User', '2020-05-24 19:41:54', '2020-05-24 19:41:54', '0');

-- ----------------------------
-- Table structure for s_node
-- ----------------------------
DROP TABLE IF EXISTS `s_node`;
CREATE TABLE `s_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(255) NOT NULL COMMENT '节点名称',
  `control_name` varchar(255) NOT NULL COMMENT '控制器名',
  `action_name` varchar(255) NOT NULL COMMENT '方法名',
  `is_menu` int(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1不是 2是',
  `type_id` int(11) NOT NULL COMMENT '父级节点id',
  `style` varchar(255) NOT NULL COMMENT '菜单样式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of s_node
-- ----------------------------
INSERT INTO `s_node` VALUES ('1', '用户管理', '#', '#', '2', '0', 'fa fa-users');
INSERT INTO `s_node` VALUES ('2', '管理员管理', 'user', 'index', '2', '1', '');
INSERT INTO `s_node` VALUES ('3', '添加管理员', 'user', 'useradd', '1', '2', '');
INSERT INTO `s_node` VALUES ('4', '编辑管理员', 'user', 'useredit', '1', '2', '');
INSERT INTO `s_node` VALUES ('5', '删除管理员', 'user', 'userdel', '1', '2', '');
INSERT INTO `s_node` VALUES ('6', '角色管理', 'role', 'index', '2', '1', '');
INSERT INTO `s_node` VALUES ('7', '添加角色', 'role', 'roleadd', '1', '6', '');
INSERT INTO `s_node` VALUES ('8', '编辑角色', 'role', 'roleedit', '1', '6', '');
INSERT INTO `s_node` VALUES ('9', '删除角色', 'role', 'roledel', '1', '6', '');
INSERT INTO `s_node` VALUES ('10', '分配权限', 'role', 'giveaccess', '1', '6', '');
INSERT INTO `s_node` VALUES ('11', '系统管理', '#', '#', '2', '0', 'fa fa-desktop');
INSERT INTO `s_node` VALUES ('12', '数据备份/还原', 'data', 'index', '2', '11', '');
INSERT INTO `s_node` VALUES ('13', '备份数据', 'data', 'importdata', '1', '12', '');
INSERT INTO `s_node` VALUES ('14', '还原数据', 'data', 'backdata', '1', '12', '');
INSERT INTO `s_node` VALUES ('15', '节点管理', 'node', 'index', '2', '1', '');
INSERT INTO `s_node` VALUES ('16', '添加节点', 'node', 'nodeadd', '1', '15', '');
INSERT INTO `s_node` VALUES ('17', '编辑节点', 'node', 'nodeedit', '1', '15', '');
INSERT INTO `s_node` VALUES ('18', '删除节点', 'node', 'nodedel', '1', '15', '');
INSERT INTO `s_node` VALUES ('19', '个人中心', '#', '#', '1', '0', '');
INSERT INTO `s_node` VALUES ('20', '编辑信息', 'profile', 'index', '1', '19', '');
INSERT INTO `s_node` VALUES ('21', '编辑头像', 'profile', 'headedit', '1', '19', '');
INSERT INTO `s_node` VALUES ('22', '上传头像', 'profile', 'uploadheade', '1', '19', '');
INSERT INTO `s_node` VALUES ('23', '邮箱管理', '#', '#', '2', '0', 'fa fa-envelope');
INSERT INTO `s_node` VALUES ('24', '邮箱列表', 'email', 'index', '2', '23', '');
INSERT INTO `s_node` VALUES ('25', '添加邮箱', 'email', 'create', '1', '24', '');
INSERT INTO `s_node` VALUES ('26', '删除邮箱', 'email', 'delete', '1', '24', '');
INSERT INTO `s_node` VALUES ('27', '导入邮箱', 'email', 'import_email', '1', '24', '');
INSERT INTO `s_node` VALUES ('28', '编辑邮箱', 'email', 'edit', '1', '24', '');
INSERT INTO `s_node` VALUES ('29', '机器管理', '#', '#', '2', '0', 'fa fa-mobile-phone');
INSERT INTO `s_node` VALUES ('30', '机器列表', 'machine', 'index', '2', '29', '');
INSERT INTO `s_node` VALUES ('31', '添加机器', 'machine', 'create', '1', '30', '');
INSERT INTO `s_node` VALUES ('32', '删除机器', 'machine', 'delete', '1', '30', '');
INSERT INTO `s_node` VALUES ('33', '编辑机器', 'machine', 'edit', '1', '30', '');
INSERT INTO `s_node` VALUES ('34', '导入邮箱', 'machine', 'import_machine', '1', '30', '');
INSERT INTO `s_node` VALUES ('35', '邮箱类型', 'emailType', 'index', '2', '23', '');
INSERT INTO `s_node` VALUES ('36', '添加', 'emailType', 'create', '1', '35', '');
INSERT INTO `s_node` VALUES ('37', '修改', 'emailType', 'update', '1', '35', '');
INSERT INTO `s_node` VALUES ('38', '切换机器状态', 'machine', 'switch_status', '1', '30', '');
INSERT INTO `s_node` VALUES ('39', '切换状态', 'email', 'switch_status', '1', '24', '');
INSERT INTO `s_node` VALUES ('40', '导出邮箱', 'email', 'download_excel', '1', '24', '');
INSERT INTO `s_node` VALUES ('41', '系统配置', 'general', 'index', '2', '11', '');
INSERT INTO `s_node` VALUES ('42', 'IP管理', '#', '#', '2', '0', 'fa fa-link');
INSERT INTO `s_node` VALUES ('43', 'IP列表', 'ipAddress', 'index', '2', '42', '');
INSERT INTO `s_node` VALUES ('44', '状态切换', 'ip_address', 'switch_status', '1', '42', '');
INSERT INTO `s_node` VALUES ('45', '导入IP', 'ipAddress', 'import_ip', '1', '42', '');
INSERT INTO `s_node` VALUES ('46', '任务设备', 'phone', 'index', '2', '29', '');
INSERT INTO `s_node` VALUES ('47', '切换状态', 'phone', 'switch_status', '1', '46', '');
INSERT INTO `s_node` VALUES ('48', '导入', 'phone', 'import_phone', '1', '46', '');
INSERT INTO `s_node` VALUES ('49', '删除', 'phone', 'delete', '1', '46', '');
INSERT INTO `s_node` VALUES ('50', '编辑', 'phone', 'edit', '1', '46', '');

-- ----------------------------
-- Table structure for s_phone
-- ----------------------------
DROP TABLE IF EXISTS `s_phone`;
CREATE TABLE `s_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` int(11) DEFAULT NULL,
  `number` varchar(64) DEFAULT NULL COMMENT '编号',
  `phone_sn` varchar(128) DEFAULT NULL COMMENT '手机sn',
  `status` tinyint(2) DEFAULT '0' COMMENT '运行状态: 0/未使用 1/已使用',
  `job_count` int(8) DEFAULT NULL COMMENT '任务次数',
  `success_job_count` int(8) DEFAULT NULL COMMENT '成功任务次数',
  `program_version` varchar(32) DEFAULT NULL COMMENT '程序版本',
  `udid` varchar(128) DEFAULT NULL,
  `run_steps` varchar(64) DEFAULT NULL COMMENT '运行步骤',
  `des` varchar(255) DEFAULT NULL COMMENT '备注',
  `HWModelStr` varchar(255) DEFAULT NULL,
  `ModelNumber` varchar(128) DEFAULT NULL,
  `PhoneModel` varchar(64) DEFAULT NULL,
  `ProductType` varchar(128) DEFAULT NULL,
  `bt` varchar(128) DEFAULT NULL,
  `imei` varchar(128) DEFAULT NULL,
  `sn` varchar(255) DEFAULT NULL,
  `wifi` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s_phone
-- ----------------------------
INSERT INTO `s_phone` VALUES ('1', null, 'A01', 'DNPS2454HFLQ', '0', null, null, '', '', '', '小林', '', '', '', '', '', '', '', '', '2020-06-15 17:45:54', '2020-06-15 17:45:54', null);

-- ----------------------------
-- Table structure for s_role
-- ----------------------------
DROP TABLE IF EXISTS `s_role`;
CREATE TABLE `s_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_name` varchar(255) NOT NULL COMMENT '角色名称',
  `rule` varchar(255) NOT NULL COMMENT '权限节点数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of s_role
-- ----------------------------
INSERT INTO `s_role` VALUES ('1', '超级管理员', '*');
INSERT INTO `s_role` VALUES ('2', '系统维护员', '1,2,3,4,5,6,7,8,9,10');

-- ----------------------------
-- Table structure for s_user
-- ----------------------------
DROP TABLE IF EXISTS `s_user`;
CREATE TABLE `s_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `head` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `login_times` int(1) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `last_login_ip` varchar(255) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `real_name` varchar(255) NOT NULL COMMENT '真实姓名',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `role_id` int(11) NOT NULL DEFAULT '1' COMMENT '用户角色id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of s_user
-- ----------------------------
INSERT INTO `s_user` VALUES ('1', 'admin', '4b181ed53816327f3bf149ef1dd1c219', '/static/admin/images/profile_small.jpg', '5', '127.0.0.1', '1592211141', 'admin', '1', '1');
