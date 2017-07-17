<?php
define('ADMIN_DEFAULT_THEME', get_admin_theme());
return array(
    //'配置项'=>'配置值'
    'VIEW_PATH'       =>'./Theme/'. MODULE_NAME .'/',// 改变某个模块的模板文件目录
    'DEFAULT_THEME' => ADMIN_DEFAULT_THEME,// 设置默认的模板主题
    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__PUBLIC__' => __ROOT__ . '/Public',
        '__IMG__'    => '/Theme/' . MODULE_NAME . '/' . ADMIN_DEFAULT_THEME .'/img',
        '__CSS__'    => '/Theme/'. MODULE_NAME . '/' . ADMIN_DEFAULT_THEME .'/css',
        '__JS__'    => '/Theme/'. MODULE_NAME . '/' . ADMIN_DEFAULT_THEME .'/js',
        '__UPLOAD__'=> C('UPLOAD_URL'),
    ),
    // error，success跳转页面
    // 'TMPL_ACTION_ERROR' => 'Public:dispatch_jump',
    // 'TMPL_ACTION_SUCCESS' => 'Public:dispatch_jump',
);

// return array(
// 	//'配置项'=>'配置值'
//     /* 模板相关配置 */
//     'TMPL_PARSE_STRING' => array(
//         '__STATIC__' => __ROOT__ . '/Public/static',
//         '__IMG__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/img',
//         '__CSS__'    => __ROOT__ . '/Public/' . MODULE_NAME . '/css',
//         '__JS__'     => __ROOT__ . '/Public/' . MODULE_NAME . '/js',
//         '__UPLOAD__'=> C('UPLOAD_URL'),
//     ),
//     //页面布局
//     'LAYOUT_ON'=>true,
//     'LAYOUT_NAME'=>'Public/layout',
//     // error，success跳转页面
//     'TMPL_ACTION_ERROR' => 'Public:dispatch_jump',
//     'TMPL_ACTION_SUCCESS' => 'Public:dispatch_jump',
// );

