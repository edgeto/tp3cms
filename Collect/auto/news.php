<?php
set_time_limit(0);
include 'pdo.php'; // 加载PDO
include 'url.php'; // 加载url配置
include 'redis.php'; // 加载redis配置
include 'Collector.class.php'; // 加载主程
include '../simplehtmldom/simple_html_dom.php'; // 加载simple_html_dom类
/*if($_SERVER['SHELL']){
	$param_arr = getopt('a:');
	$a = !empty($param_arr['a']) ? $param_arr['a'] : 0;
}else{
	$a = !empty($_GET['a']) ? $_GET['a'] : 0;
}*/
if(!empty($pdo)){
	if(!empty($url)){
		$Collector = new Collector();
		foreach ($url as $key => $value) {
			$Collector->getUrl($value['type'],$url = $value['url']);
		}
	}
}
