/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : all-mail

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-08-13 19:06:33
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for s_address
-- ----------------------------
DROP TABLE IF EXISTS `s_address`;
CREATE TABLE `s_address` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country` varchar(128) DEFAULT NULL COMMENT '国家',
  `province` varchar(128) DEFAULT NULL COMMENT '省份',
  `city` varchar(128) DEFAULT NULL COMMENT '城市',
  `street_one` varchar(255) DEFAULT NULL COMMENT '街道1',
  `street_two` varchar(255) DEFAULT NULL COMMENT '街道1',
  `street_three` varchar(255) DEFAULT NULL COMMENT '街道1',
  `postal_code` varchar(64) DEFAULT NULL COMMENT '邮编',
  `use_status` tinyint(2) DEFAULT '0' COMMENT '使用状态:0/未使用 1/已使用',
  `used_count` int(8) DEFAULT '0' COMMENT '使用次数',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22535 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for s_apple
-- ----------------------------
DROP TABLE IF EXISTS `s_apple`;
CREATE TABLE `s_apple` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `apple_account` varchar(128) NOT NULL COMMENT '账号',
  `apple_pass` varchar(64) DEFAULT NULL COMMENT '密码',
  `use_status` tinyint(2) DEFAULT '0' COMMENT '使用状态:0/未使用 1/已使用',
  `used_count` int(8) DEFAULT '0' COMMENT '使用次数',
  `used_time` datetime DEFAULT NULL COMMENT '最后一次使用时间',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_account` (`apple_account`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for s_channel
-- ----------------------------
DROP TABLE IF EXISTS `s_channel`;
CREATE TABLE `s_channel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `channel_name` varchar(128) DEFAULT NULL COMMENT '通道名称',
  `process_name` varchar(128) DEFAULT NULL COMMENT '进程名',
  `bid` varchar(32) DEFAULT '0' COMMENT '进程标识',
  `remark` varchar(128) DEFAULT NULL COMMENT '备注',
  `rank` tinyint(3) DEFAULT '1' COMMENT '排序',
  `used_count` int(12) DEFAULT '0' COMMENT '使用次数',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_channel_name` (`channel_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for s_check_apple
-- ----------------------------
DROP TABLE IF EXISTS `s_check_apple`;
CREATE TABLE `s_check_apple` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `apple_account` varchar(128) NOT NULL COMMENT '账号',
  `apple_pass` varchar(64) DEFAULT NULL COMMENT '密码',
  `use_status` tinyint(2) DEFAULT '0' COMMENT '使用状态:0/未使用 1/已使用',
  `check_status` tinyint(2) DEFAULT '2' COMMENT '过检状态:0/失败 1/成功 2/未过检',
  `fail_reason` varchar(128) DEFAULT NULL COMMENT '失败原因',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_account` (`apple_account`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for s_email
-- ----------------------------
DROP TABLE IF EXISTS `s_email`;
CREATE TABLE `s_email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_type_id` int(11) NOT NULL DEFAULT '1' COMMENT '邮箱类型',
  `phone_id` int(11) DEFAULT NULL COMMENT '手机id',
  `machine_id` int(11) DEFAULT NULL COMMENT '机器id',
  `channel_id` int(11) DEFAULT NULL COMMENT '注册通道id',
  `channel_name` varchar(128) DEFAULT NULL COMMENT '频道名',
  `email_name` varchar(128) NOT NULL COMMENT '邮箱名',
  `udid` varchar(128) DEFAULT NULL COMMENT 'udid',
  `phone_sn` varchar(128) DEFAULT NULL,
  `email_password` varchar(255) DEFAULT NULL,
  `reg_status` tinyint(4) DEFAULT '2' COMMENT '注册状态: 2/未注册 0/失败 1/成功',
  `use_status` tinyint(2) DEFAULT '0' COMMENT '使用状态:0/未使用 1/已使用 2/停止使用',
  `is_get` tinyint(2) DEFAULT '0' COMMENT '是否已导出: 0/未 1/已 导出',
  `fail_msg` varchar(128) DEFAULT NULL COMMENT '失败原因',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq_email` (`email_type_id`,`email_name`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=5002 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for s_email_type
-- ----------------------------
DROP TABLE IF EXISTS `s_email_type`;
CREATE TABLE `s_email_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) DEFAULT NULL COMMENT '邮箱名',
  `connection_method` tinyint(2) DEFAULT '1' COMMENT '取件方式:1/imap 2/pop3',
  `imapsvr` varchar(128) DEFAULT NULL COMMENT 'imap地址',
  `imap_port` int(8) DEFAULT '993' COMMENT 'imap端口',
  `pop3svr` varchar(128) DEFAULT NULL COMMENT '接收服务器',
  `pop3_port` int(8) DEFAULT '465' COMMENT 'pop3端口号',
  `smtpsvr` varchar(128) DEFAULT NULL COMMENT '发送服务器',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `ip_unique` (`ip`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

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
  `udid` varchar(128) DEFAULT NULL,
  `wifi` varchar(255) DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `udid_unique` (`udid`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;

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
-- Table structure for s_phone
-- ----------------------------
DROP TABLE IF EXISTS `s_phone`;
CREATE TABLE `s_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_id` int(11) DEFAULT NULL,
  `number` varchar(64) DEFAULT NULL COMMENT '编号',
  `account_name` varchar(128) DEFAULT NULL COMMENT '邮箱名称',
  `account_pass` varchar(64) DEFAULT NULL COMMENT '账号密码',
  `phone_sn` varchar(128) DEFAULT NULL COMMENT '手机sn',
  `phone_num` varchar(32) DEFAULT NULL COMMENT '手机号',
  `job_type` tinyint(2) DEFAULT '1' COMMENT '工作类型:1/接码 2/注册 3/激活 4/双重',
  `status` tinyint(2) DEFAULT '0' COMMENT '运行状态: 0/未运行 1/在运行 2/停止运行',
  `test_status` tinyint(2) DEFAULT '0' COMMENT '是否为测试账号:0/不是 1/是',
  `failed_job_count` int(8) DEFAULT '0' COMMENT '失败任务次数',
  `success_job_count` int(8) DEFAULT '0' COMMENT '成功任务次数',
  `program_version` varchar(32) DEFAULT NULL COMMENT '程序版本',
  `udid` varchar(128) DEFAULT NULL,
  `run_steps` varchar(64) DEFAULT NULL COMMENT '运行步骤',
  `des` varchar(255) DEFAULT NULL COMMENT '备注',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone_sn_unique` (`phone_sn`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for s_reg_apple
-- ----------------------------
DROP TABLE IF EXISTS `s_reg_apple`;
CREATE TABLE `s_reg_apple` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `apple_account` varchar(128) NOT NULL COMMENT '账号',
  `apple_pass` varchar(64) DEFAULT NULL COMMENT '密码',
  `use_status` tinyint(2) DEFAULT '0' COMMENT '使用状态:0/未使用 1/已使用',
  `reg_status` tinyint(2) DEFAULT '2' COMMENT '激活状态:0/失败 1/成功 2/未使用',
  `fail_reason` varchar(128) DEFAULT NULL COMMENT '失败原因',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_account` (`apple_account`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

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
-- Table structure for s_sms
-- ----------------------------
DROP TABLE IF EXISTS `s_sms`;
CREATE TABLE `s_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_phone_id` int(11) NOT NULL COMMENT '短信设备id',
  `token` varchar(64) NOT NULL COMMENT '任务token',
  `receiving_phone_sn` varchar(64) DEFAULT NULL COMMENT 'sms手机sn',
  `receiving_phone_num` varchar(32) DEFAULT NULL COMMENT '接码手机编号',
  `get_phone_num` varchar(32) DEFAULT NULL COMMENT '获取sms手机号',
  `sms_content` varchar(128) DEFAULT NULL,
  `code` varchar(16) DEFAULT NULL COMMENT '验证码',
  `receiving_status` tinyint(2) DEFAULT NULL COMMENT '接码状态:null/未知 0/失败 1/成功 2/下发',
  `fail_reason` varchar(255) DEFAULT NULL COMMENT '失败原因',
  `upload_sms_time` datetime DEFAULT NULL COMMENT '上传时间',
  `sending_sms_time` datetime DEFAULT NULL COMMENT '接收时间',
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Table structure for s_sms_phone
-- ----------------------------
DROP TABLE IF EXISTS `s_sms_phone`;
CREATE TABLE `s_sms_phone` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phone_num` varchar(32) NOT NULL COMMENT '手机号',
  `device_num` varchar(32) NOT NULL COMMENT '设备编号',
  `phone_sn` varchar(64) NOT NULL COMMENT '手机sn',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '设备状态: 0/空闲 1/接码中 2/异常 3/禁用',
  `remarks` varchar(128) DEFAULT NULL COMMENT '备注',
  `get_phone_count` int(10) DEFAULT '0' COMMENT '请求设备数',
  `get_sms_count` int(10) DEFAULT '0' COMMENT '下发短信数',
  `received_sms_count` int(10) DEFAULT '0' COMMENT '上传短信数',
  `success_sms_count` int(10) DEFAULT '0' COMMENT '成功上传短信数',
  `last_get_time` datetime DEFAULT NULL,
  `create_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  `delete_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_phone` (`phone_num`,`device_num`) USING BTREE,
  UNIQUE KEY `unique_phone_sn` (`phone_sn`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

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
-- Table structure for s_versions
-- ----------------------------
DROP TABLE IF EXISTS `s_versions`;
CREATE TABLE `s_versions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `file_name` varchar(64) NOT NULL COMMENT '文件名称',
  `file_versions` varchar(32) DEFAULT NULL COMMENT '版本号',
  `file_url` varchar(128) DEFAULT NULL COMMENT '文件路径',
  `file_md5` varchar(128) DEFAULT NULL COMMENT '文件md5值',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `delete_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
