<?php
// 加载数据库配置
include  "../config/database.php";
$pdo = null;
if(isset($config)){
	try {
		$pdo = new PDO("mysql:host={$config['DB_HOST']};dbname={$config['DB_NAME']}", "{$config['DB_USER']}", "{$config['DB_PWD']}", array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));
		$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	}catch (PDOException $e) {
		$pdo = null;
	}
}
