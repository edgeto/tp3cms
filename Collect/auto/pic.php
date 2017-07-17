<?php
set_time_limit(0);
include 'pdo.php'; // 加载PDO
include 'url.php'; // 加载url配置
include 'redis.php'; // 加载redis配置
include 'Collector.class.php'; // 加载主程
include '../simplehtmldom/simple_html_dom.php'; // 加载simple_html_dom类
if(!empty($pdo)){
	if(!empty($picUrl)){
		$Collector = new Collector();
		foreach ($picUrl as $key => $value) {
			$Collector->getPicUrl($value['type'],$url = $value['url']);
		}
	}
}
