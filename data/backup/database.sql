/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50524
Source Host           : localhost:3306
Source Database       : cart

Target Server Type    : MYSQL
Target Server Version : 50524
File Encoding         : 65001

Date: 2013-06-20 23:44:47
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `admin`
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin
-- ----------------------------
INSERT INTO `admin` VALUES ('1', 'admin', '0db29d9f6881308351aa13f9261203c8', 'XanOhFRd', '管理员', 'admin@admin.com', '', '1', '127.0.0.1', '14', '1371740939', '1367845114');

-- ----------------------------
-- Table structure for `admin_menus`
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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of admin_menus
-- ----------------------------
INSERT INTO `admin_menus` VALUES ('1', '管理首页', '', '', '', '', '0', '1', '1', '');
INSERT INTO `admin_menus` VALUES ('2', '产品管理', '', '', '', '', '0', '2', '1', '');
INSERT INTO `admin_menus` VALUES ('3', '用户管理', '', '', '', '', '0', '3', '0', '');
INSERT INTO `admin_menus` VALUES ('4', '内容管理', '', '', '', '', '0', '4', '1', '');
INSERT INTO `admin_menus` VALUES ('5', '节点管理', '', 'AdminMenus', 'index', '', '1', '2', '1', '');
INSERT INTO `admin_menus` VALUES ('6', '系统设置', '', 'Setting', 'index', '', '1', '3', '1', '');
INSERT INTO `admin_menus` VALUES ('7', '产品列表', '', 'Product', 'index', '', '2', '1', '1', '');
INSERT INTO `admin_menus` VALUES ('8', '区域划分', '', 'District', 'index', '', '2', '3', '0', '');
INSERT INTO `admin_menus` VALUES ('9', '文章列表', '', 'Article', 'index', '', '4', '1', '1', '');
INSERT INTO `admin_menus` VALUES ('10', '友情链接', '', 'Link', 'index', '', '4', '5', '0', '');
INSERT INTO `admin_menus` VALUES ('11', '幻灯片', '', 'Slider', 'index', '', '4', '7', '1', '');
INSERT INTO `admin_menus` VALUES ('12', '广告管理', '', 'Advertise', 'index', '', '4', '5', '0', '');
INSERT INTO `admin_menus` VALUES ('13', '关于我们', '', 'ArticlePage', 'aboutus', '', '4', '3', '0', '');
INSERT INTO `admin_menus` VALUES ('14', '文章栏目', '', 'Article', 'category', '', '4', '2', '1', '');
INSERT INTO `admin_menus` VALUES ('15', '用户列表', '', 'User', 'index', '', '3', '2', '1', '');
INSERT INTO `admin_menus` VALUES ('16', '分类管理', '', 'Product', 'category', '', '2', '2', '1', '');
INSERT INTO `admin_menus` VALUES ('17', '管理员用户', '', 'User', 'admin', '', '3', '1', '1', '');
INSERT INTO `admin_menus` VALUES ('18', '联络我们', '', 'ArticlePage', 'contact', '', '4', '3', '1', '');
INSERT INTO `admin_menus` VALUES ('19', '免责声明', '', 'ArticlePage', 'terms', '', '4', '3', '0', '');
INSERT INTO `admin_menus` VALUES ('20', '品牌管理', '', 'Product', 'brand', '', '2', '2', '0', '');
INSERT INTO `admin_menus` VALUES ('21', '招贤纳士', '', 'Jobs', 'index', '', '4', '8', '1', '');

-- ----------------------------
-- Table structure for `advertise`
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of advertise
-- ----------------------------

-- ----------------------------
-- Table structure for `articles`
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
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
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of articles
-- ----------------------------

