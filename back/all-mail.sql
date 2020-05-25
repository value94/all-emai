/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : all-mail

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-05-25 16:55:28
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for s_email
-- ----------------------------
DROP TABLE IF EXISTS `s_email`;
CREATE TABLE `s_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `machine_id` int(11) DEFAULT NULL COMMENT '机器id',
  `email_name` varchar(128) NOT NULL COMMENT '邮箱名',
  `email_password` varchar(255) DEFAULT NULL,
  `use_status` tinyint(2) DEFAULT '0' COMMENT '使用状态:0/未使用 1/已使用',
  `imapsvr` varchar(128) DEFAULT NULL COMMENT 'imap地址',
  `pop3svr` varchar(128) DEFAULT NULL COMMENT '接收服务器',
  `smtpsvr` varchar(128) DEFAULT NULL COMMENT '发送服务器',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s_email
-- ----------------------------
INSERT INTO `s_email` VALUES ('1', null, 'djeimibrainina1988@rambler.ru', 'Oy9urh17', '1', 'imap.rambler.ru', 'pop.rambler.ru', 'smtp.rambler.ru', '2020-05-25 09:25:42', '2020-05-25 09:45:37', null);

-- ----------------------------
-- Table structure for s_machine
-- ----------------------------
DROP TABLE IF EXISTS `s_machine`;
CREATE TABLE `s_machine` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` int(11) DEFAULT NULL,
  `HWModelStr` varchar(255) DEFAULT NULL,
  `ModelNumber` varchar(128) DEFAULT NULL,
  `ProductType` varchar(128) DEFAULT NULL,
  `bt` varchar(128) DEFAULT NULL,
  `imei` varchar(128) DEFAULT NULL,
  `sn` varchar(255) DEFAULT NULL,
  `udid` varchar(255) DEFAULT NULL,
  `wifi` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of s_machine
-- ----------------------------

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
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

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
INSERT INTO `s_user` VALUES ('1', 'admin', '4b181ed53816327f3bf149ef1dd1c219', '/static/admin/images/profile_small.jpg', '1', '127.0.0.1', '1590321271', 'admin', '1', '1');
