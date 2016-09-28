/*
Navicat MySQL Data Transfer

Source Server         : 192.168.70.168
Source Server Version : 50710
Source Host           : localhost:3306
Source Database       : oa

Target Server Type    : MYSQL
Target Server Version : 50710
File Encoding         : 65001

Date: 2016-09-28 15:54:23
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `oa_apply`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply`;
CREATE TABLE `oa_apply` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '申请人id',
  `for_user_ids` text NOT NULL COMMENT '给哪些用户申请的用户id',
  `title` varchar(100) NOT NULL COMMENT '主题',
  `type` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '表单类型',
  `create_time` datetime NOT NULL COMMENT '申请时间',
  `status` enum('进行中','完结') NOT NULL,
  `judgestream` text NOT NULL COMMENT '审核流程',
  `detailid` int(11) NOT NULL COMMENT '详情id',
  PRIMARY KEY (`id`),
  KEY `user` (`user_id`) USING HASH,
  KEY `time` (`create_time`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='申请主表';

-- ----------------------------
-- Records of oa_apply
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_apply_addwork`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply_addwork`;
CREATE TABLE `oa_apply_addwork` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL COMMENT '申请单id',
  `type` tinyint(4) NOT NULL COMMENT '加班类型',
  `isallday` enum('否','是') NOT NULL COMMENT '是否整天',
  `starttime` datetime NOT NULL COMMENT '开始时间',
  `endtime` datetime NOT NULL COMMENT '结束时间',
  `reason` varchar(100) NOT NULL COMMENT '理由',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_apply_addwork
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_apply_adjustwordday`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply_adjustwordday`;
CREATE TABLE `oa_apply_adjustwordday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL,
  `olddate` date NOT NULL COMMENT '调前时间',
  `newdate` date NOT NULL COMMENT '调后时间',
  `type` enum('下午','上午','全天') NOT NULL,
  `reason` varchar(100) NOT NULL COMMENT '理由',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='休息日调整';

-- ----------------------------
-- Records of oa_apply_adjustwordday
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_apply_annex`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply_annex`;
CREATE TABLE `oa_apply_annex` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(80) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `app_id` int(11) NOT NULL COMMENT '申请单id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='申请附件';

-- ----------------------------
-- Records of oa_apply_annex
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_apply_car`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply_car`;
CREATE TABLE `oa_apply_car` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL COMMENT '申请单id',
  `hecompany` varchar(100) NOT NULL COMMENT '出访单位',
  `mannum` int(11) NOT NULL COMMENT '乘车人数',
  `starttime` datetime NOT NULL COMMENT '出发时间',
  `backtime` datetime NOT NULL COMMENT '返乘时间',
  `destplace` varchar(100) NOT NULL COMMENT '详细地址',
  `reason` varchar(100) DEFAULT NULL COMMENT '出差事由',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用车子表';

-- ----------------------------
-- Records of oa_apply_car
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_apply_out`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply_out`;
CREATE TABLE `oa_apply_out` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `outplace` varchar(50) NOT NULL COMMENT '出差地点',
  `transmethod` tinyint(4) NOT NULL COMMENT '交通方式',
  `isallday` enum('否','是') NOT NULL COMMENT '是否整天',
  `starttime` datetime NOT NULL COMMENT '开始时间',
  `endtime` datetime NOT NULL COMMENT '结束时间',
  `reason` varchar(100) NOT NULL COMMENT '理由',
  `attatchid` text NOT NULL COMMENT '附件id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_apply_out
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_apply_process`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply_process`;
CREATE TABLE `oa_apply_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `operator` int(11) unsigned NOT NULL COMMENT '审批人id',
  `apply_id` int(11) NOT NULL COMMENT '申请文件id',
  `node_id` int(11) NOT NULL COMMENT '流程结点id',
  `status` tinyint(1) NOT NULL,
  `reason` varchar(100) NOT NULL COMMENT '理由',
  PRIMARY KEY (`id`),
  KEY `app_id` (`apply_id`) USING HASH
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='所有审批进程表';

-- ----------------------------
-- Records of oa_apply_process
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_apply_punch`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply_punch`;
CREATE TABLE `oa_apply_punch` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(10) DEFAULT NULL COMMENT '申请单id',
  `date` date NOT NULL COMMENT '未打卡日期',
  `time` time NOT NULL COMMENT '未打卡时间',
  `reason` varchar(60) DEFAULT NULL COMMENT '理由',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='未打卡子表';

-- ----------------------------
-- Records of oa_apply_punch
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_apply_rest`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply_rest`;
CREATE TABLE `oa_apply_rest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned DEFAULT NULL COMMENT '申请单主表id',
  `type` tinyint(1) NOT NULL COMMENT '请假类型',
  `isannex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有附件0没有1有',
  `isallday` enum('否','是') NOT NULL COMMENT '是否整天',
  `starttime` datetime NOT NULL COMMENT '开始时间',
  `endtime` datetime NOT NULL COMMENT '结束时间',
  `reason` varchar(100) NOT NULL COMMENT '理由',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='调休子表';

-- ----------------------------
-- Records of oa_apply_rest
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_apply_travel`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply_travel`;
CREATE TABLE `oa_apply_travel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '申请单主表id',
  `startplace` varchar(255) NOT NULL COMMENT '出发地',
  `date` date NOT NULL COMMENT '日期',
  `transportationfee` float NOT NULL COMMENT '车船费用',
  `transportationmethod` enum('轿车','船','火车','飞机') NOT NULL,
  `bedfee` float NOT NULL COMMENT '住宿费用(rmb)',
  `beddetail` varchar(200) NOT NULL COMMENT '住宿详细',
  `subsidymember` int(11) NOT NULL COMMENT '补贴人数',
  `subsidydays` int(11) NOT NULL COMMENT '补贴天数',
  `subsidylevel` varchar(50) NOT NULL COMMENT '补贴标准',
  `subsidymoney` float NOT NULL COMMENT '补贴金额',
  `otherfee` float NOT NULL COMMENT '杂费',
  `invoicenum` tinyint(2) NOT NULL DEFAULT '0' COMMENT '单据张数',
  `subsidyamount` float NOT NULL COMMENT '合计(rmb)',
  `applyfee` float NOT NULL COMMENT '应报销金额(rmb)',
  `borrowfee` float NOT NULL COMMENT '原借支旅费(rmb)',
  `retrievefee` float NOT NULL COMMENT '退补现金(rmb)',
  `amount` float NOT NULL COMMENT '总合计(rmb)',
  `reason` varchar(100) NOT NULL COMMENT '理由',
  `attach` varchar(100) NOT NULL,
  `fast` enum('加急','一般','不紧急') DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `appid` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='差旅';

-- ----------------------------
-- Records of oa_apply_travel
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_apply_turnover`
-- ----------------------------
DROP TABLE IF EXISTS `oa_apply_turnover`;
CREATE TABLE `oa_apply_turnover` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `app_id` int(10) unsigned NOT NULL COMMENT '申请单主表id',
  `godate` date NOT NULL COMMENT '预订离职日期',
  `reason` varchar(100) NOT NULL COMMENT '离职原因',
  `getpaymethod` enum('转入工资账号','现金') NOT NULL COMMENT '工资领取方式',
  `checkpay` enum('不需要','需要') NOT NULL COMMENT '结算的离职工资是否需要确认',
  `nogoherereason` varchar(100) NOT NULL COMMENT '本人无法回公司确认工资原因',
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='离职申请';

-- ----------------------------
-- Records of oa_apply_turnover
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_article`
-- ----------------------------
DROP TABLE IF EXISTS `oa_article`;
CREATE TABLE `oa_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `create_user_id` int(11) NOT NULL COMMENT '发话题人id',
  `create_time` datetime NOT NULL,
  `o_id` int(11) NOT NULL COMMENT '圈子id',
  `title` varchar(100) NOT NULL COMMENT '话题标题',
  `content` text NOT NULL COMMENT '话题内容',
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT '点击量',
  `approve` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点赞',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='文章表';

-- ----------------------------
-- Records of oa_article
-- ----------------------------
INSERT INTO `oa_article` VALUES ('1', '1', '2016-09-27 11:00:47', '1', '无事吐槽，闲着也是闲着', '请让我友好的吐槽一下！我记得是5月1号！我和几个朋友聚会--就是那种几个朋友找个咖啡厅坐着闲聊..其中一对情侣--就不停的吐槽2人学车被坑的事.说是熟人驾', '0', '0');
INSERT INTO `oa_article` VALUES ('2', '1', '2016-09-06 11:01:52', '1', '随性的记录生活的点点滴滴', '今天我随性的记录了生活的点点滴滴。', '0', '0');

-- ----------------------------
-- Table structure for `oa_article_folder`
-- ----------------------------
DROP TABLE IF EXISTS `oa_article_folder`;
CREATE TABLE `oa_article_folder` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL COMMENT '回复内容',
  `article_id` int(11) NOT NULL COMMENT '话题id',
  `infloor` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '在话题内的楼层--帖子内楼数',
  `user_id` int(11) NOT NULL COMMENT '发表此回复的人的id',
  `reply_floor` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '回复楼层---关联本表id',
  `creat_time` datetime NOT NULL COMMENT '回复时间',
  `hits` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `floor` (`article_id`,`infloor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='话题盖楼';

-- ----------------------------
-- Records of oa_article_folder
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_eat`
-- ----------------------------
DROP TABLE IF EXISTS `oa_eat`;
CREATE TABLE `oa_eat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1午餐2晚餐',
  `date` date NOT NULL COMMENT '用餐日期',
  `state` tinyint(4) NOT NULL DEFAULT '1' COMMENT '状态1正常0取消',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_eat
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_meet`
-- ----------------------------
DROP TABLE IF EXISTS `oa_meet`;
CREATE TABLE `oa_meet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `room_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL COMMENT '会议主题',
  `content` text NOT NULL COMMENT '会议内容',
  `start_time` datetime NOT NULL COMMENT '开始时间',
  `end_time` datetime NOT NULL COMMENT '结束时间',
  `create_time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会议室使用申请';

-- ----------------------------
-- Records of oa_meet
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_menu`
-- ----------------------------
DROP TABLE IF EXISTS `oa_menu`;
CREATE TABLE `oa_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `father_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父级id--0系统分类',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1正常0停用',
  `name` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='基础菜单分类';

-- ----------------------------
-- Records of oa_menu
-- ----------------------------
INSERT INTO `oa_menu` VALUES ('1', '0', '1', '职位');
INSERT INTO `oa_menu` VALUES ('2', '0', '1', '部门');
INSERT INTO `oa_menu` VALUES ('3', '0', '1', '公司');
INSERT INTO `oa_menu` VALUES ('4', '1', '1', 'php工程师');
INSERT INTO `oa_menu` VALUES ('5', '1', '1', 'Java工程师');
INSERT INTO `oa_menu` VALUES ('6', '1', '1', '.Net工程师');
INSERT INTO `oa_menu` VALUES ('7', '1', '1', '数据分析师');
INSERT INTO `oa_menu` VALUES ('8', '0', '1', '会议室');

-- ----------------------------
-- Table structure for `oa_message`
-- ----------------------------
DROP TABLE IF EXISTS `oa_message`;
CREATE TABLE `oa_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `content` text COMMENT '内容',
  `type` tinyint(4) NOT NULL COMMENT '消息类型',
  `status` tinyint(1) DEFAULT '0',
  `create_time` datetime NOT NULL COMMENT '消息时间',
  `title` varchar(100) DEFAULT NULL COMMENT '标题',
  PRIMARY KEY (`id`),
  KEY `useid` (`user_id`) USING HASH
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='消息表';

-- ----------------------------
-- Records of oa_message
-- ----------------------------
INSERT INTO `oa_message` VALUES ('1', '1', null, '1', '0', '2016-09-26 16:11:23', '开会讨论本周销售业绩');
INSERT INTO `oa_message` VALUES ('2', '1', null, '2', '0', '2016-09-26 16:55:16', '你的好友发布了一个新的帖子');
INSERT INTO `oa_message` VALUES ('3', '1', null, '3', '0', '2016-09-28 16:55:33', '张三休假申请');
INSERT INTO `oa_message` VALUES ('4', '1', null, '4', '0', '2016-09-20 16:56:04', null);
INSERT INTO `oa_message` VALUES ('5', '1', null, '5', '0', '2016-09-21 16:56:19', '明天需要去见客户');

-- ----------------------------
-- Table structure for `oa_process_template`
-- ----------------------------
DROP TABLE IF EXISTS `oa_process_template`;
CREATE TABLE `oa_process_template` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL DEFAULT '0' COMMENT '0代表为系统模板',
  `judgestream` text NOT NULL COMMENT '预设审批流程',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '模板状态1启用0停用',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统审批流程';

-- ----------------------------
-- Records of oa_process_template
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_socialcircle`
-- ----------------------------
DROP TABLE IF EXISTS `oa_socialcircle`;
CREATE TABLE `oa_socialcircle` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '圈子名称',
  `create_user_id` int(11) NOT NULL COMMENT '创建人id',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `membercount` int(10) NOT NULL DEFAULT '1' COMMENT '人数',
  `administrator` varchar(255) DEFAULT NULL COMMENT '管理员-可多个-json格式存储',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '圈子是否开放0开放1需审核2不可见',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='圈子\r\n';

-- ----------------------------
-- Records of oa_socialcircle
-- ----------------------------
INSERT INTO `oa_socialcircle` VALUES ('1', '相亲', '1', '2016-09-13 10:53:17', '1', null, '0');
INSERT INTO `oa_socialcircle` VALUES ('2', '二手', '1', '2016-09-20 10:53:29', '1', null, '0');

-- ----------------------------
-- Table structure for `oa_user`
-- ----------------------------
DROP TABLE IF EXISTS `oa_user`;
CREATE TABLE `oa_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` char(255) NOT NULL,
  `password` varchar(50) NOT NULL COMMENT '密码',
  `trueico` tinyint(4) NOT NULL COMMENT '真实头像',
  `truename` varchar(20) NOT NULL COMMENT '真实姓名',
  `company_id` smallint(2) NOT NULL COMMENT '公司id',
  `depart_id` smallint(2) NOT NULL COMMENT '部门id',
  `job_id` smallint(2) NOT NULL COMMENT '职位id',
  `phone` varchar(12) NOT NULL COMMENT '手机号码',
  `shortnum` varchar(9) NOT NULL COMMENT '短号',
  `tel` varchar(12) NOT NULL COMMENT '电话号码',
  `sex` tinyint(4) NOT NULL DEFAULT '1' COMMENT '性别',
  `charm` int(11) NOT NULL COMMENT '魅力值',
  `nikename` varchar(20) NOT NULL COMMENT '花名',
  `nikeico` tinyint(1) NOT NULL DEFAULT '0' COMMENT '社区头像',
  `ztmoney` int(11) NOT NULL DEFAULT '0' COMMENT '智通币',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`) USING BTREE,
  UNIQUE KEY `phone` (`phone`) USING BTREE,
  KEY `name` (`truename`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='用户基本表';

-- ----------------------------
-- Records of oa_user
-- ----------------------------
INSERT INTO `oa_user` VALUES ('1', 'oa', '123456', '1', '黄小明', '1', '1', '1', '13147073294', '123', '123654', '2', '100', '小明', '1', '100');
INSERT INTO `oa_user` VALUES ('2', 'oa5113', '89ee1a2f0d55bff667c1809ba45670de', '1', 'zl', '4', '5', '6', '15112630931', '8', '9', '1', '11', '12', '13', '14');
INSERT INTO `oa_user` VALUES ('8', 'oa5014', '746a58037304d02bd8b3c949b0ba61a3', '1', 'zl', '4', '5', '6', '15112631071', '8', '9', '1', '11', '12', '13', '14');
INSERT INTO `oa_user` VALUES ('9', 'oa7111', '64770d6afd9db89ef0f54abc4327c810', '1', 'zl', '4', '5', '6', '15112638392', '8', '9', '1', '11', '12', '13', '14');
INSERT INTO `oa_user` VALUES ('10', 'oa6603', '5e6398a8a1c6245f19fa101083f3c408', '1', 'zl', '4', '5', '6', '15112633913', '8', '9', '1', '11', '12', '13', '14');
INSERT INTO `oa_user` VALUES ('11', 'oa2227', '2445d768a049c4b5b869ae1544e84adf', '1', 'zl', '4', '5', '6', '15112639339', '8', '9', '1', '11', '12', '13', '14');

-- ----------------------------
-- Table structure for `oa_user_binding`
-- ----------------------------
DROP TABLE IF EXISTS `oa_user_binding`;
CREATE TABLE `oa_user_binding` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT '某用户id',
  `qqopenid` varchar(100) DEFAULT NULL COMMENT 'qqopenid',
  `wxopenid` varchar(100) DEFAULT NULL COMMENT '微信openid',
  `oldoaid` varchar(10) DEFAULT NULL COMMENT '老OAid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `userid` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='第三方绑定表';

-- ----------------------------
-- Records of oa_user_binding
-- ----------------------------

-- ----------------------------
-- Table structure for `oa_user_socialcircle`
-- ----------------------------
DROP TABLE IF EXISTS `oa_user_socialcircle`;
CREATE TABLE `oa_user_socialcircle` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT '用户id',
  `o_id` int(11) NOT NULL COMMENT '圈子id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of oa_user_socialcircle
-- ----------------------------
