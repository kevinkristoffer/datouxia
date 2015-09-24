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
	(3, 2, '用户管理', 1, '/core/user', '1'),
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
	('07256985', '25f9e794323b453885f5181f1b624d0b', '林志玲', 'CE', 'N'),
	('07256986', '1d42abef5a4a5748edf45448bc8d64c5', '王宝强', 'SA', 'Y'),
	('07256987', 'e10adc3949ba59abbe56e057f20f883e', '道明寺', 'CA', 'Y'),
	('07256988', 'e10adc3949ba59abbe56e057f20f883e', '柳岩', 'CA', 'N'),
	('07256989', 'e10adc3949ba59abbe56e057f20f883e', '萧蔷', 'TA', 'Y'),
	('07256990', 'e10adc3949ba59abbe56e057f20f883e', '葛优', 'TA', 'Y'),
	('07256991', 'e10adc3949ba59abbe56e057f20f883e', '测试账户1', 'TA', 'Y'),
	('07256992', 'e10adc3949ba59abbe56e057f20f883e', '测试账户2', 'TA', 'N'),
	('07256993', 'e10adc3949ba59abbe56e057f20f883e', '测试账户3', 'TA', 'Y'),
	('07256994', 'e10adc3949ba59abbe56e057f20f883e', '测试账户4', 'TA', 'Y'),
	('07256995', 'e10adc3949ba59abbe56e057f20f883e', '测试账户5', 'TA', 'Y'),
	('07256996', 'e10adc3949ba59abbe56e057f20f883e', '测试账户6', 'TA', 'N'),
	('07256997', 'e10adc3949ba59abbe56e057f20f883e', '测试账户7', 'TA', 'Y'),
	('07256998', 'e10adc3949ba59abbe56e057f20f883e', '测试账户8', 'TA', 'Y'),
	('07256999', 'e10adc3949ba59abbe56e057f20f883e', '测试账户9', 'TB', 'Y'),
	('07257000', 'e10adc3949ba59abbe56e057f20f883e', '测试账户10', 'TB', 'Y'),
	('07257001', 'e10adc3949ba59abbe56e057f20f883e', '测试账户11', 'TB', 'Y'),
	('07257002', 'e10adc3949ba59abbe56e057f20f883e', '测试账户12', 'TA', 'N'),
	('07257003', 'e10adc3949ba59abbe56e057f20f883e', '测试账户13', 'TA', 'Y'),
	('07257004', 'e10adc3949ba59abbe56e057f20f883e', '测试账户14', 'TA', 'N'),
	('07257005', 'e10adc3949ba59abbe56e057f20f883e', '测试账户15', 'TA', 'Y'),
	('07257006', 'e10adc3949ba59abbe56e057f20f883e', '测试账户16', 'TA', 'Y');
/*!40000 ALTER TABLE `dt_core_user` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
