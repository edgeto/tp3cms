<?php 
// 采集主要网页链接
$url = array(
	array(
		'type'=>1,
		'name'=>'百度百家',
		'url'=> 
			array(
				'http://baijia.baidu.com/',
				'http://baijia.baidu.com/?tn=listarticle&labelid=100',
				'http://baijia.baidu.com/?tn=listarticle&labelid=101',
				'http://baijia.baidu.com/?tn=listarticle&labelid=102',
				'http://baijia.baidu.com/?tn=listarticle&labelid=103',
				'http://baijia.baidu.com/?tn=listarticle&labelid=104',
				'http://baijia.baidu.com/?tn=listarticle&labelid=105',
				'http://baijia.baidu.com/?tn=listarticle&labelid=106',
				'http://baijia.baidu.com/?tn=listarticle&labelid=107',
				'http://baijia.baidu.com/?tn=listarticle&labelid=108',
			),
		),
);
// 图片采集主要网页链接
$picUrl = array(
	array(
		'type'=>1,
		'name'=>'美图录',
		'url'=> 
			array(
				'http://www.meitulu.com',//首页
				'http://www.meitulu.com/xihuan/',//精选美女
				'http://www.meitulu.com/guochan/',//国产美女
				'http://www.meitulu.com/gangtai/',//港台美女
				'http://www.meitulu.com/rihan/',//日韩美女
			),
		),
);
// select id,title ,count(*) as count from ys_article group by title having count>1;看是否有重复的