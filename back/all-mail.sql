/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : all-mail

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-06-02 13:14:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for s_email
-- ----------------------------
DROP TABLE IF EXISTS `s_email`;
CREATE TABLE `s_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_type_id` int(11) NOT NULL COMMENT '邮箱类型',
  `machine_id` int(11) DEFAULT NULL COMMENT '机器id',
  `email_name` varchar(128) NOT NULL COMMENT '邮箱名',
  `email_password` varchar(255) DEFAULT NULL,
  `reg_status` tinyint(4) DEFAULT '2' COMMENT '注册状态: 2/未注册 0/失败 1/成功',
  `use_status` tinyint(2) DEFAULT '0' COMMENT '使用状态:0/未使用 1/已使用',
  `fail_msg` varchar(128) DEFAULT NULL COMMENT '失败原因',
  `imapsvr` varchar(128) DEFAULT NULL COMMENT 'imap地址',
  `pop3svr` varchar(128) DEFAULT NULL COMMENT '接收服务器',
  `smtpsvr` varchar(128) DEFAULT NULL COMMENT '发送服务器',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s_email
-- ----------------------------
INSERT INTO `s_email` VALUES ('1', '0', null, 'djeimibrainina1988@rambler.ru', 'Oy9urh17', '1', '1', '失败了', 'imap.rambler.ru', 'pop.rambler.ru', 'smtp.rambler.ru', '2020-05-25 09:25:42', '2020-05-29 16:47:48', null);
INSERT INTO `s_email` VALUES ('4', '1', null, 'djeimibrainina1989@rambler.ru', 'Oy9urh17', '0', '0', null, 'imap.rambler.ru', 'pop.rambler.ru', 'smtp.rambler.ru', '2020-05-27 17:10:41', '2020-05-28 13:28:21', null);

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
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s_machine
-- ----------------------------
INSERT INTO `s_machine` VALUES ('11', null, null, 'ME342LL/A', 'iphone5s', 'iPhone6,2', '0', 'e4:98:d6:55:ba:8b', null, 'DNPLHRHCFNJK', 'bc5beec36e81753f31bce97ec3ec7cf006f3a071', 'e4:98:d6:55:ba:8a', '2020-05-27 16:18:13', '2020-05-28 21:41:27', null);
INSERT INTO `s_machine` VALUES ('12', null, null, 'ME306LL/A', 'iphone5s', 'iPhone6,2', '0', 'f8:27:93:2e:b5:06', null, 'DNPLHRCHFF9V', '79ef9d61290fae584354dd230c9eee787e2d8437', 'f8:27:93:2e:b5:05', '2020-05-27 16:18:13', '2020-05-28 21:24:29', null);
INSERT INTO `s_machine` VALUES ('13', null, null, 'ME306LL/A', 'iphone5s', 'iPhone6,2', '0', '04:db:56:1a:0a:99', null, 'DNPLHSZDFF9V', 'd8bd46bb39e07e91b4a6974ce1ec43e79db27ae8', '04:db:56:1a:0a:98', '2020-05-27 16:18:13', '2020-05-28 21:39:59', null);
INSERT INTO `s_machine` VALUES ('14', null, null, 'ME415RU/A', 'iphone5s', 'iPhone6,2', '0', '28:e1:4c:cf:92:34', null, 'DNPLHSZTFFG9', 'b502a335fd77ae2047afc33de1dcedd08e7de23d', '28:e1:4c:cf:91:d9', '2020-05-27 16:18:13', '2020-05-27 16:18:13', null);
INSERT INTO `s_machine` VALUES ('15', '1', null, 'ME342LL/A', 'iphone5s', 'iPhone6,2', '1', 'e4:98:d6:4d:95:1f', null, 'DNPLHT5EFNJK', 'b4606ad8b93d253f11003effe0b2de39854b1923', 'e4:98:d6:4d:95:1e', '2020-05-27 16:18:13', '2020-05-29 16:47:48', null);
INSERT INTO `s_machine` VALUES ('16', null, null, 'ME352LL/A', 'iphone5s', 'iPhone6,2', '0', 'f8:27:93:18:7b:69', null, 'DNPLJ0DLFFDR', 'fae5afc40bc245dc29bec8dc0e4831204d521fef', 'f8:27:93:18:7b:68', '2020-05-27 16:18:13', '2020-05-27 16:18:13', null);
INSERT INTO `s_machine` VALUES ('17', null, null, 'ME415RU/A', 'iphone5s', 'iPhone6,2', '0', '28:e1:4c:d3:99:04', null, 'DNPLHSWSFFG9', 'd9c92d9ae7e70cf8b611f8ba9a74504c959e8fa7', '28:e1:4c:d3:99:03', '2020-05-27 16:18:13', '2020-05-27 16:18:13', null);
INSERT INTO `s_machine` VALUES ('18', null, null, 'ME342LL/A', 'iphone5s', 'iPhone6,2', '0', 'e4:98:d6:58:06:a9', null, 'DNPLHRDBFNJK', '7882fd2064497faafd311fb7db7bc12d99cd6ae6', 'e4:98:d6:58:06:a8', '2020-05-27 16:18:13', '2020-05-27 16:18:13', null);
INSERT INTO `s_machine` VALUES ('19', null, null, 'ME415LP/A', 'iphone5s', 'iPhone6,2', '0', '28:e1:4c:d1:57:13', null, 'DNPLHRRCFFG9', '52a017837d7dcc6763312aa70fee1e2c06f74c98', '28:e1:4c:d1:57:12', '2020-05-27 16:18:13', '2020-05-28 13:14:01', null);
INSERT INTO `s_machine` VALUES ('20', null, null, 'ME415RU/A', 'iphone5s', 'iPhone6,2', '0', '28:e1:4c:d3:d6:82', null, 'DNPLHSWXFFG9', '3c33dd910880510ac56c21393b8371fc55b431e0', '28:e1:4c:d3:d6:63', '2020-05-27 16:18:13', '2020-05-28 13:21:11', null);

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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8;

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
INSERT INTO `s_user` VALUES ('1', 'admin', '4b181ed53816327f3bf149ef1dd1c219', '/static/admin/images/profile_small.jpg', '2', '127.0.0.1', '1590566704', 'admin', '1', '1');
