-- --------------------------------------------------------
-- 主机:                           127.0.0.1
-- 服务器版本:                        5.5.39-MariaDB - mariadb.org binary distribution
-- 服务器操作系统:                      Win32
-- HeidiSQL 版本:                  8.3.0.4713
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- 导出 datouxia 的数据库结构
CREATE DATABASE IF NOT EXISTS `datouxia` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `datouxia`;


-- 导出  表 datouxia.dt_core_forum 结构
CREATE TABLE IF NOT EXISTS `dt_core_forum` (
  `forumid` int(11) NOT NULL AUTO_INCREMENT,
  `parentid` int(11) DEFAULT NULL,
  `forumname` varchar(50) NOT NULL,
  `forumorder` int(11) DEFAULT '0',
  `url` varchar(100) DEFAULT NULL,
  `validstatus` enum('0','1') DEFAULT '1',
  PRIMARY KEY (`forumid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- 正在导出表  datouxia.dt_core_forum 的数据：~5 rows (大约)
/*!40000 ALTER TABLE `dt_core_forum` DISABLE KEYS */;
INSERT INTO `dt_core_forum` (`forumid`, `parentid`, `forumname`, `forumorder`, `url`, `validstatus`) VALUES
	(1, 0, '系统信息', 1, '/core/dashboard', '1'),
	(2, 0, '管理员', 2, NULL, '1'),
	(3, 2, '用户管理', 1, NULL, '1'),
	(4, 2, '用户组管理', 2, '/core/role', '1'),
	(5, 2, '权限管理', 3, NULL, '1');
/*!40000 ALTER TABLE `dt_core_forum` ENABLE KEYS */;


-- 导出  表 datouxia.dt_core_role 结构
CREATE TABLE IF NOT EXISTS `dt_core_role` (
  `rolecode` char(2) NOT NULL,
  `rolename` varchar(20) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `validflag` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`rolecode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- 正在导出表  datouxia.dt_core_role 的数据：~7 rows (大约)
/*!40000 ALTER TABLE `dt_core_role` DISABLE KEYS */;
INSERT INTO `dt_core_role` (`rolecode`, `rolename`, `description`, `validflag`) VALUES
	('CA', '普通管理员', '除了管理员管理等核心权限外', 'Y'),
	('CE', '内容编辑员', '新闻、图片等编辑', 'Y'),
	('SA', '超级管理员', '所有权限', 'Y'),
	('TA', '临时管理员1', '', 'Y'),
	('TB', '临时管理员2', '', 'Y'),
	('TC', '临时管理员3', '', 'N'),
	('TD', '临时管理员4', '', 'Y');
/*!40000 ALTER TABLE `dt_core_role` ENABLE KEYS */;


-- 导出  表 datouxia.dt_core_user 结构
CREATE TABLE IF NOT EXISTS `dt_core_user` (
  `authid` char(8) NOT NULL,
  `credential` varchar(32) NOT NULL,
  `accountname` varchar(20) NOT NULL,
  `rolecode` char(2) NOT NULL,
  `validflag` enum('Y','N') NOT NULL DEFAULT 'Y',
  PRIMARY KEY (`authid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- 正在导出表  datouxia.dt_core_user 的数据：~22 rows (大约)
/*!40000 ALTER TABLE `dt_core_user` DISABLE KEYS */;
INSERT INTO `dt_core_user` (`authid`, `credential`, `accountname`, `rolecode`, `validflag`) VALUES
	('07256985', '25f9e794323b453885f5181f1b624d0b', 'Catherine Jason', 'CE', 'N'),
	('13150297', '1d42abef5a4a5748edf45448bc8d64c5', 'Josh Hu', 'SA', 'Y'),
	('33020101', 'e10adc3949ba59abbe56e057f20f883e', 'Test User1', 'CA', 'Y'),
	('33020102', 'e10adc3949ba59abbe56e057f20f883e', 'Test User2', 'CA', 'Y'),
	('33020103', 'e10adc3949ba59abbe56e057f20f883e', 'Test User3', 'TA', 'Y'),
	('33020104', 'e10adc3949ba59abbe56e057f20f883e', 'Test User4', 'TA', 'Y'),
	('33020105', 'e10adc3949ba59abbe56e057f20f883e', 'Test User5', 'TA', 'Y'),
	('33020106', 'e10adc3949ba59abbe56e057f20f883e', 'Test User6', 'TA', 'Y'),
	('33020107', 'e10adc3949ba59abbe56e057f20f883e', 'Test User7', 'TA', 'Y'),
	('33020108', 'e10adc3949ba59abbe56e057f20f883e', 'Test User8', 'TA', 'Y'),
	('33020109', 'e10adc3949ba59abbe56e057f20f883e', 'Test User9', 'TA', 'Y'),
	('33020110', 'e10adc3949ba59abbe56e057f20f883e', 'Test User10', 'TA', 'Y'),
	('33020111', 'e10adc3949ba59abbe56e057f20f883e', 'Test User11', 'TA', 'Y'),
	('33020112', 'e10adc3949ba59abbe56e057f20f883e', 'Test User12', 'TA', 'Y'),
	('33020113', 'e10adc3949ba59abbe56e057f20f883e', 'Test User13', 'TB', 'Y'),
	('33020114', 'e10adc3949ba59abbe56e057f20f883e', 'Test User13', 'TB', 'Y'),
	('33020115', 'e10adc3949ba59abbe56e057f20f883e', 'Test User15', 'TB', 'Y'),
	('33020116', 'e10adc3949ba59abbe56e057f20f883e', 'Test User16', 'TA', 'Y'),
	('33020117', 'e10adc3949ba59abbe56e057f20f883e', 'Test User17', 'TA', 'Y'),
	('33020118', 'e10adc3949ba59abbe56e057f20f883e', 'Test User18', 'TA', 'Y'),
	('33020119', 'e10adc3949ba59abbe56e057f20f883e', 'Test User19', 'TA', 'Y'),
	('33020120', 'e10adc3949ba59abbe56e057f20f883e', 'Test User20', 'TA', 'Y');
/*!40000 ALTER TABLE `dt_core_user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
