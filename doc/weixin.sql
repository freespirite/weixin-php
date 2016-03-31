-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-03-31 11:57:14
-- 服务器版本： 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `weixin`
--

-- --------------------------------------------------------

--
-- 表的结构 `wx_cache_token`
--

CREATE TABLE IF NOT EXISTS `wx_cache_token` (
  `wid` int(11) NOT NULL,
  `uid` int(10) NOT NULL,
  `token` char(160) NOT NULL COMMENT 'token',
  `timeOut` int(10) NOT NULL COMMENT '超时时间'
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='token缓存信息';

-- --------------------------------------------------------

--
-- 表的结构 `wx_cache_wx`
--

CREATE TABLE IF NOT EXISTS `wx_cache_wx` (
  `id` int(11) NOT NULL,
  `uid` int(10) NOT NULL,
  `wxid` int(10) NOT NULL COMMENT '绑定的公众ID',
  `wxaid` char(32) NOT NULL COMMENT '绑定的公众标示符'
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='平台用户登录缓存信息';

-- --------------------------------------------------------

--
-- 表的结构 `wx_contents`
--

CREATE TABLE IF NOT EXISTS `wx_contents` (
  `id` int(11) NOT NULL,
  `uid` int(10) NOT NULL,
  `wxid` int(10) NOT NULL COMMENT '绑定的公众ID',
  `type` tinyint(2) NOT NULL COMMENT '消息类型',
  `content` text NOT NULL COMMENT '消息内容',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=草稿，1=发送成功，2=发送失败',
  `createtime` int(10) NOT NULL COMMENT '记录生成时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='发送内容记录';

-- --------------------------------------------------------

--
-- 表的结构 `wx_images`
--

CREATE TABLE IF NOT EXISTS `wx_images` (
  `id` int(11) NOT NULL,
  `uid` int(10) NOT NULL,
  `wxaid` int(10) NOT NULL COMMENT '绑定的公众号id',
  `mediaid` varchar(43) NOT NULL COMMENT '素材ID',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '素材类型，1有数量限制的永久素材5M内，2无数量限制的永久素材1M内',
  `groupid` int(10) NOT NULL DEFAULT '0' COMMENT '素材分组id',
  `url` varchar(150) NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='平台用户登录缓存信息';

-- --------------------------------------------------------

--
-- 表的结构 `wx_login_status`
--

CREATE TABLE IF NOT EXISTS `wx_login_status` (
  `mail` varchar(50) NOT NULL COMMENT '用户邮箱账号',
  `auth` varchar(32) NOT NULL COMMENT '签订标示',
  `timeOut` int(10) unsigned NOT NULL COMMENT '过期时间'
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='用户登录信息表';

-- --------------------------------------------------------

--
-- 表的结构 `wx_mp_set`
--

CREATE TABLE IF NOT EXISTS `wx_mp_set` (
  `id` int(11) NOT NULL,
  `uid` int(11) unsigned NOT NULL COMMENT '用户id号',
  `name` char(60) NOT NULL COMMENT '微信号名称',
  `appid` char(32) NOT NULL DEFAULT '' COMMENT '公众号应用ID',
  `mchid` char(32) NOT NULL DEFAULT '' COMMENT '受理商ID，身份标识',
  `key` char(32) NOT NULL DEFAULT '' COMMENT '商户支付密钥Key',
  `appsecret` char(32) NOT NULL DEFAULT '' COMMENT '公众号应用秘钥',
  `token` varchar(32) NOT NULL COMMENT '英文或数字，长度为3-32字符',
  `aeskey` char(43) NOT NULL COMMENT '消息加密密钥由43位字符组成',
  `encrypt` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1明文，2兼容，3安全',
  `sslcert` char(16) NOT NULL DEFAULT '' COMMENT '证书绝对路径',
  `sslkey` char(16) NOT NULL DEFAULT '' COMMENT '证书绝对路径',
  `remark` varchar(300) NOT NULL DEFAULT '' COMMENT '公众号描述',
  `wxaid` char(32) NOT NULL COMMENT '公众号在系统中的唯一识别码',
  `certification` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否通过认证，0为否，1为是',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='用户公众号设置';

--
-- 转存表中的数据 `wx_mp_set`
--

INSERT INTO `wx_mp_set` (`id`, `uid`, `name`, `appid`, `mchid`, `key`, `appsecret`, `token`, `aeskey`, `encrypt`, `sslcert`, `sslkey`, `remark`, `wxaid`, `certification`, `createtime`, `updatetime`) VALUES
(5, 3, '我的公众号', 'wx3152faa31d086ea4', '', '', 'b7bfe46a0fdecee7368f8741d547170a', 'ymimi20150909', 'nL5aQqBsjeHUlunC0JG0W8E9SVbL3gNKKKKVjthYukl', 1, '', '', '我的公众号', '862de174ffef96ea647c6875778f6aec', 0, 1457701612, 1457701612),
(2, 3, '第二个小号', 'wx3152faa31d086ea1', '', '', 'b7bfe46a0fdecee7368f8741d547170r', '55555', 'nL5aQqBsjeHUlunC0JG0W8E9SVbL3gNKKKKVjthYukl', 3, '', '', '不描述行不行啊', '', 0, 0, 1457944458);

-- --------------------------------------------------------

--
-- 表的结构 `wx_users`
--

CREATE TABLE IF NOT EXISTS `wx_users` (
  `uid` int(10) unsigned NOT NULL,
  `account` varchar(50) NOT NULL DEFAULT '',
  `pwd` char(32) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户状态，0锁定用户,1普通用户,2付费用户',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `lastloginip` int(10) NOT NULL DEFAULT '0',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0'
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `wx_users`
--

INSERT INTO `wx_users` (`uid`, `account`, `pwd`, `status`, `createtime`, `lastloginip`, `lastlogintime`) VALUES
(3, 'freespirite@163.com', '38a4f7159de3ba4ab3901ff059e4d678', 1, 1451470308, 2130706433, 1451470308);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wx_cache_token`
--
ALTER TABLE `wx_cache_token`
  ADD PRIMARY KEY (`wid`), ADD KEY `uid` (`uid`);

--
-- Indexes for table `wx_cache_wx`
--
ALTER TABLE `wx_cache_wx`
  ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`,`wxid`);

--
-- Indexes for table `wx_contents`
--
ALTER TABLE `wx_contents`
  ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`,`wxid`);

--
-- Indexes for table `wx_images`
--
ALTER TABLE `wx_images`
  ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`,`wxaid`);

--
-- Indexes for table `wx_login_status`
--
ALTER TABLE `wx_login_status`
  ADD PRIMARY KEY (`auth`), ADD UNIQUE KEY `mail` (`mail`);

--
-- Indexes for table `wx_mp_set`
--
ALTER TABLE `wx_mp_set`
  ADD PRIMARY KEY (`id`), ADD KEY `uid` (`uid`,`appid`), ADD KEY `wxaid` (`wxaid`);

--
-- Indexes for table `wx_users`
--
ALTER TABLE `wx_users`
  ADD PRIMARY KEY (`uid`), ADD UNIQUE KEY `account` (`account`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wx_cache_wx`
--
ALTER TABLE `wx_cache_wx`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wx_contents`
--
ALTER TABLE `wx_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wx_images`
--
ALTER TABLE `wx_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `wx_mp_set`
--
ALTER TABLE `wx_mp_set`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `wx_users`
--
ALTER TABLE `wx_users`
  MODIFY `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
