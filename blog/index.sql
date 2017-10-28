
create table if not exists `bk_category`(
	`cate_id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '表id',
	`name` varchar(100) not null default 0 COMMENT '分类名称',
	`english` varchar(100)  COMMENT '英文名称',
	`image` varchar(100) not null default 0 COMMENT 'logo',
    `parent_id` int  unsigned not null default 0 COMMENT '父类id',
    `addtime` int unsigned not null COMMENT '添加时间',
    `is_show`  tinyint(1) unsigned not null default 1 COMMENT '是否显示 1是 0否',
    primary key (`cate_id`)
)engine=innodb default CHARSET=utf8mb4 COMMENT '分类表';

create table if not exists `bk_config`(
	`id` int unsigned not null AUTO_INCREMENT COMMENT '表id',
	`name` varchar(100) not null COMMENT '配置名称',
	`content` varchar(500) not null  COMMENT '配置内容',
	`addtime` int unsigned not null COMMENT '添加时间',
	primary key (`id`)
)engine=innodb DEFAULT CHARSET=utf8mb4 COMMENT '配置表';

create table if  not exists `bk_banner`(
	`id` int  unsigned not null AUTO_INCREMENT COMMENT '表id',
	`show_addr` varchar(100) not null default 'index' COMMENT '显示位置',
    `introduction` varchar(255)  COMMENT '描述',
    `url` varchar(255) COMMENT '跳转地址',
    `imageurl` varchar(255) not null COMMENT '图片地址',
    `addtime` int unsigned not null COMMENT '添加时间',
    `is_show` tinyint(1) unsigned default 1 COMMENT '是否显示 0否 1是',
    primary key (`id`)
)engine=Innodb default charset= utf8mb4 COMMENT '轮播表';

create table if not exists `bk_user`(
	`user_id` int unsigned not null auto_increment COMMENT '表id',
	`name`  varchar(100) not null  COMMENT '用户名',
	`password`  varchar(32) not null  COMMENT '密码',
	`avatar` varchar(100) default 0 COMMENT '头像',
	`profession` varchar(100) default 0 comment '职业',
	`phone` varchar(100) default 0 COMMENT '电话',
	`email` varchar(100) default 0 COMMENT '邮箱',
	`contact_address` varchar(100) default 0 COMMENT '联系地址',
	`login_time` int  unsigned default 0 COMMENT '登陆时间',
	`addtime` int  unsigned not null COMMENT '注册时间',
	`is_show` tinyint(1) unsigned default 1 COMMENT '是否显示 0否 1是',
	primary key (`user_id`)
)engine=Innodb default charset= utf8mb4 COMMENT '用户表';

create table if not exists `bk_user_attr`(
	`id` int unsigned not null auto_increment COMMENT '表id',
	`user_id` int unsigned not null COMMENT '用户id',
	`is_show` tinyint(1) unsigned default 1 COMMENT '是否显示 0否 1是',
	primary key (`id`)
)engine=Innodb default charset= utf8mb4 COMMENT '用户属性表';



create table if not exists `bk_third_party`(
	`id` int unsigned not null auto_increment COMMENT '表id',
	`user_id` int unsigned not null COMMENT '表id',
	`qq_appid`  varchar(100) not null  COMMENT '用户名',
	`qq` varchar(100) default 0 COMMENT 'qq号码',
	`weixin_appid` int  unsigned not null COMMENT '添加时间',
	`weixin` varchar(100) default 0 COMMENT '微信号码',	
	`is_show` tinyint(1) unsigned default 1 COMMENT '是否显示 0否 1是',
	primary key (`id`)
)engine=Innodb default charset= utf8mb4 COMMENT '第三方登陆表';


create table if not exists `bk_article`(
    `art_id` int  unsigned not null AUTO_INCREMENT COMMENT '表id',
    `user_id` int unsigned not null default 1 COMMENT '发布用户id 默认自己',
    `title` varchar(200) not null COMMENT '标题',
    `introduction` varchar(255) not null COMMENT '简介',
    `content` text   not null COMMENT '内容',
    `discuss_total` int unsigned default 0  COMMENT '评论数',
    `approve_total` int  unsigned default 0 COMMENT '点赞数',
    `addtime` int  unsigned not null COMMENT '发表时间',
    `is_show` tinyint(1)  unsigned not null default 1 COMMENT '是否显示 1 是 0否', 
    primary key (`art_id`)
)engine=Innodb default charset= utf8mb4 COMMENT '发布文章表';

create table if not exists `bk_article_images`(
    `id` int  unsigned not null AUTO_INCREMENT COMMENT '表id',
   	`art_id` int unsigned not null comment '文章id',
   	`imageurl` varchar(255) not null COMMENT '图片地址',
   	`type` tinyint(1) unsigned not null default 1 comment '类型 1封面 2内容 3 视频封面 4视频',
    primary key (`id`)
)engine=Innodb default charset= utf8mb4 COMMENT '发布文章图片/视频表';


create table if not exists `bk_discuss`(
	`id` int  unsigned not null AUTO_INCREMENT COMMENT '表id',
	`art_id` int unsigned not null comment '文章id',
	`parent_id` int unsigned default 0 comment '父评论id',
	`user_id` int unsigned not null comment '评论用户id',	
	`content` varchar(255) not null comment '评论内容',
	`grade` tinyint(1) not null default 4 comment '评分',
	`addtime` int  unsigned not null comment '评论时间',
	`is_show` tinyint(1)  unsigned not null default 1 COMMENT '是否显示 1 是 0否', 
	primary key (`id`)
)engine=Innodb default charset= utf8mb4 COMMENT '评论表';


create table if not exists `bk_approve`(
	`id` int  unsigned not null AUTO_INCREMENT COMMENT '表id',
	`art_id` int unsigned not null comment '文章id',
	`user_id` int unsigned not null comment '点赞用户id',	
	`addtime` int  unsigned not null comment '点赞时间',
	`is_approve` tinyint(1)  unsigned not null default 1 COMMENT '是否点赞 1 是 0否', 
	`is_show` tinyint(1)  unsigned not null default 1 COMMENT '是否显示 1 是 0否',
	primary key (`id`)
)engine=Innodb default charset= utf8mb4 COMMENT '点赞表';


create table if not exists `bk_article_recommend`(
	`id` int  unsigned not null AUTO_INCREMENT COMMENT '表id',
	`art_id` int unsigned not null comment '文章id',
	`user_id` int unsigned not null comment '点赞用户id',	
	`addtime` int  unsigned not null comment '点赞时间',
	`is_rec` tinyint(1)  unsigned not null default 1 COMMENT '是否推荐 1 是 0否', 
	primary key (`id`)
)engine=Innodb default charset= utf8mb4 COMMENT '文章推荐表';


create table if not exists `bk_article_hot`(
	`id` int  unsigned not null AUTO_INCREMENT COMMENT '表id',
	`art_id` int unsigned not null comment '文章id',
	`hot` int unsigned not null comment '热度',
	`addtime` int  unsigned not null comment '添加时间',
	primary key (`id`)
)engine=Innodb default charset= utf8mb4 COMMENT '热门文章表';