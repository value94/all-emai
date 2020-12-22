# snake
thinkphp5做的通用系统改后台

目前完成的功能有:

1. 后台管理员的增删改查
2. 角色的增删改查
3. 权限的分配
4. 数据库的备份与还原

# 使用方法
1. 使用`composer`工具建立项目
    `composer create-project xiaosumay/snake [your_project_name]`
2. 开始操作 `php think keygen`
3. 修改数据库配置参数
4. 生成表 `php think  migrate:run`
5. 生成默认数据 `php think seed:run`

管理员是: admin
密码是: admin

### 2018.12.24
1、已经升级到thinkphp5.1.23


ALTER TABLE `s_machine`
ADD COLUMN `ecid`  varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL AFTER `udid`,
ADD COLUMN `use_count`  int(11) NULL COMMENT '使用次数' AFTER `wifi`,
ADD COLUMN `device_cert`  text NULL COMMENT '设备证书' AFTER `use_count`;
ALTER TABLE `s_machine`
MODIFY COLUMN `use_count`  int(11) NULL DEFAULT 0 COMMENT '使用次数' AFTER `wifi`;

ALTER TABLE `s_machine`
ADD COLUMN `bluetooth`  varchar(128) NULL COMMENT '蓝牙' AFTER `wifi`;

