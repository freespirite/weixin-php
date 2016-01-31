-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 �?01 �?31 �?18:24
-- 服务器版本: 5.6.11
-- PHP 版本: 5.5.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `weixin`
--

-- --------------------------------------------------------

--
-- 表的结构 `wx_login_status`
--

CREATE TABLE IF NOT EXISTS `wx_login_status` (
  `mail` varchar(50) NOT NULL COMMENT '用户邮箱账号',
  `auth` varchar(32) NOT NULL COMMENT '签订标示',
  `timeOut` int(10) unsigned NOT NULL COMMENT '过期时间',
  PRIMARY KEY (`auth`),
  UNIQUE KEY `mail` (`mail`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='用户登录信息表';

-- --------------------------------------------------------

--
-- 表的结构 `wx_mp_set`
--

CREATE TABLE IF NOT EXISTS `wx_mp_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) unsigned NOT NULL COMMENT '用户id号',
  `name` char(60) NOT NULL COMMENT '微信号名称',
  `appid` char(32) NOT NULL DEFAULT '' COMMENT '公众号应用ID',
  `mchid` char(32) NOT NULL DEFAULT '' COMMENT '受理商ID，身份标识',
  `key` char(32) NOT NULL DEFAULT '' COMMENT '商户支付密钥Key',
  `appsecret` char(32) NOT NULL DEFAULT '' COMMENT '公众号应用秘钥',
  `sslcert` char(16) NOT NULL DEFAULT '' COMMENT '证书绝对路径',
  `sslkey` char(16) NOT NULL DEFAULT '' COMMENT '证书绝对路径',
  `remark` varchar(300) NOT NULL DEFAULT '' COMMENT '公众号描述',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `appid` (`appid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='用户公众号设置' AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `wx_mp_set`
--

INSERT INTO `wx_mp_set` (`id`, `uid`, `name`, `appid`, `mchid`, `key`, `appsecret`, `sslcert`, `sslkey`, `remark`, `createtime`, `updatetime`) VALUES
(1, 3, '我的公众号', 'wx3152faa31d086ea4', '', '', 'b7bfe46a0fdecee7368f8741d547170a', '', '', '测试的公众号6', 0, 0),
(2, 3, '第二个小号', 'wx3152faa31d086ea1', '', '', 'wx3152faa31d086ea4dddddddd', '', '', '不描述行不行啊', 0, 1454089522),
(3, 3, '第三个小号', 'wx3152faa31d086ea3', '', '', 'b7bfe46a0fdecee7368f8741d547170b', '', '', '测试的公众号03', 1454089589, 1454089589);

-- --------------------------------------------------------

--
-- 表的结构 `wx_users`
--

CREATE TABLE IF NOT EXISTS `wx_users` (
  `uid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(50) NOT NULL DEFAULT '',
  `pwd` char(32) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `lastloginip` int(10) NOT NULL DEFAULT '0',
  `lastlogintime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `account` (`account`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- 转存表中的数据 `wx_users`
--

INSERT INTO `wx_users` (`uid`, `account`, `pwd`, `createtime`, `lastloginip`, `lastlogintime`) VALUES
(3, 'freespirite@163.com', '38a4f7159de3ba4ab3901ff059e4d678', 1451470308, 2130706433, 1451470308);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
