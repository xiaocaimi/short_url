<?php

// 开启调试模式,开启后sql错误后面会输出原始sql语句
define('DEBUG',true); 

// 数据库类型 mysql,sqlite
define('DB_TYPE','mysql');
// 数据库连接地址 如果是sqlite，请填写相对路径
define('DB_HOST','127.0.0.1');  
// 数据库端口
define('DB_PORT','3306');
// 数据库用户名
define('DB_USER', 'root');
// 数据库密码
define('DB_PWD','xiaocaimi');
// 数据库名
define('DB_NAME','short');
// 数据库名前缀
define('DB_PREFIX','');

// 基本配置
$config['list']['type'] = 'black';	// 启用状态，black黑名单、white白名单
$config['list']['black'] = array('url.ms','skyx.in','88dgt.com','guochanglaw.com','hebweichang.com','hfpszs888.com','jjfykj.com','jchs.cn','5jaa.com','39gcw.com','yyioognnm.pw','azxccvvbb.pw','qiunnnszzc.win');	// 域名黑名单 
$config['list']['white'] = array();	// 域名白名单

// 域名、hash配置
$config['base_url'] = 'http://dwz.lt/';
$config['hash']     = array(
			'salt'     => 'dwz_lt_key',
	        'length'   => 5,
	        'alphabet' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890',
		);

// 加密签名设置  参数：
// key 临时用户每天签名调用次数、正式用户每天签名调用次数、VIP用户每天签名调用次数 0不限
// num 临时用户每天调用次数、正式用户每天调用的次数、VIP用户每天的调用次数 0不限
$config['sign'] = array(
			array('key'=>2,'num'=>20),
			array('key'=>10,'num'=>100),
			array('key'=>20,'num'=>200),
			array('key'=>50,'num'=>500)
		);

// 关键字屏蔽
$blackWord = array('法轮功','博彩','赌博','私服','反共','卖枪','催情药');
