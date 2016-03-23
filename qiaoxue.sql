/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50625
 Source Host           : localhost
 Source Database       : qiaoxue

 Target Server Type    : MySQL
 Target Server Version : 50625
 File Encoding         : utf-8

 Date: 03/23/2016 22:48:23 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `admin`
-- ----------------------------
DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `pwdkey` char(8) NOT NULL DEFAULT '',
  `username` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `city_ids` varchar(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `login_ip` varchar(64) NOT NULL DEFAULT '',
  `login_count` int(10) unsigned NOT NULL DEFAULT '0',
  `login_time` int(10) unsigned NOT NULL DEFAULT '0',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `admin`
-- ----------------------------
BEGIN;
INSERT INTO `admin` VALUES ('1', 'admin', 'f17c5399e1021a35ba69bba302e3da53', 'abc123', '管理员ggg', 'admin@admin.com', '', '1', '172.31.0.1', '957', '1458052680', '1367845114');
COMMIT;

-- ----------------------------
--  Table structure for `admin_menus`
-- ----------------------------
DROP TABLE IF EXISTS `admin_menus`;
CREATE TABLE `admin_menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL DEFAULT '',
  `group_name` varchar(20) NOT NULL DEFAULT '',
  `module_name` varchar(20) NOT NULL DEFAULT '',
  `action_name` varchar(20) NOT NULL DEFAULT '',
  `params` varchar(255) NOT NULL DEFAULT '',
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_order` smallint(6) NOT NULL DEFAULT '0',
  `display` tinyint(1) NOT NULL DEFAULT '1',
  `remark` varchar(500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `admin_menus`
-- ----------------------------
BEGIN;
INSERT INTO `admin_menus` VALUES ('1', '管理首页', '', '', '', '', '0', '1', '1', ''), ('2', '教学资源', '', '', '', '', '0', '3', '1', ''), ('3', '用户管理', '', '', '', '', '0', '5', '0', ''), ('4', '内容管理', '', '', '', '', '0', '2', '1', ''), ('5', '节点管理', '', 'AdminMenus', 'index', '', '1', '9999', '1', ''), ('6', '系统设置', '', 'Setting', 'index', '', '1', '3', '1', ''), ('7', '产品列表', '', 'Product', 'index', '', '2', '1', '0', ''), ('8', '添加产品', '', 'Product', 'add', '', '2', '2', '0', ''), ('9', '文章内容', '', 'Article', 'index', '', '4', '1', '1', ''), ('10', '友情链接', '', 'Link', 'index', '', '1', '6', '1', ''), ('11', '店面报错', '', 'Store', 'errors', '', '2', '3', '0', ''), ('12', '广告管理', '', 'Advertise', 'index', '', '4', '5', '0', ''), ('13', '服务', '', 'ArticlePage', 'service', '', '4', '3', '1', ''), ('14', '文章分类', '', 'Article', 'category', '', '4', '2', '1', ''), ('15', '教学特色', '', 'ArticlePage', 'feature', '', '4', '5', '0', ''), ('16', '预订记录', '', 'Product', 'orders', '', '2', '3', '0', ''), ('17', '管理员用户', '', 'User', 'admin', '', '1', '1', '1', ''), ('18', '联系我们', '', 'ArticlePage', 'contact', '', '4', '5', '1', ''), ('19', '留言记录', '', 'Comment', 'index', '', '4', '5', '0', ''), ('20', '作品列表', '', 'Case', 'index', '', '24', '1', '0', ''), ('21', '招聘信息', '', 'Jobs', 'index', '', '24', '4', '1', ''), ('22', '幻灯片', '', 'Slider', 'index', '', '1', '3', '1', '幻灯片幻灯片'), ('23', '添加作品', '', 'Case', 'add', '', '24', '2', '0', ''), ('24', '名企招聘', '', '', '', '', '0', '4', '1', ''), ('25', '教学环境', '', 'Env', 'index', '', '2', '5', '0', ''), ('26', '添加教学环境', '', 'Env', 'add', '', '2', '6', '0', ''), ('27', '师资力量', '', 'Faculty', 'index', '', '2', '3', '1', ''), ('28', '添加教师', '', 'Faculty', 'add', '', '2', '4', '1', ''), ('29', '课程列表', '', 'Course', 'index', '', '2', '1', '1', ''), ('30', '添加课程', '', 'Course', 'add', '', '2', '2', '1', ''), ('31', '报名信息', '', 'Course', 'orders', '', '2', '9', '1', ''), ('32', '添加招聘信息', '', 'Jobs', 'add', '', '24', '5', '1', ''), ('33', '添加作品分类', '', 'Case', 'addcatalog', '', '24', '4', '0', ''), ('34', '作品分类', '', 'Case', 'catalog', '', '24', '3', '0', ''), ('35', '环境分类', '', 'Env', 'catalog', '', '2', '6', '0', ''), ('36', '添加环境', '', 'Env', 'addcatalog', '', '2', '8', '0', ''), ('37', '开班信息', '', 'Course', 'cls', '', '2', '0', '1', ''), ('38', '增加开班信息', '', 'Course', 'addcls', '', '2', '0', '1', '');
COMMIT;

-- ----------------------------
--  Table structure for `advertise`
-- ----------------------------
DROP TABLE IF EXISTS `advertise`;
CREATE TABLE `advertise` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `position` varchar(36) NOT NULL DEFAULT '',
  `params` text,
  `html` text,
  `start_time` int(10) unsigned NOT NULL DEFAULT '0',
  `end_time` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `position` (`position`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `advertise`
-- ----------------------------
BEGIN;
INSERT INTO `advertise` VALUES ('3', 'district_16', '{\"width\":710,\"height\":217,\"link\":\"http:\\/\\/www.zbgogogo.com\\/healthy\\/\",\"type\":\"image\",\"file\":\"upload\\/ad\\/v\\/52b705bc3b792.jpg\"}', '<a href=\"http://www.zbgogogo.com/healthy/\" target=\"_blank\"><img src=\"upload/ad/v/52b705bc3b792.jpg\" width=\"710\" height=\"217\" /></a>', '1387726268', '0'), ('6', 'district_16', '{\"width\":710,\"height\":217,\"link\":\"http:\\/\\/www.zbgogogo.com\\/healthy\\/\",\"type\":\"image\",\"file\":\"upload\\/ad\\/d\\/52b7a53935dcb.jpg\"}', '<a href=\"http://www.zbgogogo.com/healthy/\" target=\"_blank\"><img src=\"upload/ad/d/52b7a53935dcb.jpg\" width=\"710\" height=\"217\" /></a>', '1387767097', '0'), ('7', 'district_15', '{\"width\":0,\"height\":0,\"link\":\"http:\\/\\/www.zbgogogo.com\\/healthy\\/\",\"type\":\"image\",\"file\":\"upload\\/ad\\/k\\/52b70ac9ef9cf.jpg\"}', '<a href=\"http://www.zbgogogo.com/healthy/\" target=\"_blank\"><img src=\"upload/ad/k/52b70ac9ef9cf.jpg\" /></a>', '1387727561', '0'), ('8', 'district_9', '{\"width\":0,\"height\":0,\"link\":\"http:\\/\\/www.zbgogogo.com\\/healthy\\/\",\"type\":\"image\",\"file\":\"upload\\/ad\\/p\\/52b70b26778eb.jpg\"}', '<a href=\"http://www.zbgogogo.com/healthy/\" target=\"_blank\"><img src=\"upload/ad/p/52b70b26778eb.jpg\" /></a>', '1387727654', '0'), ('9', 'district_8', '{\"width\":0,\"height\":0,\"link\":\"http:\\/\\/www.zbgogogo.com\\/healthy\\/\",\"type\":\"image\",\"file\":\"upload\\/ad\\/1\\/52b70b3f2ca39.jpg\"}', '<a href=\"http://www.zbgogogo.com/healthy/\" target=\"_blank\"><img src=\"upload/ad/1/52b70b3f2ca39.jpg\" /></a>', '1387727679', '0'), ('10', 'district_14', '{\"width\":0,\"height\":0,\"link\":\"http:\\/\\/www.zbgogogo.com\\/healthy\\/\",\"type\":\"image\",\"file\":\"upload\\/ad\\/a\\/52b70b57253db.jpg\"}', '<a href=\"http://www.zbgogogo.com/healthy/\" target=\"_blank\"><img src=\"upload/ad/a/52b70b57253db.jpg\" /></a>', '1387727703', '0'), ('11', 'district_13', '{\"width\":0,\"height\":0,\"link\":\"http:\\/\\/www.zbgogogo.com\\/healthy\\/\",\"type\":\"image\",\"file\":\"upload\\/ad\\/0\\/52b70b6ed4031.jpg\"}', '<a href=\"http://www.zbgogogo.com/healthy/\" target=\"_blank\"><img src=\"upload/ad/0/52b70b6ed4031.jpg\" /></a>', '1387727726', '0'), ('12', 'district_12', '{\"width\":0,\"height\":0,\"link\":\"http:\\/\\/www.zbgogogo.com\\/healthy\\/\",\"type\":\"image\",\"file\":\"upload\\/ad\\/c\\/52b70b853d466.jpg\"}', '<a href=\"http://www.zbgogogo.com/healthy/\" target=\"_blank\"><img src=\"upload/ad/c/52b70b853d466.jpg\" /></a>', '1387727749', '0');
COMMIT;

-- ----------------------------
--  Table structure for `article_category`
-- ----------------------------
DROP TABLE IF EXISTS `article_category`;
CREATE TABLE `article_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(60) NOT NULL DEFAULT '',
  `catalog` varchar(20) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pids` varchar(100) NOT NULL DEFAULT '',
  `sort_order` smallint(6) NOT NULL DEFAULT '0',
  `final` tinyint(1) NOT NULL DEFAULT '0',
  `deletable` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `code` (`catalog`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `article_category`
-- ----------------------------
BEGIN;
INSERT INTO `article_category` VALUES ('1', '学库', 'xueku', '', '', '0', '', '1', '0', '0'), ('2', '一级建造师', 'v1', '', '', '1', '1', '1', '0', '0'), ('3', '二级建造师', 'v2', '', '', '1', '1', '2', '0', '0'), ('4', '安全工程师', 'v4', '', '', '1', '1', '4', '0', '0'), ('5', '一级消防师', 'v5', '', '', '1', '1', '5', '0', '0'), ('6', '二级消防师', 'v6', '', '', '1', '1', '6', '0', '0'), ('7', '注册造价师', 'v7', '', '', '1', '1', '7', '0', '0'), ('8', '学历/职称', 'v8', '', '', '1', '1', '8', '0', '0'), ('9', '监理工程师', 'v3', '', '', '1', '1', '3', '0', '0'), ('10', '每日一练', 'v1s2', '', '', '2', '2,1', '2', '0', '1'), ('11', '历年真题', 'v2s1', '', '', '3', '3,1', '1', '0', '1'), ('12', '每日一练', 'v2s2', '', '', '3', '3,1', '2', '0', '1'), ('13', '历年真题', 'v13', '', '', '2', '2,1', '1', '0', '1'), ('14', '巧学公告', 'news', '', '', '0', '', '2', '1', '0'), ('15', '上课方式', 'how', '', '', '0', '', '3', '1', '0'), ('16', '为什么选择巧学', 'why', '', '', '0', '', '4', '1', '0'), ('17', '历年真题', 'v1', '', '', '4', '4,1', '0', '0', '1'), ('18', '每日一练', 'sd', '', '', '4', '4,1', '0', '0', '1'), ('19', '2013年', '22', '', '', '13', '13,2,1', '0', '1', '1'), ('20', '历年真题', 's', '', 's', '5', '5,1', '0', '0', '1'), ('21', '2014年', '14', '', '', '13', '13,2,1', '0', '1', '1'), ('22', '2015年', '15', '', '', '13', '13,2,1', '0', '1', '1'), ('23', '第一天', '1', '', '', '10', '10,2,1', '0', '1', '1'), ('24', '第二天', '2', '', '', '10', '10,2,1', '0', '1', '1');
COMMIT;

-- ----------------------------
--  Table structure for `article_page`
-- ----------------------------
DROP TABLE IF EXISTS `article_page`;
CREATE TABLE `article_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_code` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `video` varchar(1000) NOT NULL DEFAULT '',
  `content` text,
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `visit_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `page_code` (`page_code`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `article_page`
-- ----------------------------
BEGIN;
INSERT INTO `article_page` VALUES ('1', 'about', '画室简介', '', '<p><span style=\"font-family: arial; padding: 0px; margin: 0px; color: rgb(68, 68, 68); line-height: 22px; font-size: 15px; background-color: rgb(250, 239, 240);\">&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</span><span style=\"color: rgb(97, 97, 97); font-family: arial; line-height: 24px; padding: 0px; margin: 0px; background-color: rgb(250, 239, 240);\"><span style=\"padding: 0px; margin: 0px; color: rgb(3, 110, 184); font-size: 18px; background-color: rgb(248, 241, 241);\">周边，让美食尽在身边！</span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px;\"></span></span></p><p><br /></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px; font-size: 16px;\"><span style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 16px; line-height: 24px; background-color: rgb(250, 239, 240);\">&nbsp; &nbsp; &nbsp; &nbsp; 周边网</span><span style=\"font-size: 16px; line-height: 1.5; background-color: rgb(250, 239, 240); color: rgb(97, 97, 97); padding: 0px; margin: 0px; font-family: Arial;\">(&nbsp;</span><span style=\"font-size: 16px; line-height: 24px; color: rgb(97, 97, 97); background-color: rgb(248, 241, 241); padding: 0px; margin: 0px; font-family: Arial;\"><a href=\"http://www.zbgogogo.com/\" style=\"padding: 0px; margin: 0px; color: rgb(0, 153, 204); text-decoration: none;\"><span style=\"padding: 0px; margin: 0px; color: rgb(220, 129, 0); background-color: rgb(250, 239, 240);\">www.zbgogogo.com</span></a></span><span style=\"font-size: 16px; line-height: 1.5; background-color: rgb(250, 239, 240); color: rgb(97, 97, 97); padding: 0px; margin: 0px; font-family: Arial;\">&nbsp;)</span><span style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 16px; line-height: 24px; background-color: rgb(250, 239, 240);\">收录了南宁953条道路、30多所高校所有的外卖菜单，是南宁最大的外卖信息公益平台。挖掘周边美食，一起喂南宁市民服务！</span><br /></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); font-family: arial; background-color: rgb(248, 241, 241);\"><br /></p><p class=\"content\" style=\"padding: 0px; margin: 0px; line-height: 1.5; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px; font-size: 16px;\"><span style=\"color: rgb(68, 68, 68); font-family: arial; padding: 0px; margin: 0px; background-color: rgb(250, 239, 240);\">&nbsp; &nbsp; &nbsp; &nbsp; 周边网</span><span style=\"color: rgb(68, 68, 68); font-family: arial; padding: 0px; margin: 0px; background-color: rgb(250, 239, 240);\">是广西领先的外卖信息平台，由几位</span><span style=\"color: rgb(68, 68, 68); font-family: Arial; padding: 0px; margin: 0px; background-color: rgb(250, 239, 240);\">80</span><span style=\"color: rgb(68, 68, 68); font-family: arial; padding: 0px; margin: 0px; background-color: rgb(250, 239, 240);\">后的青年草根创办。整合了数千家</span><span style=\"color: rgb(68, 68, 68); font-family: arial; padding: 0px; margin: 0px; background-color: rgb(250, 239, 240);\">南宁外卖餐厅资源，为南宁市数百万白领用户和大学生提供外卖信息服务。</span><span style=\"padding: 0px; margin: 0px; background-color: rgb(250, 239, 240);\"></span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px; font-size: 16px;\"><span style=\"padding: 0px; margin: 0px; background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\"><span style=\"font-family: 宋体; font-size: 16px;\"><br /></span></span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px; font-size: 16px;\"><span style=\"padding: 0px; margin: 0px; background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\"><span style=\"font-family: 宋体; font-size: 16px;\">&nbsp; &nbsp; 致力于让大家更快、更方便的订到自己所需要的外卖。我们努力为您提供所在地点周边尽量多的外卖单，并提供方便的分类和检索功能，让您选择更多，订餐更快捷。</span></span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><br /></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px; background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\"><span style=\"font-family: 宋体;\"><span style=\"font-size: 16px; font-family: 宋体;\">&nbsp; &nbsp; </span><span style=\"font-family: 宋体;\"><span style=\"font-size:16px;\">以消费者创造更便捷的服务为宗旨，做到当地美食资源，优质配送服务相互融合，成为商家和周边网消费使用用户互相沟通的良好节点，引领各大城市消费及生活新潮流。</span></span></span></span></span></span><span style=\"font-size:16px;\"><span style=\"line-height: 1.5; font-family: 宋体; background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\">于此同时，“周边”向用户传达一种健康、年轻化的饮食习惯和生活方式，同时也努力为优质的外卖店带来生意，为</span><span style=\"line-height: 1.5; font-family: 宋体; background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\">餐厅提供一站式运营的解决方案。</span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px; font-size: 16px;\"><span style=\"padding: 0px; margin: 0px; background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\"><span style=\"font-family: 宋体; font-size: 16px;\"><br /></span></span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px; font-size: 16px;\"><span style=\"padding: 0px; margin: 0px; background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\"><span style=\"font-family: 宋体; font-size: 16px;\"><br /></span></span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><br /></p><br /><p><br /></p><p><span style=\"font-family:宋体;\"><span style=\"font-size:16px;\"><br /></span></span></p><p><br /></p><br />', '', '', '', '1453817909', '299'), ('2', 'contact', '联系我们', '', '<p><span style=\"font-family:arial;font-size:18px;color:#036eb8;LINE-HEIGHT: 24px; BACKGROUND-COLOR: rgb(248,241,241)\">周边官网</span></p><p><span style=\"font-family:arial;font-size:18px;color:#036eb8;LINE-HEIGHT: 24px; BACKGROUND-COLOR: rgb(248,241,241)\"><br /></span></p><p></p><p style=\"PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 11pt; PADDING-TOP: 0px\" class=\"content\"><span style=\"LINE-HEIGHT: 1.5; FONT-SIZE: 11pt\">联系电话：0771-3393 992&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;188-7873-7118</span></p><br style=\"TEXT-ALIGN: center; PADDING-BOTTOM: 0px; LINE-HEIGHT: 24px; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 16px; PADDING-TOP: 0px\" /><p style=\"PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; FONT-SIZE: 11pt; PADDING-TOP: 0px\" class=\"content\"><span style=\"color:#616161;\">商务合作：151-7717-1158 &nbsp; &nbsp; &nbsp; 腾讯QQ：1826328783 &nbsp; &nbsp; &nbsp; &nbsp;</span><span style=\"color:#000000;\"></span></p><br style=\"TEXT-ALIGN: center; PADDING-BOTTOM: 0px; LINE-HEIGHT: 24px; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 16px; PADDING-TOP: 0px\" /><p style=\"PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 11pt; PADDING-TOP: 0px\" class=\"content\">官方网站：<a href=\"http://zbgogogo.com\">www.zbgogogo.com</a></p><br style=\"TEXT-ALIGN: center; PADDING-BOTTOM: 0px; LINE-HEIGHT: 24px; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 16px; PADDING-TOP: 0px\" /><p style=\"PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 11pt; PADDING-TOP: 0px\" class=\"content\"></p><p style=\"PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 11pt; PADDING-TOP: 0px\" class=\"content\">办公室地址：南宁市西乡塘区大学东路98号世贸西城A座804室</p><p><span style=\"font-family:arial;color:#616161;LINE-HEIGHT: 22px; BACKGROUND-COLOR: rgb(248,241,241); FONT-SIZE: 15px\"><span style=\"font-family:Arial;font-size:16px;PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(250,239,240); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; PADDING-TOP: 0px\"><span style=\"font-family:arial;LINE-HEIGHT: 22px; BACKGROUND-COLOR: rgb(248,241,241); FONT-SIZE: 15px\">金湖店</span></span>地址：南宁市青秀区金湖北路东和巷</span><br style=\"TEXT-ALIGN: center; PADDING-BOTTOM: 0px; LINE-HEIGHT: 24px; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 16px; PADDING-TOP: 0px\" /></p><p><br /></p><p><br style=\"TEXT-ALIGN: center; PADDING-BOTTOM: 0px; LINE-HEIGHT: 24px; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 16px; PADDING-TOP: 0px\" /></p><p><br /></p><p style=\"PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 11pt; PADDING-TOP: 0px\" class=\"content\"><span style=\"font-size:18px;color:#036eb8;LINE-HEIGHT: 24px\">周边网，为您提供最全面的周边外卖信息。</span></p><p style=\"PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 11pt; PADDING-TOP: 0px\" class=\"content\"><span style=\"font-family:arial;color:#616161;LINE-HEIGHT: 22px; BACKGROUND-COLOR: rgb(248,241,241); FONT-SIZE: 15px\"><br /></span></p><p style=\"PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 11pt; PADDING-TOP: 0px\" class=\"content\"><span style=\"font-family:arial;color:#616161;LINE-HEIGHT: 22px; BACKGROUND-COLOR: rgb(248,241,241); FONT-SIZE: 15px\">周边团队致力于让大家更快、更方便的订到自己所需要的外卖。我们努力为您提供所在地点周边尽量多的外卖单，并提供方便的分类和检索功能，让您选择更多，订餐更便捷。</span><br /></p><p style=\"PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 11pt; PADDING-TOP: 0px\" class=\"content\"><br /></p><p style=\"PADDING-BOTTOM: 0px; LINE-HEIGHT: 1.5; BACKGROUND-COLOR: rgb(248,241,241); MARGIN: 0px; PADDING-LEFT: 0px; PADDING-RIGHT: 0px; FONT-FAMILY: arial; COLOR: rgb(97,97,97); FONT-SIZE: 11pt; PADDING-TOP: 0px\" class=\"content\"><br /></p><br />', '', '', '', '1453817961', '180'), ('3', 'terms', '免责声明', '', '<h1 class=\"heading\" style=\"padding: 0px; margin: 0px 0px 1em; font-weight: normal; font-size: 18px; color: rgb(3, 110, 184); font-family: arial; background-color: rgb(248, 241, 241);\">免责声明</h1><p class=\"content\" style=\"padding: 0px; margin: 0px; font-size: 11pt; line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\">1.用户明确同意其使用周边网络服务所存在的风险将完全由其自己承担；因其使用周边网络服务而产生的一切后果也由其自己承担，周边网对用户不承担任何责任。</p><br style=\"font-family: arial;font-size:16px; text-align: center; background-color: rgb(248, 241, 241);\" /><p class=\"content\" style=\"padding: 0px; margin: 0px; font-size: 11pt; line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\">2.周边网信息均来自店家发放之外卖单或网上提供的信息，可能不正确或有编排上的错误。周边网不保证内容的正确性、可靠性、完整性和及时性。</p><br style=\"font-family: arial;font-size:16px; text-align: center; background-color: rgb(248, 241, 241);\" /><p class=\"content\" style=\"padding: 0px; margin: 0px; font-size: 11pt; line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\">3.周边网不担保网络服务一定能满足用户的要求，也不担保网络服务不会中断，对网络服务的及时性、安全性、准确性也都不作担保。</p><br style=\"font-family: arial;font-size:16px; text-align: center; background-color: rgb(248, 241, 241);\" /><p class=\"content\" style=\"padding: 0px; margin: 0px; font-size: 11pt; line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\">4.周边网不保证为向用户提供便利而设置的外部链接的准确性和完整性，同时，对于该等外部链接指向的不由周边网实际控制的任何网页上的内容，周边网不承担任何责任。</p><br style=\"font-family: arial;font-size:16px; text-align: center; background-color: rgb(248, 241, 241);\" /><p class=\"content\" style=\"padding: 0px; margin: 0px; font-size: 11pt; line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\">5.对于因不可抗力或周边网不能控制的原因造成的网络服务中断或其它缺陷，周边网不承担任何责任，但将尽力减少因此而给用户造成的损失和影响。</p><br style=\"font-family: arial;font-size:16px; text-align: center; background-color: rgb(248, 241, 241);\" /><br style=\"font-family: arial;font-size:16px; text-align: center; background-color: rgb(248, 241, 241);\" /><h1 class=\"heading\" style=\"padding: 0px; margin: 0px 0px 1em; font-weight: normal; font-size: 18px; color: rgb(3, 110, 184); font-family: arial; background-color: rgb(248, 241, 241);\">隐私保护</h1><p class=\"content\" style=\"padding: 0px; margin: 0px; font-size: 11pt; line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\">本网站无意侵犯任何外卖商家的隐私权，若有商家不愿自己的外卖信息出现在本网站上，请及时与我们取得联系，我们将第一时间将该商家的信息删除。</p>', '', '', '', '1385567389', '64'), ('4', 'fmeeting', '会议外卖', '', '<p><span style=\"font-size:16px;color:#666666;background-color: rgb(255, 255, 255);\"><strong></strong></span></p><p></p><p class=\"content\" style=\"padding: 0px; margin: 0px; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"color: rgb(68, 68, 68); line-height: 22px; font-size: 15px; font-family: arial; background-color: rgb(250, 239, 240);\">&nbsp; &nbsp; &nbsp; &nbsp; </span><span style=\"color: rgb(68, 68, 68); line-height: 22px; font-family: arial; background-color: rgb(250, 239, 240);\"><span style=\"font-size:18px;\">&nbsp; &nbsp;</span></span><span style=\"font-family: arial; background-color: rgb(250, 239, 240);\"><span style=\"color: rgb(3, 110, 184); font-size: 18px; line-height: 24px; font-family: arial; background-color: rgb(248, 241, 241);\">还在为活动餐饮发愁？ 周边会议外卖为您提供解决方案。</span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"font-size:16px;\"><br style=\"padding: 0px; margin: 0px;\" /></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"font-size:16px;\"><span style=\"color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 周边网</span><span style=\"line-height: 1.5; font-family: Arial; background-color: rgb(250, 239, 240);\">(&nbsp;</span><span style=\"font-family: Arial;\"><a href=\"http://www.zbgogogo.com/\"><span style=\"color: rgb(220, 129, 0); background-color: rgb(250, 239, 240);\">www.zbgogogo.com</span></a></span><span style=\"line-height: 1.5; font-family: Arial; background-color: rgb(250, 239, 240);\">&nbsp;)</span><span style=\"color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\">是南宁领先的外卖信息平台，由几位</span><span style=\"font-family: Arial; color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\">80</span><span style=\"color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\">后的青年草根创办。整合了数千家</span><span style=\"background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\">南宁外卖餐厅资源，为南宁市数百万白领用户和大学生提供外卖信息服务。最新上线茶歇、会议外卖频道，</span><span style=\"background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\">满足您对会议餐饮、自助餐会、外卖服务的各种需求</span><span style=\"background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\">。</span></span><br /></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); font-size: 11pt; line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"><br /></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); font-size: 11pt; line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px;\"></span></p><p><br /></p><span style=\"color: rgb(68, 68, 68); font-family: arial; line-height: 25px; background-color: rgb(250, 239, 240);\"><span style=\"font-size:16px;\"></span></span><div class=\"part_title\" style=\"padding: 0px; margin: 0px; width: 990px; background-image: url(http://waimaiku.com/img/tuan/p_title.png); background-color: rgb(250, 239, 240); height: 49px; overflow: auto; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; background-position: 0px 0px; background-repeat: no-repeat no-repeat;\"><h3 id=\"us\" style=\"padding: 0px; margin: 12px 0px 0px 70px; font-weight: normal; font-size: 14px; width: 160px; height: 18px; background-image: url(http://waimaiku.com/img/tuan/p_title.png); background-position: 0px -49px; background-repeat: no-repeat no-repeat;\"></h3></div><div class=\"content_box\" style=\"padding: 0px; margin: 30px auto 40px; width: 853px; line-height: 25px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; background-color: rgb(250, 239, 240);\"><h2 class=\"choose_us_title\" id=\"cu_f\" style=\"padding: 0px; margin: 0px; font-weight: normal; font-size: 14px; background-image: url(http://waimaiku.com/img/tuan/p_ct_bg.png); width: 230px; height: 36px; background-position: 0px 0px; background-repeat: no-repeat no-repeat;\"><br /></h2><div></div><p><span style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 10.5pt; line-height: 25px; background-color: rgb(250, 239, 240);\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;周边网开展茶歇、冷餐会、宴会外卖服务，拥有几十家专业的餐厅合作伙伴。不论您的活动在哪里</span></p><p><span style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 10.5pt; line-height: 25px; background-color: rgb(250, 239, 240);\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;举行、</span><span style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 10.5pt; line-height: 25px; background-color: rgb(250, 239, 240);\">人数</span>多少都能为您提供专业的解决方案。外卖库员工全程跟踪，协助您进行全部沟通、交流</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;工作。您只需要提出具体的需求，其他所有的琐碎工作都有外卖库员工为您代劳。</p><p></p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\">求，其他所有的琐碎工作都有外卖库员工为您代劳。</p><h2 class=\"choose_us_title\" id=\"cu_s\" style=\"padding: 0px; margin: 0px; font-weight: normal; font-size: 14px; background-image: url(http://waimaiku.com/img/tuan/p_ct_bg.png); background-color: rgb(250, 239, 240); width: 230px; height: 36px; color: rgb(68, 68, 68); font-family: arial; line-height: 25px; background-position: 0px -36px; background-repeat: no-repeat no-repeat;\"></h2><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\">周边网作为合作餐厅的重要合作伙伴，与相关餐厅均有协议价格。提供产品选择多样，包括西式、</p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\">日韩、港式、中式甚至甜点的定制服务，满足不同场合、品牌、活动的需求。</p><p></p><h2 class=\"choose_us_title\" id=\"cu_t\" style=\"padding: 0px; margin: 0px; font-weight: normal; font-size: 14px; background-image: url(http://waimaiku.com/img/tuan/p_ct_bg.png); background-color: rgb(250, 239, 240); width: 230px; height: 36px; color: rgb(68, 68, 68); font-family: arial; line-height: 25px; background-position: 0px -72px; background-repeat: no-repeat no-repeat;\"></h2><p></p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\">周边网的合作伙伴均为专业的餐饮服务公司，涉外餐会经验丰富，确保服务质量与食品安全。拥有</p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\">政府颁发的食品卫生安全许可证明。让您的来宾吃的满意、放心。</p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\"><br /></p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\"><br /></p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\"><span style=\"color: rgb(3, 110, 184); font-family: arial; font-size: 18px; line-height: 24px; background-color: rgb(248, 241, 241);\">联系我们：</span></p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\">周边官网</p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\">联系电话：0771-3393 992 &nbsp; &nbsp; &nbsp;联系QQ：1826328783</p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\">合作热线：151-7717-1158</p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\">具体地址：南宁市大学东路98号世贸西城A座804室</p><p></p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\"><br /></p><br /><div><br /></div><br /><p><span style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 10.5pt; line-height: 25px; background-color: rgb(250, 239, 240);\"><span style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\"><span style=\"color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\"></span></span></span></p><p class=\"small_part\" style=\"padding: 0px; margin: 10px 0px 20px 55px; width: 700px; color: rgb(68, 68, 68); font-family: arial; font-size: 14px; line-height: 25px; background-color: rgb(250, 239, 240);\"></p><div><br /></div><br /><p></p><br /><p><br /></p></div><p></p>', '', '', '', '1389667415', '113'), ('5', 'aboutus', '关于我们', '', '<p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px; color: rgb(3, 110, 184); line-height: 24px;\"><span style=\"padding: 0px; margin: 0px; line-height: 22px; color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\"><span style=\"font-size:16px;\"><span style=\"color: rgb(97, 97, 97);\">周边网，为您提供周边外卖的信息。</span>&nbsp; </span></span><span style=\"padding: 0px; margin: 0px; font-size: 15px; line-height: 22px; color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\">&nbsp;&nbsp;</span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px; color: rgb(3, 110, 184); line-height: 24px;\"><span style=\"padding: 0px; margin: 0px; line-height: 22px; color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\"><span style=\"font-size:16px;\"><br /></span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px; color: rgb(3, 110, 184); line-height: 24px;\"><span style=\"padding: 0px; margin: 0px; line-height: 22px; color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"font-size:16px;\"><span style=\"color: rgb(97, 97, 97);\">周边网</span><span style=\"font-family: Arial; color: rgb(97, 97, 97);\">(&nbsp;<a href=\"http://www.zbgogogo.com/\"><span style=\"color: rgb(0, 153, 204);\">www.zbgogogo.com</span></a>&nbsp;)</span><span style=\"color: rgb(97, 97, 97);\">是南宁周边电子商务科技有限公司旗下网站，致力于让大家更快、更方便的订到自己所需要的外卖。我们努力为您提供所在地点周边尽量多的外卖单，并提供方便的分类和检索功能，让您选择更多，订餐更快捷。</span><br /></span></span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px; color: rgb(3, 110, 184); line-height: 24px;\"><span style=\"padding: 0px; margin: 0px; line-height: 22px; color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"font-size:16px;\"><br /></span></span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px; color: rgb(3, 110, 184); line-height: 24px;\"><span style=\"padding: 0px; margin: 0px; line-height: 22px; color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\"><span style=\"padding: 0px; margin: 0px;\"><span style=\"color: rgb(97, 97, 97);\"><span style=\"font-size:16px;\">以消费者创造更便捷的服务为宗旨，做到当地美食资源，优质配送服务相互融合，成为商家和周边网消费使用用户互相沟通的良好节点，引领各大城市消费及生活新潮流。于此同时，“周边”向用户传达一种健康、年轻化的饮食习惯和生活方式，同时也努力为优质的外卖店带来生意，为餐厅提供一站式运营的解决方案。</span></span><br /></span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px; color: rgb(3, 110, 184); line-height: 24px;\"><span style=\"padding: 0px; margin: 0px; line-height: 22px; color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\"><span style=\"padding: 0px; margin: 0px; font-size: 18px;\"><br /></span></span></span></p><p class=\"content\" style=\"padding: 0px; margin: 0px; color: rgb(97, 97, 97); line-height: 1.5; font-family: arial; background-color: rgb(248, 241, 241);\"><span style=\"padding: 0px; margin: 0px; color: rgb(3, 110, 184); line-height: 24px;\"><span style=\"padding: 0px; margin: 0px; line-height: 22px; color: rgb(68, 68, 68); background-color: rgb(250, 239, 240);\"><span style=\"padding: 0px; margin: 0px; font-size: 18px;\">&nbsp;</span></span></span></p><p><br /></p><div><span style=\"padding: 0px; margin: 0px;\"><span style=\"padding: 0px; margin: 0px; font-size: 16px;\"><span style=\"padding: 0px; margin: 0px; background-color: rgb(250, 239, 240); color: rgb(68, 68, 68);\"><br /></span></span></span></div>', '', '', '', '1375468487', '0'), ('6', 'service', '服务', '奔塔顶 基本面栽 ', '<p><img src=\"/data/upload/3y/6r/55d087b8e6733_380x380.jpg\" alt=\"\" /><br /></p><p><br /></p><p>枯栽夺</p><p>asdfas夺</p><p>基本原则地基本原则 <br /></p><p>基本原则 <br /></p>', 'upload/3y/6r/55d087b8e6733_380x380.jpg', '', '', '1458660946', '82'), ('7', 'business', '招商加盟', '', '<p>要厅有根有据</p><p>要在基本原则 <br /></p>', '', '', '', '1439393672', '33'), ('8', 'tostu', '招生信息', '', '<p>大雁塔左<span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本大雁塔左<span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span>大雁塔左<span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span>大雁塔左<span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span>大雁塔左<span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span><span style=\"color: rgb(151, 151, 151); font-family: 微软雅黑, 宋体; font-size: 13px; line-height: 16px;\">工要夺基本原则载顶替基本原则地sdfdf苛夺基本原则地基本</span></span></p><p>f asdf</p><p>&nbsp;a</p><p>sdf</p><p>a sd</p><p>f&nbsp;</p><p>ads</p><p>f a</p><p>dsf</p><p>&nbsp;asd</p><p>f a</p><p><br /></p>', '', '', '', '1453817939', '27'), ('9', 'topar', '致家长信', '', '致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信致学长信', '', '', '', '1453817934', '18'), ('10', 'feature', '教学特色', '<embed width=\"497\" height=\"320\" allowscriptaccess=\"always\" allownetworking=\"all\" allowfullscreen=\"true\" wmode=\"transparent\" type=\"application/x-shockwave-flash\" src=\"http://player.56.com/v_Njk4OTI3MDQ.swf\">', '<p>基本面基本面塔顶 塔顶&nbsp;</p><p>教学特色<br /></p><p>教学特色<br /></p><p>教学特色<br /><br /><br /><br />教学特色<br /><br />教学特色<br /><br />教学特色<br /><br />教学特色<br /><br />教学特色<br /><br />教学特色<br /><br />教学特色<br /><br />教学特色<br /><br />教学特色<br /></p><div style=\"top: 0px;\"><br /></div>', '', '', '', '1453817944', '8');
COMMIT;

-- ----------------------------
--  Table structure for `articles`
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `video` varchar(1000) NOT NULL DEFAULT '',
  `content` text,
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `visit_count` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `author` varchar(60) NOT NULL DEFAULT '',
  `from_url` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `cate_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `source` varchar(60) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cate_id` (`cate_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `articles`
-- ----------------------------
BEGIN;

COMMIT;

-- ----------------------------
--  Table structure for `cases`
-- ----------------------------
DROP TABLE IF EXISTS `cases`;
CREATE TABLE `cases` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(6) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '类型',
  `cid` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `cases`
-- ----------------------------
BEGIN;
INSERT INTO `cases` VALUES ('1', '吩咐吩咐', 'upload/2l/6y/55ca0343a3cb7.jpg', '1', '1', '1439302467', 'case', '7'), ('3', 'kwkw', 'upload/4t/2q/55ca25b0c239e.jpg', '44', '1', '1439311280', 'case', '5'), ('4', 'sdfsdfasdf', 'upload/3r/5m/569baa4315bf7.jpg', '0', '1', '1453042243', 'case', '6'), ('5', '素描', 'upload/1o/3g/56a62fc82e560.jpg', '3', '1', '1453731698', 'catalog', '0'), ('6', '写生', 'upload/0h/5o/56a6322ad707b.jpg', '5', '1', '1453732395', 'catalog', '0'), ('7', '油画', 'upload/2m/2u/56a6323a91c5d.jpg', '4', '1', '1453732410', 'catalog', '0'), ('8', 'fdsdfg ', 'upload/8m/8d/56a63587df0cb.jpg', '0', '1', '1453733255', 'case', '5'), ('9', '教学大楼', 'upload/0g/6t/56a63593178b5.jpg', '0', '1', '1453733267', 'case', '5'), ('10', '教学楼', 'upload/6w/5o/56a78e579e5ca.jpg', '1', '1', '1453821527', 'catalog', '0');
COMMIT;

-- ----------------------------
--  Table structure for `course_orders`
-- ----------------------------
DROP TABLE IF EXISTS `course_orders`;
CREATE TABLE `course_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `course_id` int(10) unsigned NOT NULL DEFAULT '0',
  `course_name` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(60) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `age` varchar(20) NOT NULL DEFAULT '',
  `qq` varchar(255) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `class_id` int(10) unsigned NOT NULL DEFAULT '0',
  `class_name` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `course_id` (`course_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `course_orders`
-- ----------------------------
BEGIN;
INSERT INTO `course_orders` VALUES ('5', '6', '教学二班', 'asdfasf', '15099998888', '12', 'sdfas asdf asdf', '1453555195', '0', ''), ('6', '6', '教学二班', 'asdfasf', '15099998888', '12', 'sdfas asdf asdf', '1453555366', '0', ''), ('7', '0', '', '阿德', '18260806066', '', '', '1458742660', '0', ''), ('8', '6', '二级建造师', 'dsfsdf', '1503333212', '', '', '1458743020', '0', ''), ('9', '6', '二级建造师', 'rgdgdf', '123123123', '', '', '1458743266', '0', ''), ('10', '6', '二级建造师', 'rgdgdf', '123123123', '', '', '1458743306', '0', ''), ('11', '6', '二级建造师', 'iygffh', '2123123', '', '', '1458743495', '15', 'sdfasdf');
COMMIT;

-- ----------------------------
--  Table structure for `courses`
-- ----------------------------
DROP TABLE IF EXISTS `courses`;
CREATE TABLE `courses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `image` varchar(2000) NOT NULL DEFAULT '',
  `video` varchar(2000) NOT NULL DEFAULT '',
  `detail` text NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort_order` smallint(5) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `desc` varchar(255) NOT NULL DEFAULT '',
  `course_times` int(10) unsigned NOT NULL DEFAULT '0',
  `price` decimal(9,2) unsigned NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `courses`
-- ----------------------------
BEGIN;
INSERT INTO `courses` VALUES ('5', '一级建造师', 'upload/2e/0b/56f29a5feee38.jpg', '<embed src=\"http://player.youku.com/player.php/Type/Folder/Fid/26574747/Ob/1/sid/XMTQ1MjMyNjY3Ng==/v.swf\" quality=\"high\" width=\"480\" height=\"400\" align=\"middle\" allowScriptAccess=\"always\" allowFullScreen=\"true\" mode=\"transparent\" type=\"application/x-shockwave-flash\"></embed>', '厅苦asdf&nbsp;', '1', '0', '1453302986', '顶戴顶戴夺基本面 ', '50', '3560.00'), ('6', '二级建造师', 'upload/1m/3x/56f29a4da8dc2.jpg', '', '枯asdf工枯东奔西走东奔西走苦苦苦基本面基本面有东西', '1', '0', '1453303285', '苛奔奔sfasdd塔顶  左asdfasd基本面苦东奔西走asdf基本面东奔西走', '76', '1260.00'), ('7', '监理工程师', 'upload/6y/3q/56f29a73ca82f.jpg', 'sd asfd', 'as fasdas dfas', '1', '0', '1458562759', 'sdf sdf', '129', '999.00'), ('8', '安全工程师', 'upload/5q/4p/56f29af097aa7.jpg', 'asdf枯基本面f', '无可奈何asdf基本面基本面&nbsp;', '1', '0', '1458569748', '棋asdf', '30', '983.00'), ('9', '一级消防师', 'upload/3c/4x/56f29ada0e8ab.jpg', '', '枯东奔西走asdf基本面地', '1', '0', '1458661866', '村枯塔顶 塔顶 asdfasdf基本面魂牵梦萦 霜期 ', '26', '2217.00'), ('10', '二级消防师', 'upload/4w/1p/56f29ac88b2d9.jpg', '', '棒棒 塔顶地基本面无可奈何&nbsp;', '1', '0', '1458661892', '顶戴sd', '98', '6721.00'), ('11', '注册师课程', 'upload/0v/0h/56f29a3ed745f.jpg', '', '基本面asdf棋枯萎顶戴基本面f', '1', '0', '1458662069', 'f', '90', '2781.00'), ('12', '学历课程', 'upload/1s/2d/56f29a2b6736c.jpg', '', '基本面塔顶地asdfasdf东奔西走基本面', '1', '0', '1458662094', '', '88', '6782.00');
COMMIT;

-- ----------------------------
--  Table structure for `courses_class`
-- ----------------------------
DROP TABLE IF EXISTS `courses_class`;
CREATE TABLE `courses_class` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `video` varchar(2000) NOT NULL DEFAULT '',
  `detail` text NOT NULL,
  `sort_order` smallint(5) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `course_id` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `courses_class`
-- ----------------------------
BEGIN;
INSERT INTO `courses_class` VALUES ('8', 'sdfasdf', '基本面顶戴', '<img src=\"/data/upload/0j/4p/56f009deed584_600x600.jpg\" alt=\"\" />基本面塔顶地sf sdf asdf asd asdf', '0', '1458571747', '7', 'upload/0j/4p/56f009deed584_600x600.jpg'), ('9', 'sdfasdf', '基本面顶戴', '<img src=\"/data/upload/0j/4p/56f009deed584_600x600.jpg\" alt=\"\" />基本面塔顶地sf sdf asdf asd asdf', '0', '1458571747', '8', 'upload/0j/4p/56f009deed584_600x600.jpg'), ('10', 'sdfasdf', '基本面顶戴', '<img src=\"/data/upload/0j/4p/56f009deed584_600x600.jpg\" alt=\"\" />基本面塔顶地sf sdf asdf asd asdf', '0', '1458571747', '8', 'upload/0j/4p/56f009deed584_600x600.jpg'), ('11', 'sdfasdf', '基本面顶戴', '<img src=\"/data/upload/0j/4p/56f009deed584_600x600.jpg\" alt=\"\" />基本面塔顶地sf sdf asdf asd asdf', '0', '1458571747', '8', 'upload/0j/4p/56f009deed584_600x600.jpg'), ('12', 'sdfasdf', '基本面顶戴', '<img src=\"/data/upload/0j/4p/56f009deed584_600x600.jpg\" alt=\"\" />基本面塔顶地sf sdf asdf asd asdf', '0', '1458571747', '8', 'upload/0j/4p/56f009deed584_600x600.jpg'), ('13', 'sdfasdf', '基本面顶戴', '<img src=\"/data/upload/0j/4p/56f009deed584_600x600.jpg\" alt=\"\" />基本面塔顶地sf sdf asdf asd asdf', '0', '1458571747', '8', 'upload/0j/4p/56f009deed584_600x600.jpg'), ('14', 'sdfasdf', '基本面顶戴', '<img src=\"/data/upload/0j/4p/56f009deed584_600x600.jpg\" alt=\"\" />基本面塔顶地sf sdf asdf asd asdf', '0', '1458571747', '8', 'upload/0j/4p/56f009deed584_600x600.jpg'), ('15', 'sdfasdf', '基本面顶戴', '<img src=\"/data/upload/0j/4p/56f009deed584_600x600.jpg\" alt=\"\" />基本面塔顶地sf sdf asdf asd asdf', '0', '1458571747', '6', 'upload/0j/4p/56f009deed584_600x600.jpg'), ('16', 'sdfasdf', '基本面顶戴', '<img src=\"/data/upload/0j/4p/56f009deed584_600x600.jpg\" alt=\"\" />基本面塔顶地sf sdf asdf asd asdf', '0', '1458571747', '6', 'upload/0j/4p/56f009deed584_600x600.jpg');
COMMIT;

-- ----------------------------
--  Table structure for `env`
-- ----------------------------
DROP TABLE IF EXISTS `env`;
CREATE TABLE `env` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `sort_order` tinyint(6) unsigned NOT NULL DEFAULT '0',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `cid` int(10) NOT NULL DEFAULT '0',
  `type` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `env`
-- ----------------------------
BEGIN;
INSERT INTO `env` VALUES ('5', '教学大楼', 'upload/4u/7j/569f99b1a63f0.jpg', '2', '1', '1453300145', '11', 'env'), ('6', '操场', 'upload/1d/8e/569f99c3f1077.jpg', '2', '1', '1453300163', '12', 'env'), ('7', '一号教学楼', 'upload/8t/6t/56a63e8c8fa94.jpg', '2', '1', '1453735564', '11', 'env'), ('8', '要栽塔顶 栽 ', 'upload/8s/0z/56a6418fa0485.jpg', '0', '1', '1453736335', '12', 'env'), ('9', '基本面asdf ', 'upload/9l/7x/56a641998cd0b.jpg', '0', '1', '1453736345', '11', 'env'), ('10', '教学大楼', 'upload/9a/7d/56a78e82578a2.jpg', '1', '1', '1453821570', '12', 'env'), ('11', '顶戴班', 'upload/7m/9i/56a78f54a5c1b.jpg', '1', '1', '1453821780', '0', 'catalog'), ('12', '教学大楼', 'upload/4q/5r/56a791cf5d45c.jpg', '0', '1', '1453822415', '0', 'catalog'), ('13', '', '', '0', '1', '1458665048', '0', '');
COMMIT;

-- ----------------------------
--  Table structure for `jobs`
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL DEFAULT '',
  `company` varchar(255) NOT NULL DEFAULT '',
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  `number` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `others` varchar(1500) NOT NULL DEFAULT '',
  `jobdesc` varchar(1500) NOT NULL DEFAULT '',
  `compdesc` varchar(1500) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `jobs`
-- ----------------------------
BEGIN;
INSERT INTO `jobs` VALUES ('14', '老龄老师', '广西大学', '1', '1458665652', '3', '21232322', '大学路88号', '学历要求：大专\r\n专业要求：大专\r\n职位要求：园林/景观设计\r\n工作地址：南宁盘岭路6号金凯苑\r\n福利待遇：周末双休，加班补助', '岗位职责：\r\n1、组织落实景观工程设计工作，控制图纸及变更的交付进度、深度及合理性；\r\n2、编制、审查景观工程计划、，制定设计标准，完善景观设计，控制景观实施效果；\r\n3、负责景观工程竣工验收、监控施工进度、质量，解决相关技术问题。\r\n4、工程施工图及竣工图的绘制。\r\n任职资格：\r\n1、建筑工程、园林、园艺、艺术设计类相关专业大学专科及以上学历；\r\n2、2年以上装修及景观设计相关领域工作经验；\r\n3、熟悉苗木特性，善于现场布景，能独立组织景观项目的设计和施工工作，能有效的控制工期和景观实施效果，熟 练使用电脑办公软件及绘图专业软件；\r\n4、具备专业创新能力、掌握高效的工作方法，具备良好的协调能力，具有极强的敬业精神和责任心。', '公司成立于2009年，注册资本500万元，现主要从事单位、学校、医院等园林绿化设计、施工养护、后勤服务；一直专注经营城市园林绿化工程、市政公用工程、房屋建筑工程、建筑装修装饰工程、环保设计的设计施工（以上项目凭资质证经营）；园林绿化养护服务；花卉苗木、盆景的销售；公司一贯坚持热忱对待每一位客户并认真负责到底、超出客户预想、信任和尊重员工个人并注重专业高效的团队精神，因公司发展需要热忱欢迎有志之士加盟共谋发展。'), ('15', '本面基本面霜期  ', '广西大学', '1', '1458665744', '8', '5464523', '大学路88号', '职位要求：园林/景观设计\r\n工作地址：南宁盘岭路6号金凯苑\r\n福利待遇：周末双休，加班补助', '岗位职责：\r\n1、组织落实景观工程设计工作，控制图纸及变更的交付进度、深度及合理性；\r\n2、编制、审查景观工程计划、，制定设计标准，完善景观设计，控制景观实施效果；\r\n3、负责景观工程竣工验收、监控施工进度、质量，解决相关技术问题。\r\n4、工程施工图及竣工图的绘制。', '公司成立于2009年，注册资本500万元，现主要从事单位、学校、医院等园林绿化设计、施工养护、后勤服务；一直专注经营城市园林绿化工程、市政公用工程、房屋建筑工程、建筑装修装饰工程、环保设计的设计施工（以上项目凭资质证经营）；园林绿化养护服务；花卉苗木、盆景的销售；公司一贯坚持热忱对待每一位客户并认真负责到底、超出客户预想、信任和尊重员工个人并注重专业高效的团队精神，因公司发展需要热忱欢迎有志之士加盟共谋发展。');
COMMIT;

-- ----------------------------
--  Table structure for `links`
-- ----------------------------
DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `sitename` varchar(60) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `category` varchar(20) NOT NULL DEFAULT '',
  `sort_order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `links`
-- ----------------------------
BEGIN;
INSERT INTO `links` VALUES ('1', '肯德基', 'http://www.zbgogogo.com/about.html', '', 'business', '5'), ('3', '麦当劳', 'http://www.zbgogogo.com/about.html', '', 'business', '5'), ('4', '菜茶茉', 'http://nn.zbgogogo.com/search/?key=%E8%8F%9C%E8%8C%B6%E8%8C%89', '', 'business', '15'), ('5', '佳百旺', 'http://nn.zbgogogo.com/search/?key=%E4%BD%B3%E7%99%BE%E6%97%BA', '', 'business', '6'), ('6', '玲莉', 'http://nn.zbgogogo.com/search/?key=%E7%8E%B2%E8%8E%89', '', 'business', '2'), ('8', 'today', 'http://www.zbgogogo.com/about.html', '', 'business', '16'), ('9', '大维', 'http://nn.zbgogogo.com/search/?key=%E5%A4%A7%E7%BB%B4', '', 'business', '14'), ('11', '新浪微博', 'http://weibo.com/2750797275/profile?topnav=1&wvr=5', '', 'friendlink', '0'), ('12', '腾讯微博', 'http://t.qq.com/xinshijiechuanbo/mine', '', 'friendlink', '0'), ('19', '桶天香', 'http://nn.zbgogogo.com/search/?key=%E6%A1%B6%E5%A4%A9%E9%A6%99', '', 'business', '10'), ('20', '全上品', 'http://nn.zbgogogo.com/search/?key=%E5%85%A8%E4%B8%8A%E5%93%81', '', 'business', '7'), ('21', '养生汤吧', 'http://nn.zbgogogo.com/search/?key=%E5%85%BB%E7%94%9F%E6%B1%A4%E5%90%A7', '', 'business', '3'), ('23', '易乐宝', 'http://nn.zbgogogo.com/search/?key=%E6%98%93%E4%B9%90%E5%AE%9D', '', 'business', '9'), ('24', '城市佳厨', 'http://nn.zbgogogo.com/search/?key=%E5%9F%8E%E5%B8%82%E4%BD%B3%E5%8E%A8', '', 'business', '1'), ('25', '榜样', 'http://nn.zbgogogo.com/search/?key=%E6%A6%9C%E6%A0%B7', '', 'business', '4'), ('27', '华莱士', 'http://www.zbgogogo.com/contact.html', '', 'business', '9'), ('28', '真功夫', 'http://nn.zbgogogo.com/search/?key=%E7%9C%9F%E5%8A%9F%E5%A4%AB', '', 'business', '8'), ('29', '威尔小站', 'http://nn.zbgogogo.com/search/?key=%E5%A8%81%E5%B0%94%E5%B0%8F%E7%AB%99', '', 'business', '13'), ('30', '腾讯空间', 'http://user.qzone.qq.com/1826328783/main', '', 'friendlink', '3');
COMMIT;

-- ----------------------------
--  Table structure for `msgboard`
-- ----------------------------
DROP TABLE IF EXISTS `msgboard`;
CREATE TABLE `msgboard` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(100) NOT NULL DEFAULT '',
  `mobile` varchar(20) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `content` varchar(1000) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `store_id` (`name`),
  KEY `user_id` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `msgboard`
-- ----------------------------
BEGIN;
INSERT INTO `msgboard` VALUES ('12', '张三', 'ewrwrqw@dnvdv.cn', '223232221', ' sdfssdasdsdf枯', '要dfasd ', '172.31.0.1', '1439474730');
COMMIT;

-- ----------------------------
--  Table structure for `product_orders`
-- ----------------------------
DROP TABLE IF EXISTS `product_orders`;
CREATE TABLE `product_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL DEFAULT '0',
  `product_name` varchar(255) NOT NULL DEFAULT '',
  `contact` varchar(60) NOT NULL DEFAULT '',
  `phone` varchar(20) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `product_orders`
-- ----------------------------
BEGIN;
INSERT INTO `product_orders` VALUES ('2', '3', '435345', 'sdfwef', '1231231242', 'sdfadsf adsfadsf', '1439477091'), ('3', '3', '435345', '联系我', '121242322', '村有顶起仍城', '1439560009'), ('4', '3', '435345', '月报表', '652352439', '枯基本面基本面厅asdf工', '1439560219');
COMMIT;

-- ----------------------------
--  Table structure for `products`
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `images` varchar(2000) NOT NULL DEFAULT '',
  `detail` text NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort_order` smallint(5) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `products`
-- ----------------------------
BEGIN;
INSERT INTO `products` VALUES ('2', 'sdfasdf s', '[\"upload\\/5l\\/3d\\/55ca1f93e613a.jpg\",\"\",\"\",\"\"]', '<p>asd fasd fasdf <br /></p><p>asd f</p><p>a</p><p>sdf</p><p>asd</p><p>f <br /></p><p>asd</p><p>f a</p><p>sdf<br /></p>', '1', '22', '1439305472'), ('3', '435345', '[\"upload\\/7u\\/5v\\/55ca22ee5cb64.jpg\",\"upload\\/6h\\/7m\\/55ca244e30b16.jpg\",\"upload\\/3q\\/9k\\/55ca2567349f5.jpg\",\"upload\\/2z\\/3v\\/55dc6ac7be0d4.jpg\",\"upload\\/3t\\/3u\\/55dc6ac7c4279.jpg\"]', '<p>f fsadf asdfasdfasdf</p><p>asd <br /></p><p>a</p><p>sdf <br /></p><p><img src=\"/data/upload/5s/6u/55d0887037818_380x380.jpg\" alt=\"\" /><br /></p><p>asd</p><p>f <br /></p><p>asdf</p><p>&nbsp;a</p><p>sdf</p><p>adsf<br /></p>', '1', '255', '1439309175'), ('4', 'qwqe', '[\"upload\\/8n\\/0o\\/55dc680f2941f.jpg\"]', 'r dsf asdf asf <br />', '1', '6', '1440507919'), ('5', '顶戴班', '[\"upload\\/2o\\/4e\\/569fa443415ec.jpg\"]', '&nbsp;裁夺 asdfasdfasdfasdf基本面地', '1', '1', '1453302851'), ('6', '顶戴班', '[\"upload\\/5k\\/7b\\/569fa4a754333.jpg\"]', 'asdfasdfasdf棋东奔西走fads &nbsp;东奔西走', '1', '1', '1453302951');
COMMIT;

-- ----------------------------
--  Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `skey` varchar(60) NOT NULL DEFAULT '',
  `svalue` varchar(800) NOT NULL DEFAULT '',
  PRIMARY KEY (`skey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `settings`
-- ----------------------------
BEGIN;
INSERT INTO `settings` VALUES ('company_address', ''), ('company_name', ''), ('company_phone', '0771-23234234'), ('footer_content', '版权归属：广西南宁市长恒文化传播有限公司\r\n地址：南宁市银海大道金象四区春阳路109号\r\n服务热线：0771-4920772  /  4920773    传真：0771-4920773\r\n我们提供万无一失的学习保障\r\n备案号:桂ICP备14002506号   ©2015   DuoLeCaiHS.com All Rights Reserved.'), ('kefuqq', '15644666|客服A,81635191|客服B,71585658|客服C'), ('notify_email', '254160620@qq.com'), ('seo_description', '巧教育'), ('seo_keywords', '巧教育'), ('site_name', '巧教育'), ('stat_code', '');
COMMIT;

-- ----------------------------
--  Table structure for `slider`
-- ----------------------------
DROP TABLE IF EXISTS `slider`;
CREATE TABLE `slider` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `target` varchar(255) NOT NULL DEFAULT '',
  `image` varchar(255) NOT NULL DEFAULT '',
  `position` varchar(20) NOT NULL DEFAULT '',
  `sort_order` smallint(5) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `position` (`position`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `slider`
-- ----------------------------
BEGIN;
INSERT INTO `slider` VALUES ('7', '', 'http://www.zbgogogo.com/healthy/', 'upload/9r/5j/56f2997f7552c.jpg', 'index', '4'), ('8', '', '', 'upload/4m/9t/56f2997732df5.jpg', 'index', '0'), ('9', '', 'DFSF', 'upload/8z/6q/56f2995b44fe7.jpg', 'index', '0');
COMMIT;

-- ----------------------------
--  Table structure for `teacher`
-- ----------------------------
DROP TABLE IF EXISTS `teacher`;
CREATE TABLE `teacher` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `image` varchar(200) NOT NULL DEFAULT '',
  `gender` varchar(5) NOT NULL DEFAULT '',
  `school` varchar(500) NOT NULL DEFAULT '',
  `subject` varchar(500) NOT NULL DEFAULT '',
  `teach_age` smallint(6) NOT NULL DEFAULT '0',
  `video` varchar(500) NOT NULL DEFAULT '',
  `detail` text NOT NULL,
  `state` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `sort_order` smallint(5) unsigned NOT NULL,
  `dateline` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `teacher`
-- ----------------------------
BEGIN;
INSERT INTO `teacher` VALUES ('5', '张三三', 'upload/2j/1b/569fa8a985491.jpg', '女', 'sdfsdfadfasd夺基地 ', '素描', '6', 'f地苦苦东奔西走基本面基基苦基基本面夺苦asdd棋工在', '左右基苦adsadsf在奇葩朝秦暮楚 基', '1', '1', '1453303977'), ('6', '顶戴班', 'upload/7x/8r/56a782efbd6ed.jpg', '女', '广西艺术学院', '写生', '3', '', 'effa顶戴 苛基本面&nbsp;', '1', '0', '1453818607'), ('7', '张国桌', 'upload/5l/1a/56a783a630640.jpg', '男', '广州艺术学院', '要城', '8', '', '研左基本面工 工', '1', '0', '1453818790');
COMMIT;

-- ----------------------------
--  Table structure for `users`
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(60) NOT NULL DEFAULT '',
  `password` char(32) NOT NULL DEFAULT '',
  `passwdkey` char(8) NOT NULL DEFAULT '',
  `email` varchar(160) NOT NULL DEFAULT '',
  `nickname` varchar(60) NOT NULL DEFAULT '',
  `gender` varchar(2) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `login_time` int(11) NOT NULL DEFAULT '0',
  `login_ip` varchar(64) NOT NULL DEFAULT '',
  `login_count` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `users`
-- ----------------------------
BEGIN;
INSERT INTO `users` VALUES ('1', 'admin', 'cf9d717eb9d4d99f8b42acf3fbac75f4', 'RAt2uicx', 'admin@example.com', '管理员', '男', '1', '1', '0', '0', '', '0'), ('2', 'test', '8ad9e1fde32f9a05edb9cd6909f1ba4e', 'lfvqxydu', 'test@test.cn', '外卖仔', '', '1', '0', '1383485415', '1383485434', '113.14.216.168', '1'), ('3', 'ken', '114401dca8cb97f22fa857ff011dfce7', 'flzemori', '623198951@qq.com', '阿德', '', '1', '0', '1383655740', '1383655822', '222.84.76.234', '1'), ('4', 'zbgogogo', '35609e75829b23732fc8363964d42a55', 'jeytigsq', '1826328783@qq.com', '周边网', '', '1', '0', '1385473103', '1385473103', '113.12.103.16', '1'), ('5', '10000', 'dabf38b91024ac19ac4d39ea19706b68', 'rpdbeysh', '1074330335@qq.com', '玲', '', '1', '0', '1385554535', '1385554535', '119.136.138.69', '1'), ('6', '374267347@qq.com', '8e95db5bb8ec29c6e8881fcc2475ec20', 'tkfdbjxe', '374267347@qq.com', 'V1p扯淡', '', '1', '0', '1387023992', '1387162107', '171.36.68.186', '2'), ('7', '开心的一博', '3a87bfa5a62aa4d41fe3b3403266b143', 'wkqujmgd', '987959927@qq.com', '开心的一博', '', '1', '0', '1387029855', '1387029855', '171.36.82.95', '1'), ('8', 'Sophia', 'a80de23e719f7afd691ab5718a0745dc', 'fcgvoben', 'Sophia_1126@qq.com', '阳光', '', '1', '0', '1387282230', '1387282231', '182.88.186.210', '1'), ('9', 'aaaaa', 'cc3e75d71fe406e64d6ee12b2f79776a', 'oysntwmq', 'sss@ass.cn', 'bbbbb', '', '1', '0', '1388931600', '1388931601', '116.252.120.221', '1'), ('10', 'xu_qi22@126.com', 'b0f1d2e2a9e2b61b82dd5a0f1c0fa516', 'omakwixu', 'xu_qi22@126.com', '郁影77', '', '1', '0', '1389585809', '1389585809', '116.252.69.192', '1'), ('11', 'leilihuan', '0ca666ac541e1e94ecf72d6eca459924', 'whrbejif', 'leilihuan@163.com', '小李', '', '1', '0', '1389612433', '1389612433', '222.216.56.86', '1'), ('12', 'qq229364364', '23af2a0cea8886fa1a199cfa71a80214', 'okrwysib', '229364364@qq.com', '谢晓新', '', '1', '0', '1389843677', '1389843677', '171.37.47.193', '1'), ('13', '15678823321', '7e146ddac4a2fc70540cb27f8882b39d', 'dkyucrme', '327131372@qq.com', '方琪', '', '1', '0', '1389865687', '1389865687', '117.136.14.83', '1'), ('14', '2514390846', 'f55ea7fd4cd83a2081f654c8e2908fc4', 'pjtuyihl', '2514390846@qq.com', '汪汪汪', '', '1', '0', '1390205830', '1390205830', '220.173.32.90', '1');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
