-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2016 �?01 �?04 �?11:05
-- 服务器版本: 5.6.24
-- PHP 版本: 5.6.8

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
  `uid` int(11) unsigned NOT NULL COMMENT '用户id号',
  `appid` char(15) NOT NULL DEFAULT '' COMMENT '公众号身份的唯一标识',
  `mchid` char(32) NOT NULL DEFAULT '' COMMENT '受理商ID，身份标识',
  `key` char(32) NOT NULL DEFAULT '' COMMENT '商户支付密钥Key',
  `appsecret` char(30) NOT NULL DEFAULT '' COMMENT 'JSAPI接口中获取openid',
  `sslcert` char(16) NOT NULL DEFAULT '' COMMENT '证书绝对路径',
  `sslkey` char(16) NOT NULL DEFAULT '' COMMENT '证书绝对路径',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `updatetime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户公众号设置';

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
