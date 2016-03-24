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

 Date: 03/24/2016 00:08:06 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

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
INSERT INTO `admin_menus` VALUES ('1', '管理首页', '', '', '', '', '0', '1', '1', ''), ('2', '教学资源', '', '', '', '', '0', '3', '1', ''), ('3', '用户管理', '', '', '', '', '0', '5', '0', ''), ('4', '内容管理', '', '', '', '', '0', '2', '1', ''), ('5', '节点管理', '', 'AdminMenus', 'index', '', '1', '9999', '1', ''), ('6', '系统设置', '', 'Setting', 'index', '', '1', '3', '1', ''), ('7', '产品列表', '', 'Product', 'index', '', '2', '1', '0', ''), ('8', '添加产品', '', 'Product', 'add', '', '2', '2', '0', ''), ('9', '文章内容', '', 'Article', 'index', '', '4', '1', '1', ''), ('10', '友情链接', '', 'Link', 'index', '', '1', '6', '1', ''), ('11', '店面报错', '', 'Store', 'errors', '', '2', '3', '0', ''), ('12', '广告管理', '', 'Advertise', 'index', '', '4', '5', '1', ''), ('13', '服务', '', 'ArticlePage', 'service', '', '4', '3', '1', ''), ('14', '文章分类', '', 'Article', 'category', '', '4', '2', '1', ''), ('15', '教学特色', '', 'ArticlePage', 'feature', '', '4', '5', '0', ''), ('16', '预订记录', '', 'Product', 'orders', '', '2', '3', '0', ''), ('17', '管理员用户', '', 'User', 'admin', '', '1', '1', '1', ''), ('18', '联系我们', '', 'ArticlePage', 'contact', '', '4', '5', '0', ''), ('19', '留言记录', '', 'Comment', 'index', '', '4', '5', '1', ''), ('20', '作品列表', '', 'Case', 'index', '', '24', '1', '0', ''), ('21', '招聘信息', '', 'Jobs', 'index', '', '24', '4', '1', ''), ('22', '幻灯片', '', 'Slider', 'index', '', '1', '3', '1', '幻灯片幻灯片'), ('23', '添加作品', '', 'Case', 'add', '', '24', '2', '0', ''), ('24', '名企招聘', '', '', '', '', '0', '4', '1', ''), ('25', '教学环境', '', 'Env', 'index', '', '2', '5', '0', ''), ('26', '添加教学环境', '', 'Env', 'add', '', '2', '6', '0', ''), ('27', '师资力量', '', 'Faculty', 'index', '', '2', '3', '1', ''), ('28', '添加教师', '', 'Faculty', 'add', '', '2', '4', '1', ''), ('29', '课程列表', '', 'Course', 'index', '', '2', '1', '1', ''), ('30', '添加课程', '', 'Course', 'add', '', '2', '2', '1', ''), ('31', '报名信息', '', 'Course', 'orders', '', '2', '9', '1', ''), ('32', '添加招聘信息', '', 'Jobs', 'add', '', '24', '5', '1', ''), ('33', '添加作品分类', '', 'Case', 'addcatalog', '', '24', '4', '0', ''), ('34', '作品分类', '', 'Case', 'catalog', '', '24', '3', '0', ''), ('35', '环境分类', '', 'Env', 'catalog', '', '2', '6', '0', ''), ('36', '添加环境', '', 'Env', 'addcatalog', '', '2', '8', '0', ''), ('37', '开班信息', '', 'Course', 'cls', '', '2', '0', '1', ''), ('38', '增加开班信息', '', 'Course', 'addcls', '', '2', '0', '1', '');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
