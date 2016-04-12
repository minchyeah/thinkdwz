/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MySQL
 Source Server Version : 50625
 Source Host           : localhost
 Source Database       : hanshoujiaoyu

 Target Server Type    : MySQL
 Target Server Version : 50625
 File Encoding         : utf-8

 Date: 04/13/2016 00:55:26 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- ----------------------------
--  Records of `article_category`
-- ----------------------------
BEGIN;
INSERT INTO `article_category` VALUES ('1', '网站公告', 'news', '', '', '0', '', '3', '1', '0'), ('2', '网络教育', 'webucation', '', 'df', '0', '', '1', '1', '0'), ('3', '成考资料', 'material', '', '', '0', '', '2', '1', '0'), ('4', '报名指南', 'guide', '', '', '0', '', '4', '1', '0'), ('5', '招生简章', 'recruit', '', '', '0', '', '5', '1', '0'), ('6', '历年考试', 'liniankaoshi', '', '', '0', '', '6', '1', '0'), ('7', '专业介绍', 'major', '', '', '0', '', '0', '0', '0'), ('8', '工程大类', 'gxdl', '', '', '7', '7', '0', '1', '1'), ('9', '经管大类', '2', '', '', '7', '7', '2', '1', '1'), ('10', '农林大类', '3', '', '', '7', '7', '3', '1', '1'), ('11', '艺术大类', '4', '', '', '7', '7', '4', '1', '1'), ('13', '人文大类', 'rwdl', '', '', '7', '7', '5', '1', '1'), ('14', '成人高考', 'chengrengaokao', '', '', '0', '', '7', '1', '0'), ('15', '资格证书报考资讯', 'zsnews', '', '', '0', '', '8', '1', '0');
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
