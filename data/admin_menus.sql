/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50625
 Source Host           : localhost
 Source Database       : huashi

 Target Server Type    : MySQL
 Target Server Version : 50625
 File Encoding         : utf-8

 Date: 01/26/2016 23:55:46 PM
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
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `admin_menus`
-- ----------------------------
BEGIN;
INSERT INTO `admin_menus` VALUES ('1', '管理首页', '', '', '', '', '0', '1', '1', ''), ('2', '教学资源', '', '', '', '', '0', '3', '1', ''), ('3', '用户管理', '', '', '', '', '0', '5', '0', ''), ('4', '内容管理', '', '', '', '', '0', '2', '1', ''), ('5', '节点管理', '', 'AdminMenus', 'index', '', '1', '9999', '1', ''), ('6', '系统设置', '', 'Setting', 'index', '', '1', '3', '1', ''), ('7', '产品列表', '', 'Product', 'index', '', '2', '1', '0', ''), ('8', '添加产品', '', 'Product', 'add', '', '2', '2', '0', ''), ('9', '文章列表', '', 'Article', 'index', '', '4', '1', '1', ''), ('10', '友情链接', '', 'Link', 'index', '', '1', '6', '1', ''), ('11', '店面报错', '', 'Store', 'errors', '', '2', '3', '0', ''), ('12', '广告管理', '', 'Advertise', 'index', '', '4', '5', '0', ''), ('13', '致家长信', '', 'ArticlePage', 'topar', '', '4', '3', '1', ''), ('14', '文章分类', '', 'Article', 'category', '', '4', '2', '1', ''), ('15', '教学特色', '', 'ArticlePage', 'feature', '', '4', '5', '1', ''), ('16', '预订记录', '', 'Product', 'orders', '', '2', '3', '0', ''), ('17', '管理员用户', '', 'User', 'admin', '', '1', '1', '1', ''), ('18', '联系我们', '', 'ArticlePage', 'contact', '', '4', '5', '1', ''), ('19', '留言记录', '', 'Comment', 'index', '', '4', '5', '0', ''), ('20', '作品列表', '', 'Case', 'index', '', '24', '1', '1', ''), ('21', '招生信息', '', 'ArticlePage', 'tostu', '', '4', '4', '1', ''), ('22', '幻灯片', '', 'Slider', 'index', '', '1', '3', '1', '幻灯片幻灯片'), ('23', '添加作品', '', 'Case', 'add', '', '24', '2', '1', ''), ('24', '作品展示', '', '', '', '', '0', '4', '1', ''), ('25', '教学环境', '', 'Env', 'index', '', '2', '5', '1', ''), ('26', '添加教学环境', '', 'Env', 'add', '', '2', '6', '1', ''), ('27', '师资力量', '', 'Faculty', 'index', '', '2', '3', '1', ''), ('28', '添加教师', '', 'Faculty', 'add', '', '2', '4', '1', ''), ('29', '课程列表', '', 'Course', 'index', '', '2', '1', '1', ''), ('30', '添加课程', '', 'Course', 'add', '', '2', '2', '1', ''), ('31', '报名信息', '', 'Course', 'orders', '', '2', '9', '1', ''), ('32', '画室简介', '', 'ArticlePage', 'about', '', '4', '2', '1', ''), ('33', '添加作品分类', '', 'Case', 'addcatalog', '', '24', '4', '1', ''), ('34', '作品分类', '', 'Case', 'catalog', '', '24', '3', '1', ''), ('35', '环境分类', '', 'Env', 'catalog', '', '2', '6', '1', ''), ('36', '添加环境', '', 'Env', 'addcatalog', '', '2', '8', '1', '');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
