-- phpMyAdmin SQL Dump
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2017-04-11 15:48:31
-- 服务器版本: 5.5.36-log
-- PHP 版本: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `short`
--

-- --------------------------------------------------------

--
-- 表的结构 `short_count`
--

CREATE TABLE IF NOT EXISTS `short_count` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT 'sign id',
  `date` char(16) NOT NULL COMMENT '调用时的时间戳 格式 20160330',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '调用状态 0签名、1生成/缩短',
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

-- --------------------------------------------------------

--
-- 表的结构 `short_sign`
--

CREATE TABLE IF NOT EXISTS `short_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(16) NOT NULL COMMENT '生成的密钥的文件名',
  `domain` varchar(255) NOT NULL COMMENT '调用的域名',
  `appid` char(16) NOT NULL COMMENT 'appid 的 值',
  `appsecret` char(32) NOT NULL COMMENT 'appsecret 的 值',
  `state` tinyint(1) NOT NULL DEFAULT '0' COMMENT '签名状态 0临时用户、1正式用户、2 VIP用户、3 测试账号',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否被禁用 0不允许',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `state` (`state`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `short_sign`
--

INSERT INTO `short_sign` (`id`, `name`, `domain`, `appid`, `appsecret`, `state`, `status`) VALUES
(1, 'k6xBgla2VaqeNoX0', '*', 'dwz_2XKpa2naxn5k', 'oQB4dMW7eXJ8Ava2Ramp61DVE5jGrwbR', 3, 1);

-- --------------------------------------------------------

--
-- 表的结构 `short_urls`
--

CREATE TABLE IF NOT EXISTS `short_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sha1` char(40) NOT NULL COMMENT 'sha1加密后的url',
  `url` text NOT NULL COMMENT '原始url',
  `create_at` int(11) NOT NULL COMMENT '创建时间',
  `creator` int(11) NOT NULL DEFAULT '0' COMMENT '生成短网址的用户的ip',
  `count` int(11) NOT NULL DEFAULT '0' COMMENT '短网址调用的次数',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '短网址是否允许使用 0不允许',
  PRIMARY KEY (`id`),
  KEY `sha1` (`sha1`),
  KEY `create_at` (`create_at`),
  KEY `creator` (`creator`),
  KEY `count` (`count`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=134 ;

--
-- 转存表中的数据 `short_urls`
--

INSERT INTO `short_urls` (`id`, `sha1`, `url`, `create_at`, `creator`, `count`, `status`) VALUES
(84, '67232b0457ae58def79d6b5cfd172372b50baed9', 'http://www.kz.gl/92', 1488449901, 2147483647, 0, 1),
(83, 'bcec9f42eefd28bdd0ab719998ff4e2facf43b10', 'http://www.kz.gl/91', 1488449869, 2147483647, 0, 1),
(81, '383bfba55d86dd839884f48d9a7139455a9832df', 'http://www.kz.gl/89', 1488449720, 2147483647, 6, 1),
(82, '631007987653429817d9059da33190bf22d4cdde', 'http://www.kz.gl/90', 1488449823, 2147483647, 0, 1),
(80, '2d4c19cfec5187a1ba014eef0a73124b840426eb', 'http://t.cn/RiZzSSZ', 1487907535, 2147483647, 1, 1),
(79, '83ce950dc6bbabaa95d1c4d415ac6595d4a73845', 'http://www.66bomao.com/auth/signin', 1487907082, 2147483647, 24, 2),
(78, '604202ed3ffd37d0e59d947e1f5111e54d0183e7', 'https://ha55.net/SiteSort/TS111/index.aspx', 1487907045, 2147483647, 2, 1),
(77, 'ce42cd5eb61eaa793569ea7c7c74421ef39904e4', 'http://weibo.com/ajaxlogin.php?framelogin=1&callback=parent.sinaSSOController.feedBackUrlCallBack', 1487141233, 2147483647, 1, 1),
(74, 'bafc6418fef1c0c0950e92b12bba63edfaf0ba61', 'http://qm.qq.com/cgi-bin/qm/qr?k=ECaapjOHYyQtOVAxTQu3Rqv4_pord5eH', 1469080982, 1964845145, 0, 2),
(75, '36afb2984dc902d01468286d43c16827ead6198a', 'http://xiaocaimi.org', 1469677128, 2147483647, 3, 1),
(76, '69333a5dffa651f20096d8666e3e0a4a3673ffe8', 'http://www.boniu365.com', 1470794786, 2147483647, 5, 1),
(72, '8bf3e5493edc26a5bf314adcd02a310e138b3ff0', 'http://qinnmddfd.win/2,teobao,com/itam.htm&spm2007.1000337.18.2.FNu26k/?id=2221', 1466313768, 244848407, 0, 2),
(73, 'a0befe42be03acb2037e4a3ee0150a7166e0b53c', 'http://td111.net/', 1467875958, 2147483647, 1, 2),
(71, 'a5db93b392b74f40e28527fa18bf65a73f4d5e6d', 'http://qinnmddfd.win/2,teobao,com/itam.htm&spm2007.1000337.18.2.FNu26k/?id=2187', 1466313557, 2147483647, 0, 2),
(69, '85220e60b4764a7b2feb554102f25aa86d2f63cd', 'http://azxccvvbb.pw/images/?id=2187', 1466306582, 244848407, 7, 2),
(70, '8fe7622d1f74ad0ab5e363ba474654e37c3fff1a', 'http://qiunnnszzc.win/2,teobao,com/itam.htm&spm2007.1000337.18.2.FNu26k/?id=2214', 1466311520, 244848407, 10, 2),
(67, 'e19a918a3dcefa98c5d703e40808a60bd3ede566', 'http://www.51.com', 1466300916, 244848407, 0, 1),
(68, 'dc36002c89e12365989b4e9fa87bbfab187ab1fd', 'http://qiunnnszzc.win/2,teobao,com/itam.htm&spm2007.1000337.18.2.FNu26k/?id=2220', 1466305516, 244848407, 3, 2),
(66, '2c5349908efe4c703ea9b9cf72338a40dc0eb5b8', 'http://azxccvvbb.pw/images/?id=2217', 1466231233, 244848319, 8, 2),
(65, 'cb8810e2ff2c7c1a5a57b607f03d9c5b6c006b26', 'http://qiunnnszzc.win/2,teobao,com/itam.htm&spm2007.1000337.18.2.FNu26k/?id=2216', 1466229280, 244848319, 7, 2),
(63, '3cca35fd411bf8ee1b70812d053f8295495a0960', 'http://azxccvvbb.pw/images/?id=2214', 1466220492, 244848319, 19, 2),
(64, '8f139590119bb3b0155fe5ab786644f05b3351b4', 'http://azxccvvbb.pw/images/?id=2215', 1466228944, 244848319, 44, 2),
(62, '91c03f7c33bdbe8bd4f3d2f312d57cf61beb5af9', 'http://azxccvvbb.pw/images/?id=2213', 1466217752, 244848319, 9, 2),
(60, 'd4c72a12b99055763ed48140d7c08f07245d2942', 'http://yyioognnm.pw/images/?id=2211', 1466140063, 244848319, 15, 2),
(61, '416d006731fffeea8ae2dbde9a994cf764057ed6', 'http://azxccvvbb.pw/images/?id=2212', 1466145176, 244848274, 15, 2),
(59, '6894c51378c4d681e7b01e38e9cc0281c93746aa', 'http://www.dasheng888.com/index', 1465800248, 2147483647, 2, 2),
(85, '7cf49266dc21425a37a7eb4529ed5f4686413b43', 'http://www.kz.gl/93', 1488450019, 2147483647, 0, 1),
(58, 'addf05fde437f1d7d9eaf055e4dd9f2415cb2457', 'http://t.cn/RyaE8kN', 1465799546, 2147483647, 15, 2),
(55, '62c8beb476554feb036e238c98ecf746c7c09482', 'http://www.bomao.com/auth/signin', 1465797711, 2147483647, 3, 2),
(86, '1845a31ed2f4506f4861aa978efb75f750857eb7', 'http://www.kz.gl/94', 1488450081, 2147483647, 0, 1),
(87, 'f764b92be1a36b2197b65942907126b7b2a44883', 'http://www.kz.gl/95', 1488450131, 2147483647, 0, 1),
(88, '83a5ad8ae2f11455312acfabd67865ac03d7200e', 'http://www.kz.gl/96', 1488450172, 2147483647, 0, 1),
(89, '0677500b5e01ac4b12d8d60ad3252d854592fda4', 'http://url.zhbcl.com/?x6', 1490503673, 1939336254, 7006, 1),
(90, '23df6670aa3af2e51df669f5b7e501b7f418f8a8', 'http://url.zhbcl.com/?2a', 1490503851, 1939336254, 0, 1),
(91, '5fb0f5db931ec47e30539f69aa1ba89b73f15b50', 'http://up8.ren/?1i', 1490503952, 1939336254, 5896, 1),
(92, 'ad193161632ad21798a2378f2d113488427ceb47', 'http://up8.ren/?3s', 1490504014, 1939336254, 6134, 1),
(93, '7f0e7ac3b6e87872dd00c8a2c652c21024901a14', 'http://up8.ren/?2p', 1490504044, 1939336254, 12183, 1),
(94, 'dde56f7b68e140b283acb15f60bba7dd030954ba', 'http://up8.ren/?96', 1490504101, 1939336254, 5263, 1),
(95, 'd14b9a1d0b2732b98a6f5aae411769741ba050bd', 'http://up8.ren/?rb', 1490504178, 1939336254, 11568, 1),
(96, '13d4adeaf8469df998026ba9558354698956726c', 'http://url.xrbk.top/pl', 1490594726, 1939336677, 0, 1),
(97, '307c6d078184a1747645824414880beb061387bd', 'http://url.xrbk.top/y4', 1490877771, 2147483647, 9803, 1),
(98, '09bb5498efe53096871aaccac600c9cf24334d99', 'http://url.xrbk.top/1y', 1490877916, 2147483647, 5967, 1),
(99, 'a5e81fe5e4754b73a1b1a74958bc82b91f4e59ea', 'http://url.xrbk.top/b0', 1490877952, 2147483647, 4887, 1),
(100, '81acb09276aa0701758ae47114b20f5569d05c1f', 'http://url.xrbk.top/y8', 1490878054, 2147483647, 4531, 1),
(101, '28a11f94cad0ebe80d2f2aa3f6258d0b821c3e68', 'http://url.xrbk.top/2x', 1490878157, 2147483647, 4048, 1),
(102, 'e6bb1ef18c427f8709b79e91dad79ae078c0e0bb', 'http://url.xrbk.top/dl', 1490878202, 2147483647, 4441, 1),
(103, '3f4575323505baad7b69b2eeee1c020ef8c22292', 'http://url.xrbk.top/gm', 1490878280, 2147483647, 4737, 1),
(104, '57a437f838acfff76bcc9d819040330478ea2ae3', 'http://url.zhbcl.com/?2o', 1491044539, 2147483647, 8571, 1),
(105, '455bd440b284783d779b8862a7004a6931793418', 'http://url.zhbcl.com/?qn', 1491044601, 2147483647, 5996, 1),
(106, '7a892dc6e070260004bf151606d6a6f5d7aa5e9b', 'http://url.zhbcl.com/?ea', 1491044639, 2147483647, 5148, 1),
(107, 'c8ce1bca50ce6fafc9a91e2739af5c523019ab13', 'http://url.zhbcl.com/?nb', 1491044722, 2147483647, 3814, 1),
(108, 'f0baf7006579300d228c81dca038d42ca59338c9', 'http://url.zhbcl.com/?r1', 1491044788, 2147483647, 4599, 1),
(109, '09c69334c65ff677bd44995c023228965de0223b', 'http://url.zhbcl.com/?ut', 1491044865, 2147483647, 4393, 1),
(110, '2de92a2ce9aaa9847f9796356497e61d61ca6f54', 'http://url.zhbcl.com/?sv', 1491045020, 2147483647, 4557, 1),
(111, '5e92f46e0b970117748f1a4a1976c14e55a752ec', 'http://url.zhbcl.com/?tp', 1491201780, 2147483647, 9057, 1),
(112, 'd41c3d4ecd51bb428eb5627b55f8054f9f8cc5a0', 'http://url.zhbcl.com/?wd', 1491201826, 2147483647, 6048, 1),
(113, 'fccbbefbd7dd39b91bc20f70e705716973fe1a0a', 'http://url.zhbcl.com/?bb', 1491201933, 2147483647, 5000, 1),
(114, 'd96619bf3dc3810158c339dbc381dfb4a83f5180', 'http://url.zhbcl.com/?ll', 1491202009, 2147483647, 4358, 1),
(115, '23daff5e9277d1beeb74233342ccb5f4118be9b4', 'http://url.zhbcl.com/?xd', 1491202099, 2147483647, 4654, 1),
(116, '060f9de79716372dcdedc919aed72b3eb532589d', 'http://url.zhbcl.com/?zi', 1491202183, 2147483647, 4853, 1),
(117, '55f9a971bdb708454c8c333ab86a775a5519953b', 'http://url.zhbcl.com/?up', 1491202215, 2147483647, 4645, 1),
(118, '8d4a2904992947a7c1887ad6ce4c0d0925568dec', 'http://url.zhbcl.com/?s8', 1491202472, 2147483647, 2, 1),
(119, '413009059dc58b7521fd250c38d53f8155e6dd48', 'http://url.zhbcl.com/?pp', 1491202532, 2147483647, 0, 1),
(120, '2bcebec9e78390e24df2165d0ff71889fcabd3c2', 'http://url.zhbcl.com/?zd', 1491202575, 2147483647, 0, 1),
(121, '477d009b57eadcac17e1bf1876aee75b6e6131eb', 'http://url.zhbcl.com/?41', 1491202675, 2147483647, 0, 1),
(122, 'facd719360d46bc9f41023fd4c5f64f4be2cd457', 'http://url.zhbcl.com/?dcb', 1491202726, 2147483647, 0, 1),
(123, '6ec39c87c19ca0b36e4c0f1b3a354ebdfb6c4d0e', 'http://url.zhbcl.com/?hw', 1491202757, 2147483647, 0, 1),
(124, 'b9dd00befe7299bd01b64478eee833a4756b6a3f', 'http://url.zhbcl.com/?xk', 1491202804, 2147483647, 0, 1),
(125, '4d5b6f76717cf3110e4a784e98dc38cd9623fe99', 'http://url.zhbcl.com/?7g', 1491202863, 2147483647, 2, 1),
(126, 'df5c9ed372695974535d7f0170cddda7d23e0234', 'http://url.xrbk.top/y2', 1491366469, 2147483647, 7996, 1),
(127, 'cd8b8090de53c66691146d68873e5cfe7118fa50', 'http://url.xrbk.top/ple', 1491366515, 2147483647, 5366, 1),
(128, 'd20447fb250cccf068861c48d2aa1b405b2f418a', 'http://url.xrbk.top/gt', 1491366570, 2147483647, 4445, 1),
(129, '5509eea808e4734c08b94e1c42250d43fc4d760a', 'http://url.xrbk.top/5l', 1491366599, 2147483647, 3275, 1),
(130, 'bb427c4f52025aac9ceed33be4ac512493c0a4a4', 'http://url.xrbk.top/69', 1491366746, 2147483647, 4191, 1),
(131, '23b63c36c894b827f4d15992315b60138d78c612', 'http://url.xrbk.top/nb', 1491366786, 2147483647, 4059, 1),
(132, 'eb36cb77366339acb183f3d79624a45e1e906f70', 'http://url.xrbk.top/ox', 1491366814, 2147483647, 4074, 1),
(133, '755f7116e34deda45819546e49620497b5b4f816', 'http://url.xrbk.top/ll', 1491559004, 2147483647, 0, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
