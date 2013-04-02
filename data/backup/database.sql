drop table if exists admin_menus;

drop table if exists article_category;

drop table if exists article_page;

drop table if exists articles;

drop table if exists district;

drop table if exists district_location;

drop table if exists links;

drop table if exists locations;

drop table if exists settings;

drop table if exists store_category;

drop table if exists store_menus;

drop table if exists stores;

drop table if exists users;

/*==============================================================*/
/* Table: admin_menus                                           */
/*==============================================================*/
create table admin_menus
(
   id                   int unsigned not null auto_increment,
   title                varchar(20) not null default '',
   group_name           varchar(20) not null default '',
   module_name          varchar(20) not null default '',
   action_name          varchar(20) not null default '',
   params               varchar(255) not null default '',
   pid                  int unsigned not null default 0,
   sort_order           smallint not null default 0,
   display              tinyint(1) not null default 1,
   remark               varchar(500) not null default '',
   primary key (id),
   key pid (pid)
);

/*==============================================================*/
/* Table: article_category                                      */
/*==============================================================*/
create table article_category
(
   id                   smallint unsigned not null auto_increment,
   cate_name            varchar(60) not null default '',
   code                 varchar(20) not null default '',
   keywords             varchar(255) not null default '',
   description          varchar(1000) not null default '',
   primary key (id),
   key code (code)
);

/*==============================================================*/
/* Table: article_page                                          */
/*==============================================================*/
create table article_page
(
   id                   int unsigned not null auto_increment,
   page_code            varchar(20) not null default '',
   title                varchar(255) not null default '',
   content              text,
   thumb                varchar(255) not null default '',
   keywords             varchar(255) not null default '',
   description          varchar(1000) not null default '',
   create_time          int unsigned not null default 0,
   visit_count          int unsigned not null default 0,
   primary key (id),
   key page_code (page_code)
);

/*==============================================================*/
/* Table: articles                                              */
/*==============================================================*/
create table articles
(
   id                   int unsigned not null auto_increment,
   title                varchar(255) not null default '',
   content              text,
   keywords             varchar(255) not null default '',
   description          varchar(1000) not null default '',
   create_time          int unsigned not null default 0,
   visit_count          int unsigned not null default 0,
   user_id              int unsigned not null default 0,
   author               varchar(60) not null default '',
   from_url             varchar(255) not null default '',
   thumb                varchar(255) not null default '',
   primary key (id)
);

/*==============================================================*/
/* Table: district                                              */
/*==============================================================*/
create table district
(
   id                   int unsigned not null auto_increment,
   title                varchar(60) not null default '',
   code                 varchar(60) not null default '',
   remark               varchar(255) not null default '',
   pid                  int unsigned not null default 0,
   letter               char(1) not null default '',
   primary key (id),
   key letter (letter)
);

alter table district comment '城区或自定义大区';

/*==============================================================*/
/* Table: district_location                                     */
/*==============================================================*/
create table district_location
(
   district_id          int unsigned not null default 0,
   location_id          int unsigned not null default 0,
   primary key (district_id, location_id)
);

/*==============================================================*/
/* Table: links                                                 */
/*==============================================================*/
create table links
(
   id                   smallint unsigned not null auto_increment,
   sitename             varchar(60) not null default '',
   address              varchar(255) not null default '',
   logo                 varchar(255) not null default '',
   category             varchar(20) not null default '',
   primary key (id),
   key category (category)
);

/*==============================================================*/
/* Table: locations                                             */
/*==============================================================*/
create table locations
(
   id                   int unsigned not null auto_increment,
   name                 varchar(60) not null default '',
   code                 varchar(60) not null default '',
   letter               char(1) not null default '',
   remark               varchar(255) not null default '',
   store_count          int not null default 0,
   primary key (id),
   key letter (letter)
);

/*==============================================================*/
/* Table: settings                                              */
/*==============================================================*/
create table settings
(
   skey                 varchar(60) not null default '',
   svalue               varchar(800) not null default '',
   options              varchar(500) not null default '',
   remark               varchar(255) not null default '',
   input_type           varchar(20) not null default '',
   input_style          varchar(500) not null default '',
   category             varchar(20) not null default '',
   primary key (skey),
   key category (category)
);

/*==============================================================*/
/* Table: store_category                                        */
/*==============================================================*/
create table store_category
(
   id                   int unsigned not null auto_increment,
   cate_name            varchar(60) not null default '',
   store_id             int unsigned not null,
   primary key (id),
   key store_id (store_id)
);

/*==============================================================*/
/* Table: store_menus                                           */
/*==============================================================*/
create table store_menus
(
   id                   int unsigned not null auto_increment,
   name                 varchar(100) not null default '',
   price                decimal(4,2) not null default 0,
   image                varchar(255) not null default '',
   remark               varchar(255) not null default '',
   cate_id              int unsigned not null default 0,
   primary key (id),
   key store_id (cate_id)
);

/*==============================================================*/
/* Table: stores                                                */
/*==============================================================*/
create table stores
(
   id                   int unsigned not null auto_increment,
   name                 varchar(80) not null default '',
   address              varchar(255) not null default '',
   telphone             varchar(80) not null default '',
   scope                varchar(255) not null default '',
   shop_hours           varchar(80) not null default '',
   sendup_prices        varchar(60) not null default '',
   remark               varchar(1000) not null default '',
   image                varchar(100) not null default '',
   rating               char(1) not null default '',
   location_id          varchar(100) not null default '',
   primary key (id)
);

/*==============================================================*/
/* Table: users                                                 */
/*==============================================================*/
create table users
(
   id                   int unsigned not null auto_increment,
   username             varchar(60) not null default '',
   password             char(32) not null default '',
   passwdkey            char(8) not null default '',
   email                varchar(160) not null default '',
   nickname             varchar(60) not null default '',
   gender               varchar(2) not null default '',
   status               tinyint(1) not null default 1,
   is_admin             tinyint(1) not null default 0,
   create_time          int not null default 0,
   login_time           int not null default 0,
   login_ip             varchar(64) not null default '',
   login_count          int not null default 0,
   primary key (id),
   unique key username (username),
   key email (email)
);
