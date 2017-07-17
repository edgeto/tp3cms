<?php
if(ENVIRONMENR == 'DEVELOPMENT') {
    return array(
        //'配置项'=>'配置值'
        /* 调试配置 */
        'SHOW_PAGE_TRACE' => true,
        /* URL配置 */
        'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
        'URL_MODEL' => 2, //URL模式
        'VAR_URL_PARAMS' => '', // PATHINFO URL参数变量
        'URL_PATHINFO_DEPR' => '/', //PATHINFO URL分割符
        'URL_HTML_SUFFIX' => 'html', //伪静态
        'APP_SUB_DOMAIN_DEPLOY' => 1, // 开启子域名配置
        'APP_SUB_DOMAIN_RULES' => array(
            // 'ys16.yangsoon.ngx' => 'Admin',  // admin子域名指向Admin模块
            // 'www.yangsoon.ngx' => 'Home',  // www子域名指向Home模块
            'm.yangsoon.ngx' => 'M',  // m子域名指向M模块
            // 'api.yangsoon.ngx' => 'Api',  // api子域名指向Api模块
            'news.yangsoon.ngx' => 'News',
            // 'v.yangsoon.ngx' => 'V',
            'pic.yangsoon.ngx' => 'Pic',
            // 'user.yangsoon.ngx' => 'User',
            // 's.yangsoon.ngx' => 'Search',
            'ys16.yangsoon.ngx' => 'Admin',  // admin子域名指向Admin模块
            'www.yangsoon.ngx' => 'News',  // www子域名指向Home模块
            'user.yangsoon.ngx' => 'User',
            'test.yangsoon.ngx' => 'Test',
        ),
        /* 数据库配置 */
        'DB_TYPE' => 'mysql', // 数据库类型
        'DB_HOST' => '127.0.0.1', // 服务器地址
        'DB_NAME' => 'yangsoon', // 数据库名
        'DB_USER' => 'root', // 用户名
        'DB_PWD' => 'qiuzhou8790',  // 密码
        'DB_PORT' => '3306', // 端口
        'DB_PREFIX' => 'ys_', // 数据库表前缀
        'UPLOAD_URL'=>'http://qd.yangsoon.ngx/',//上传的静态资源
        'API_URL' => 'http://api.yangsoon.ngx/',//api链接
        /* 用户相关配置 */
        'USER_INFO' =>array(
            'headimgurl' => './Uploads/Avatur/'
        ),/* 用户相关配置 */
        /* 默认链接 */
        'DEFAULT_URL' => array(
            'ADMIN' => 'http://admin.yangsoon.ngx',
            'HOME' => 'http://www.yangsoon.ngx',
            'M' => 'http://m.yangsoon.ngx',
            'NEWS' => 'http://news.yangsoon.ngx',
            'V' => 'http://v.yangsoon.ngx',
            'PIC' => 'http://pic.yangsoon.ngx',
            'USER' => 'http://user.yangsoon.ngx',
            'SEARCH' => 'http://s.yangsoon.ngx',
        ),
        // 多域名session
        'SESSION_OPTIONS' => array(
            'domain' => 'yangsoon.ngx',
            'COOKIE_DOMAIN' => 'yangsoon.ngx',
        ),
         /* Cookie设置 */
        'COOKIE_DOMAIN'         => 'yangsoon.ngx',      // Cookie有效域名
        /* 文件上传相关配置 */
        'DOWNLOAD_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 5*1024*1024, //上传的文件大小限制 (0-不做限制)
            'exts'     => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml', //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => 'Uploads/Download/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ), //下载模型上传配置（文件上传类配置）

        /* 图片上传相关配置 */
        'PICTURE_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 8*1024*1024, //上传的文件大小限制 (0-不做限制)
            'exts'     => array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Ymd'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => 'Uploads/Images/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ), //图片上传相关配置（文件上传类配置）
        /* 视频上传相关配置 */
        'VIDEO_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 10*1024*1024, //上传的文件大小限制 (0-不做限制)
            'exts'     => array( "flv", "swf", "mkv", "avi", "rm", "rmvb", "mpeg", "mpg", "ogg", "ogv", "mov", "wmv", "mp4", "webm", "mp3", "wav", "mid"), //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Ymd'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => 'Uploads/Videos/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ), //视频上传相关配置（文件上传类配置）
        //----------------------sphinx配置------------------------------------------
        'SPHINX_ADDRESS' => '127.0.0.1',
        'SPHINX_PORT' => '9312',
        //----------------------redis配置------------------------------------------
        'REDIS_ADDRESS'              => '127.0.0.1',
        'REDIS_PORT'                 => '6379',
        'REDIS_PASSWORD'             => '',
        //----------------------redis配置------------------------------------------
    );
}
if(ENVIRONMENR == 'TESTING'){
    return array(
        //'配置项'=>'配置值'
        /* 调试配置 */
        'SHOW_PAGE_TRACE' => true,
        /* URL配置 */
        'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
        'URL_MODEL' => 2, //URL模式
        'VAR_URL_PARAMS' => '', // PATHINFO URL参数变量
        'URL_PATHINFO_DEPR' => '/', //PATHINFO URL分割符
        'URL_HTML_SUFFIX' => 'html', //伪静态
        'APP_SUB_DOMAIN_DEPLOY' => 1, // 开启子域名配置
        'APP_SUB_DOMAIN_RULES' => array(
            'admin.demo.ngx' => 'Admin',  // admin子域名指向Admin模块
        ),
        /* 数据库配置 */
        'DB_TYPE' => 'mysql', // 数据库类型
        'DB_HOST' => '127.0.0.1', // 服务器地址
        'DB_NAME' => 'yangsoon', // 数据库名
        'DB_USER' => 'root', // 用户名
        'DB_PWD' => 'root',  // 密码
        'DB_PORT' => '3306', // 端口
        'DB_PREFIX' => 'ys_', // 数据库表前缀
        'UPLOAD_URL'=>'http://qd.yangsoon.ngx/',//上传的静态资源
        'API_URL' => 'http://api.yangsoon.ngx/',//api链接
        /* 用户相关配置 */
        'USER_INFO' =>array(
            'headimgurl' => './Uploads/Avatur/'
        ),/* 用户相关配置 */
        /* 默认链接 */
        'DEFAULT_URL' => array(
            'ADMIN' => 'http://admin.yangsoon.ngx',
            'HOME' => 'http://www.yangsoon.ngx',
            'M' => 'http://m.yangsoon.ngx',
            'NEWS' => 'http://news.yangsoon.ngx',
            'V' => 'http://v.yangsoon.ngx',
            'PIC' => 'http://pic.yangsoon.ngx',
            'USER' => 'http://user.yangsoon.ngx',
            'SEARCH' => 'http://s.yangsoon.ngx',
        ),
        // 多域名session
        'SESSION_OPTIONS' => array(
            'domain' => 'yangsoon.ngx',
            'COOKIE_DOMAIN' => 'yangsoon.ngx',
        ),
        /* Cookie设置 */
        'COOKIE_DOMAIN'         => 'yangsoon.ngx',      // Cookie有效域名
        /* 文件上传相关配置 */
        'DOWNLOAD_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 5*1024*1024, //上传的文件大小限制 (0-不做限制)
            'exts'     => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml', //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => 'Uploads/Download/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ), //下载模型上传配置（文件上传类配置）

        /* 图片上传相关配置 */
        'PICTURE_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 8*1024*1024, //上传的文件大小限制 (0-不做限制)
            'exts'     => array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Ymd'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => 'Uploads/Images/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ), //图片上传相关配置（文件上传类配置）
        /* 视频上传相关配置 */
        'VIDEO_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 10*1024*1024, //上传的文件大小限制 (0-不做限制)
            'exts'     => array( "flv", "swf", "mkv", "avi", "rm", "rmvb", "mpeg", "mpg", "ogg", "ogv", "mov", "wmv", "mp4", "webm", "mp3", "wav", "mid"), //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Ymd'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => 'Uploads/Videos/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ), //视频上传相关配置（文件上传类配置）
        //----------------------sphinx配置------------------------------------------
        'SPHINX_ADDRESS' => '127.0.0.1',
        'SPHINX_PORT' => '9312',
        //----------------------redis配置------------------------------------------
        'REDIS_ADDRESS'              => '127.0.0.1',
        'REDIS_PORT'                 => '6379',
        'REDIS_PASSWORD'             => '',
        //----------------------redis配置------------------------------------------
    );
}if(ENVIRONMENR == 'PRODUCTION'){
    return array(
        //'配置项'=>'配置值'
        /* 调试配置 */
        'SHOW_PAGE_TRACE' => false,
        /* URL配置 */
        'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
        'URL_MODEL' => 2, //URL模式
        'VAR_URL_PARAMS' => '', // PATHINFO URL参数变量
        'URL_PATHINFO_DEPR' => '/', //PATHINFO URL分割符
        'URL_HTML_SUFFIX' => 'html', //伪静态
        'APP_SUB_DOMAIN_DEPLOY' => 1, // 开启子域名配置
        'APP_SUB_DOMAIN_RULES' => array(
            // 'ys16.580vps.com' => 'Admin',  // addemo子域名指向Admin模块
            // 'www.580vps.com' => 'Home',  // www子域名指向Home模块
            'ysm.580vps.com' => 'M',  // m子域名指向M模块
            'ysapi.580vps.com' => 'Api',  // api子域名指向Api模块
            'ysnews.580vps.com' => 'News',
            // 'v.580vps.com' => 'V',
            'yspic.580vps.com' => 'Pic',
            'ysuser.580vps.com' => 'User',
            // 's.580vps.com' => 'Search',
            'ys.580vps.com' => 'Admin',  // addemo子域名指向Admin模块
            'yswww.580vps.com' => 'News',  // www子域名指向Home模块
            'ysuser.580vps.com' => 'User',
            'ystest.580vps.com' => 'Test',
        ),
        /* 数据库配置 */
        'DB_TYPE' => 'mysql', // 数据库类型
        'DB_HOST' => '127.0.0.1', // 服务器地址
        'DB_NAME' => 'yangsoon', // 数据库名
        'DB_USER' => 'root', // 用户名
        'DB_PWD' => 'ecrun2017',  // 密码
        'DB_PORT' => '3306', // 端口
        'DB_PREFIX' => 'ys_', // 数据库表前缀
        'UPLOAD_URL'=>'http://ysqd.580vps.com/',//上传的静态资源
        'API_URL' => 'http://ysapi.580vps.com/',//api链接
        /* 用户相关配置 */
        'USER_INFO' =>array(
            'headimgurl' => 'Uploads/Avatur/'
        ),/* 用户相关配置 */
        /* 默认链接 */
        'DEFAULT_URL' => array(
            'ADMIN' => 'http://ys.580vps.com',
            'HOME' => 'http://yswww.580vps.com',
            'M' => 'http://ysm.580vps.com',
            'NEWS' => 'http://ysnews.580vps.com',
            'V' => 'http://ysv.580vps.com',
            'PIC' => 'http://yspic.580vps.com',
            'USER' => 'http://ysuser.580vps.com',
            'SEARCH' => 'http://ysseach.580vps.com',
        ),
        // 多域名session
        'SESSION_OPTIONS' => array(
            'domain' => '580vps.com',
            'COOKIE_DOMAIN' => '580vps.com',
        ),
        /* Cookie设置 */
        'COOKIE_DOMAIN'         => '580vps.com',      // Cookie有效域名
        /* 文件上传相关配置 */
        'DOWNLOAD_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 5*1024*1024, //上传的文件大小限制 (0-不做限制)
            'exts'     => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml', //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => 'Uploads/Download/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ), //下载模型上传配置（文件上传类配置）

        /* 图片上传相关配置 */
        'PICTURE_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 8*1024*1024, //上传的文件大小限制 (0-不做限制)
            'exts'     => array('jpg', 'gif', 'png', 'jpeg'), //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Ymd'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => 'Uploads/Images/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ), //图片上传相关配置（文件上传类配置）
        /* 视频上传相关配置 */
        'VIDEO_UPLOAD' => array(
            'mimes'    => '', //允许上传的文件MiMe类型
            'maxSize'  => 10*1024*1024, //上传的文件大小限制 (0-不做限制)
            'exts'     => array( "flv", "swf", "mkv", "avi", "rm", "rmvb", "mpeg", "mpg", "ogg", "ogv", "mov", "wmv", "mp4", "webm", "mp3", "wav", "mid"), //允许上传的文件后缀
            'autoSub'  => true, //自动子目录保存文件
            'subName'  => array('date', 'Ymd'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
            'rootPath' => 'Uploads/Videos/', //保存根路径
            'savePath' => '', //保存路径
            'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
            'saveExt'  => '', //文件保存后缀，空则使用原后缀
            'replace'  => false, //存在同名是否覆盖
            'hash'     => true, //是否生成hash编码
            'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
        ), //视频上传相关配置（文件上传类配置）
        //----------------------sphinx配置------------------------------------------
        'SPHINX_ADDRESS' => '127.0.0.1',
        'SPHINX_PORT' => '9312',
        //----------------------redis配置------------------------------------------
        'REDIS_ADDRESS'              => '127.0.0.1',
        'REDIS_PORT'                 => '6379',
        'REDIS_PASSWORD'             => '',
        //----------------------redis配置------------------------------------------
    );
}