-- ----------------------------
-- Table structure for `article_category`
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
  PRIMARY KEY (`id`),
  KEY `code` (`catalog`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article_category
-- ----------------------------
INSERT INTO `article_category` VALUES ('1', '新闻中心', 'news', '', '新闻中心新闻中心', '0', '', '1');
INSERT INTO `article_category` VALUES ('2', '关于我们', 'about', '', '关于我们关于我们关于我们', '0', '', '2');
INSERT INTO `article_category` VALUES ('3', '新闻1', 'news', '', '1111', '1', '', '1');
INSERT INTO `article_category` VALUES ('4', '新闻2', 'news', '', 'asdfasdfasdsd ', '1', '', '2');
INSERT INTO `article_category` VALUES ('5', '关于', 'about', '', 'sddas  fwesdf', '2', '', '1');
INSERT INTO `article_category` VALUES ('6', '我们', 'about', '', '棋苛塔顶地', '2', '', '3');
INSERT INTO `article_category` VALUES ('7', '洒在要', 'news', '', 'sd afsd asd fasd ', '1', '', '4');

-- ----------------------------
-- Table structure for `article_page`
-- ----------------------------
DROP TABLE IF EXISTS `article_page`;
CREATE TABLE `article_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `page_code` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `keywords` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(1000) NOT NULL DEFAULT '',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `visit_count` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `page_code` (`page_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of article_page
-- ----------------------------
INSERT INTO `article_page` VALUES ('1', 'contact', '联系我们', '<p>联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联</p><p align=\"center\"><img src=\"/cart/data/upload/70/91/51b6ff1178437_600x480.jpg\" alt=\"\" /><br /></p><p>系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们</p>', '', '', '我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们联系我们', '1370947374', '23');

-- ----------------------------
-- Table structure for `jobs`
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `requirement` text NOT NULL,
  `description` text NOT NULL,
  `number` smallint(5) unsigned NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of jobs
-- ----------------------------
INSERT INTO `jobs` VALUES ('1', '业务员', '专业要求：服务专业\n学历要求：中专以上\n年龄要求：18—35岁\n性别要求：男性\n工作性质：全职\n工作区域：全国\n工作经验：有一年以上工作经验者优先\n薪酬待遇：年薪5-10万（基本工资+绩效考核+年功工资+提成工资+六险+补贴+激励奖+年终奖）', '岗位职责：\n1、建立、管理、维护销售部文件数据档案，为部门制定正确的销售方案、销售计划提供决策依据；\n2、对产品销售过程的管理；\n3、草拟年季月度销售和预算计划；\n4、负责账务的核对；\n5、监督、协调各网点的营运情况。', '10', '1');
INSERT INTO `jobs` VALUES ('2', '技术员', '    专业要求：服务专业\r\n    学历要求：中专以上\r\n    年龄要求：18—35岁\r\n    性别要求：女性\r\n    工作性质：广西.南宁\r\n    工作经验：有一年以上工作经验者优先\r\n    薪酬待遇：年薪5-10万（基本工资+绩效考核+年功工资+提成工资+六险+补贴+激励奖+年终奖）', '岗位职责：\r\n1、建立、管理、维护销售部文件数据档案，为部门制定正确的销售方案、销售计划提供决策依据；\r\n2、对产品销售过程的管理；', '2', '1');
INSERT INTO `jobs` VALUES ('3', '公司前台', '    专业要求：服务专业\r\n    学历要求：中专以上\r\n    性别要求：男性\r\n    工作区域：全国\r\n    工作经验：有一年以上工作经验者优先', '岗位职责：\r\n1、建立、管理、维护销售部文件数据档案，为部门制定正确的销售方案、销售计划提供决策依据；\r\n2、对产品销售过程的管理；\r\n3、草拟年季月度销售和预算计划；\r\n4、负责账务的核对；', '1', '1');

-- ----------------------------
-- Table structure for `links`
-- ----------------------------
DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `sitename` varchar(60) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `category` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of links
-- ----------------------------

-- ----------------------------
-- Table structure for `products`
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_name` varchar(60) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `related_products` varchar(100) NOT NULL DEFAULT '',
  `cate_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `product_attrs` text,
  `detail` text,
  `create_time` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `images` text,
  PRIMARY KEY (`id`),
  KEY `cate_id` (`cate_id`),
  CONSTRAINT `FK_Reference_2` FOREIGN KEY (`cate_id`) REFERENCES `product_category` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('1', '顶戴枯花样百出棋', 'upload/38/80/51b73e5892925.jpg', '花样百出塔顶地', '', '2', '大师傅花样百出花样百出', '花样百出棋模压 <br />', '0', '1', 'upload/38/80/51b73e5892925.jpg,upload/01/03/51b73e590d4f0.jpg,upload/40/42/51b7408287855.jpg');
INSERT INTO `products` VALUES ('2', '顶戴枯花样百出棋人枯', '', '花样百出花样百出', '', '2', '花样百出花样百出花样百出枯地', '塔顶时时有仍地轩叶直睦基址 草木灰睦共产党亲友楔<br />', '1370188704', '0', null);
INSERT INTO `products` VALUES ('3', '顶戴枯花样百出棋', 'upload/20/09/51b57abc300f4.jpg', 'dsfdasf asdf', '', '5', '士大夫：枯夺 士大夫枯\r\n士大： 地士大夫\r\n饿地：厅枯 厅在', 'sad sad sd f<br />', '1370848671', '1', 'upload/20/09/51b57abc300f4.jpg,upload/69/06/51b57abc9498d.jpg');
INSERT INTO `products` VALUES ('4', '灰机机啊', 'upload/27/16/51b73ad154d2f.jpg', '缀枯花样百出柑棋档案室霜期地大师傅', '', '4', '花样百出塔顶枯棋要夺', '<p>枯花样百出士大夫士大夫</p><p align=\"center\"><img src=\"/cart/data/upload/22/45/51b73ae5ead4b_600x480.jpg\" alt=\"\" /></p>', '1370962677', '1', 'upload/27/16/51b73ad154d2f.jpg,upload/07/91/51b73ad1b8bde.jpg,upload/19/34/51b73ad21d291.jpg');

-- ----------------------------
-- Table structure for `product_category`
-- ----------------------------
DROP TABLE IF EXISTS `product_category`;
CREATE TABLE `product_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `cate_name` varchar(30) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pid` smallint(5) unsigned NOT NULL DEFAULT '0',
  `pids` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of product_category
-- ----------------------------
INSERT INTO `product_category` VALUES ('1', '铁路专用', '铁路专用铁路专用铁路专用铁路专用铁路专用铁路专用铁路专用铁路专用铁路专用铁路专用铁路专用', '1', '0', '');
INSERT INTO `product_category` VALUES ('2', '机器', '机器机器机器机器机器机器机器机器', '2', '1', '');
INSERT INTO `product_category` VALUES ('3', '天空专用', '', '2', '0', '');
INSERT INTO `product_category` VALUES ('4', '灰机', '灰机灰机灰机灰机灰机灰机灰机灰机灰机灰机灰机灰机灰机', '1', '3', '');
INSERT INTO `product_category` VALUES ('5', '直通车', '直通车直通车直通车直通车直通车直通车直通车直通车直通车直通车直通车直通车直通车直通车直通车直通车直通车', '2', '1', '');

-- ----------------------------
-- Table structure for `settings`
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `skey` varchar(60) NOT NULL DEFAULT '',
  `svalue` varchar(800) NOT NULL DEFAULT '',
  `options` varchar(500) NOT NULL DEFAULT '',
  `remark` varchar(255) NOT NULL DEFAULT '',
  `input_type` varchar(20) NOT NULL DEFAULT '',
  `input_style` varchar(500) NOT NULL DEFAULT '',
  `category` varchar(20) NOT NULL DEFAULT '',
  PRIMARY KEY (`skey`),
  KEY `category` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES ('address', '广州城铁机械路170号科德五金机电城 7栋B2-B4号', '', '', '', '', '');
INSERT INTO `settings` VALUES ('admin_email', '', '', '', '', '', '');
INSERT INTO `settings` VALUES ('admin_qq', '', '', '', '', '', '');
INSERT INTO `settings` VALUES ('client_update_url', '', '', '', '', '', '');
INSERT INTO `settings` VALUES ('client_version', '', '', '', '', '', '');
INSERT INTO `settings` VALUES ('contact_phone', '020-279123787', '', '', '', '', '');
INSERT INTO `settings` VALUES ('copyright', 'Copyright ©2013 All Rights Reserved.', '', '', '', '', '');
INSERT INTO `settings` VALUES ('hot_keywords', '', '', '', '', '', '');
INSERT INTO `settings` VALUES ('icp_info', '', '', '', '', '', '');
INSERT INTO `settings` VALUES ('jobs_contact', '联系人：陈小姐\r\n联系电话：0771-3219952\r\n详细地址：广州大道41号国贸中心A座28楼2812\r\n投递邮箱：rzp@163.com g', '', '', '', '', '');
INSERT INTO `settings` VALUES ('jobs_description', '在新兴经济体，卡特彼勒机械正被用来建设道路，机场，医院和排污系统，创造经济机会和提高生活水平。在发达经济体，卡特彼勒网络的经销商与顾客一起共同创新解决方案。gggggg', '', '', '', '', '');
INSERT INTO `settings` VALUES ('kefu_phone', '020-679123787', '', '', '', '', '');
INSERT INTO `settings` VALUES ('min_withdraw', '0', '', '', '', '', '');
INSERT INTO `settings` VALUES ('seo_description', '', '', '', '', '', '');
INSERT INTO `settings` VALUES ('seo_keywords', '', '', '', '', '', '');
INSERT INTO `settings` VALUES ('sitename', '广州城铁机械', '', '', '', '', '');
INSERT INTO `settings` VALUES ('stat_code', '', '', '', '', '', '');
INSERT INTO `settings` VALUES ('web_admin_pagenum', '0', '', '', '', '', '');

-- ----------------------------
-- Table structure for `slider`
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
  KEY `position` (`position`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of slider
-- ----------------------------
INSERT INTO `slider` VALUES ('1', '村大梵蒂冈', 'http://test.com/sdfdfdfd', 'upload/04/46/51c31e1a71b68.jpg', 'index', '1');
