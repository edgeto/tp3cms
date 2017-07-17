<?php
return array(
	//'配置项'=>'配置值'
	/* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/img',
        '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
        '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
        '__UPLOAD__'=> C('UPLOAD_URL'),
    ),
    // 开启路由
    /*'URL_ROUTER_ON'   => true,
    'URL_ROUTE_RULES'=>array(
         'article/detail/:id'=>'article/detail',
    ),*/
);