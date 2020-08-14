/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : all-mail

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-08-13 19:09:57
*/

SET FOREIGN_KEY_CHECKS=0;

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
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

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
INSERT INTO `s_node` VALUES ('51', '添加', 'phone', 'create', '1', '46', '');
INSERT INTO `s_node` VALUES ('52', '地址管理', '#', '#', '2', '0', 'fa fa-map-signs');
INSERT INTO `s_node` VALUES ('53', '地址列表', 'address', 'index', '2', '52', '');
INSERT INTO `s_node` VALUES ('54', '导入', 'address', 'import', '1', '53', '');
INSERT INTO `s_node` VALUES ('55', '添加', 'address', 'create', '1', '53', '');
INSERT INTO `s_node` VALUES ('56', '修改', 'address', 'edit', '1', '53', '');
INSERT INTO `s_node` VALUES ('57', '删除', 'address', 'delete', '1', '53', '');
INSERT INTO `s_node` VALUES ('58', '切换', 'address', 'switch_status', '1', '53', '');
INSERT INTO `s_node` VALUES ('59', 'Apple账号管理', '#', '#', '2', '0', 'fa fa-apple');
INSERT INTO `s_node` VALUES ('60', '备用账号列表', 'apple', 'index', '2', '59', '');
INSERT INTO `s_node` VALUES ('61', '添加', 'apple', 'create', '1', '60', '');
INSERT INTO `s_node` VALUES ('62', '导入', 'apple', 'import', '1', '60', '');
INSERT INTO `s_node` VALUES ('63', '删除', 'apple', 'delete', '1', '60', '');
INSERT INTO `s_node` VALUES ('64', '编辑', 'apple', 'edit', '1', '60', '');
INSERT INTO `s_node` VALUES ('65', '切换状态', 'apple', 'switch_status', '1', '60', '');
INSERT INTO `s_node` VALUES ('66', '待激活账号', 'regApple', 'index', '2', '59', '');
INSERT INTO `s_node` VALUES ('67', '添加', 'regApple', 'create', '1', '66', '');
INSERT INTO `s_node` VALUES ('68', '导入', 'regApple', 'import', '1', '66', '');
INSERT INTO `s_node` VALUES ('69', '删除', 'regApple', 'delete', '1', '66', '');
INSERT INTO `s_node` VALUES ('70', '编辑', 'regApple', 'edit', '1', '66', '');
INSERT INTO `s_node` VALUES ('71', '切换状态', 'regApple', 'switch_status', '1', '66', '');
INSERT INTO `s_node` VALUES ('72', '导出', 'regApple', 'download_excel', '1', '66', '');
INSERT INTO `s_node` VALUES ('73', '注册通道', 'regChannel', 'index', '2', '11', '');
INSERT INTO `s_node` VALUES ('74', '添加', 'regChannel', 'create', '1', '73', '');
INSERT INTO `s_node` VALUES ('75', '编辑', 'regChannel', 'update', '1', '73', '');
INSERT INTO `s_node` VALUES ('76', '删除', 'regChannel', 'delete', '1', '73', '');
INSERT INTO `s_node` VALUES ('77', '版本管理', '#', '#', '2', '0', 'fa fa-tree');
INSERT INTO `s_node` VALUES ('78', '版本管理', 'versions', 'index', '2', '77', '');
INSERT INTO `s_node` VALUES ('79', '添加', 'versions', 'versionsadd', '1', '78', '');
INSERT INTO `s_node` VALUES ('80', '编辑', 'versions', 'versionsedit', '1', '78', '');
INSERT INTO `s_node` VALUES ('81', '删除', 'versions', 'versionsDel', '1', '78', '');
INSERT INTO `s_node` VALUES ('82', '短信管理', '#', '#', '2', '0', 'fa fa-envelope');
INSERT INTO `s_node` VALUES ('83', '短信设备', 'smsPhone', 'index', '2', '82', '');
INSERT INTO `s_node` VALUES ('84', '短信管理', 'sms', 'index', '2', '82', '');
INSERT INTO `s_node` VALUES ('85', '添加', 'smsPhone', 'create', '1', '83', '');
INSERT INTO `s_node` VALUES ('86', '删除', 'smsPhone', 'delete', '1', '83', '');
INSERT INTO `s_node` VALUES ('87', '编辑', 'smsPhone', 'edit', '1', '83', '');
INSERT INTO `s_node` VALUES ('88', '导入', 'smsPhone', 'import', '1', '83', '');
INSERT INTO `s_node` VALUES ('89', '切换状态', 'smsPhone', 'switch_status', '1', '83', '');
INSERT INTO `s_node` VALUES ('90', '删除', 'sms', 'delete', '1', '84', '');
INSERT INTO `s_node` VALUES ('91', '导出', 'smsPhone', 'download_excel', '1', '83', '');
INSERT INTO `s_node` VALUES ('92', '待过检账号', 'checkApple', 'index', '2', '59', '');
INSERT INTO `s_node` VALUES ('93', '添加', 'checkApple', 'create', '1', '92', '');
INSERT INTO `s_node` VALUES ('94', '删除', 'checkApple', 'delete', '1', '92', '');
INSERT INTO `s_node` VALUES ('95', '编辑', 'checkApple', 'edit', '1', '92', '');
INSERT INTO `s_node` VALUES ('96', '导入', 'checkApple', 'import', '1', '92', '');
INSERT INTO `s_node` VALUES ('97', '导出', 'checkApple', 'download_excel', '1', '92', '');
INSERT INTO `s_node` VALUES ('98', '切换状态', 'checkApple', 'switch_status', '1', '92', '');

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
INSERT INTO `s_user` VALUES ('1', 'admin', '4b181ed53816327f3bf149ef1dd1c219', '/static/admin/images/profile_small.jpg', '20', '127.0.0.1', '1596786354', 'admin', '1', '1');
