<?php
//环境配置
include  "../../Environment/environment.php";
if(ENVIRONMENR == 'DEVELOPMENT') {
	$config = array(
        	'DB_HOST' => '127.0.0.1', // 服务器地址
                'DB_NAME' => 'yangsoon', // 数据库名
                'DB_USER' => 'root', // 用户名
                'DB_PWD' => 'root',  // 密码
                'DB_PORT' => '3306', // 端口
	);
        define('QDURL','http://qd.yangsoon.ngx');
}
if(ENVIRONMENR == 'TESTING'){
	$config = array(
        	'DB_HOST' => '127.0.0.1', // 服务器地址
                'DB_NAME' => 'yangsoon', // 数据库名
                'DB_USER' => 'root', // 用户名
                'DB_PWD' => 'root',  // 密码
                'DB_PORT' => '3306', // 端口
	);
        define('QDURL','http://qd.yangsoon.ngx');
}
if(ENVIRONMENR == 'PRODUCTION'){
	$config = array(
        	'DB_HOST' => '127.0.0.1', // 服务器地址
                'DB_NAME' => 'yangsoon', // 数据库名
                'DB_USER' => 'ys_user', // 用户名
                'DB_PWD' => 'ys_199027',  // 密码
                'DB_PORT' => '3306', // 端口
	);
        define('QDURL','http://qd.yangsoon.cn');
}