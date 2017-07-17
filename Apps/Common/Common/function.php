<?php
/**
 * 系统公共库文件
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 10:58
 */

/**
 * 检测输入的验证码是否正确
 * Function check_verify
 * User: edgeto
 * Date: 2016/06/16
 * Time: 10:00
 * @param $code 用户输入的验证码字符串
 * @param string $id
 * @return bool
 */
function check_verify($code, $id = ''){
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}
/**
 * 结构树
 * @param  array  $data           源数组
 * @param  string $id_name        id名称
 * @param  string $parent_id_name parent_id名称
 * @param  string $son            孩子键名
 * @param  array $current_id_arr  用于面包屑 breadcrumbs 
 * @return [type]                 [description]
 */
function structureTree($data = array(), $id_name = 'id', $parent_id_name = 'pid', $son = 'son',$current_id_arr = array())
{
    $structureTreeData = array();
    $tmpData = array();
    foreach ($data as $dataValue) {
        // 过滤不显示的导航
        if(isset($dataValue['show_nav']) && $dataValue['show_nav'] == 0){
            continue;
        }
        $tmpData[$dataValue[$id_name]] = $dataValue;
    }
    foreach ($tmpData as &$tmpValue) {
        $tmpValue['current'] = 0;
        if(in_array($tmpValue[$id_name],$current_id_arr)){
            $tmpValue['current'] = 1;
        }
        if (isset($tmpData[$tmpValue[$parent_id_name]])) {
            $tmpData[$tmpValue[$parent_id_name]][$son][] = &$tmpData[$tmpValue[$id_name]];
        } else {
            $structureTreeData[] = &$tmpData[$tmpValue[$id_name]];
        }
    } 
    // 树状等级分类
    $structureTreeData = levelTree($structureTreeData);
    return $structureTreeData;
}

/**
 * select 树状无限分类 selectTree 
 * @param  array  $data           [数据源]
 * @param  array  $level          [等级]
 * @param  array  $res            [返回数据]
 * @param  string $id_name        [字段]
 * @param  string $parent_id_name [父字段]
 * @param  string $son            [子分类]
 * @return [type]                 [description]
 */
function levelTree($data = array(),$level = array(),$res = array(),$id_name = 'id', $parent_id_name = 'pid', $son = 'son')
{
    if($data){
        foreach ($data as $key => &$value) {
            $parent_id = $value[$parent_id_name];
            if(isset($level[$parent_id])){
                $level[$value[$id_name]] = $level[$parent_id] + 1;
            }else{
                $level[$value[$id_name]] = 0;
            }
            $value['level'] = $level[$value[$id_name]];
            $res[] = $value;
            if(!empty($value['son'])){
                levelTree($value['son'],$level,$res);
            }
        }
    }
    return $res;
}

/**
 * 树状等级select显示
 * @param  [type] $parent_list [description]
 * @param  string $son         [description]
 * @return [type]              [description]
 */
function selectTree($parent_list, $level = '', $son = 'son', $id = 'id', $name = 'name'){
    if($parent_list){
        foreach ($parent_list as $key => $val) {
            $level = str_repeat('│　 ',$val['level']);
            if($val == end($parent_list)){
                echo '<option value="'. $val[$id] .'">'. $level .'└─ '. $val[$name ] .'</option>';
            }else{
                echo '<option value="'. $val[$id] .'">'. $level .'├─ '. $val[$name ] .'</option>';
            }
            if(!empty($val[$son])){
                selectTree($val[$son],$level);
            }
        }
    }
}

/**
 * 树状等级select修改显示
 * @param  [type]  $parent_list [description]
 * @param  string  $son         [description]
 * @param  integer $pid         [description]
 * @param  integer $self_id     [description]
 * @return [type]               [description]
 */
function selectEditTree($parent_list,$level = '',$pid = 0,$self_id = 0, $son = 'son', $id = 'id', $name = 'name'){
    if($parent_list){
        foreach ($parent_list as $key => $val) {
            $level = str_repeat('│　 ',$val['level']);
            if($val[$id] != $self_id){
                if($val == end($parent_list)){
                    if($val[$id] == $pid){
                        echo '<option value="'. $val[$id] .'" selected>'. $level .'└─ '. $val[$name] .'</option>';
                    }else{
                        echo '<option value="'. $val[$id] .'">'. $level .'└─ '. $val[$name] .'</option>';
                    }
                }else{
                    if($val[$id] == $pid){
                        echo '<option value="'. $val[$id] .'" selected>'. $level .'├─ '. $val[$name] .'</option>';
                    }else{
                        echo '<option value="'. $val[$id] .'">'. $level .'├─ '. $val[$name] .'</option>';
                    }
                }
                if(!empty($val[$son])){
                    selectEditTree($val[$son],$level,$pid,$self_id);
                }
            }
        }
    }
}

/**
 * 找资源父ID
 * @param  integer $pid [description]
 * @return [type]       [description]
 */
function getResourcePid($pid = 0)
{
    static $data = array();
    if($pid){
        $map['id'] = $pid;
        $res = M('resource')->where($map)->field('id,pid')->find();
        if($res){
            $data[] = $res['id'];
            if(!empty($res['pid'])){
                getResourcePid($res['pid']);
            }
        }
    }
    return $data;
}


/**
 * 得到操作系统信息
 * Function get_system
 * User: edgeto
 * Date: 2016/06/16
 * Time: 14:00
 * @return string
 */
function get_system() {
    $sys = $_SERVER['HTTP_USER_AGENT'];

    if (stripos($sys, "NT 10")) {
        $os = "Windows 10";
    } else if (stripos($sys, "NT 8")) {
        $os = "Windows 8";
    } else if (stripos($sys, "NT 6.1")) {
        $os = "Windows 7";
    } else if (stripos($sys, "NT 6.0")) {
        $os = "Windows Vista";
    }  else if (stripos($sys, "NT 5.1")) {
        $os = "Windows XP";
    } else if (stripos($sys, "NT 5.2")) {
        $os = "Windows Server 2003";
    } else if (stripos($sys, "NT 5")) {
        $os = "Windows 2000";
    } else if (stripos($sys, "NT 4.9")) {
        $os = "Windows ME";
    } else if (stripos($sys, "NT 4")) {
        $os = "Windows NT 4.0";
    } else if (stripos($sys, "98")) {
        $os = "Windows 98";
    } else if (stripos($sys, "95")) {
        $os = "Windows 95";
    } else if (stripos($sys, "Mac")) {
        $os = "Mac";
    } else if (stripos($sys, "Linux")) {
        $os = "Linux";
    } else if (stripos($sys, "Unix")) {
        $os = "Unix";
    } else if (stripos($sys, "FreeBSD")) {
        $os = "FreeBSD";
    } else if (stripos($sys, "SunOS")) {
        $os = "SunOS";
    } else if (stripos($sys, "BeOS")) {
        $os = "BeOS";
    } else if (stripos($sys, "OS/2")) {
        $os = "OS/2";
    } else if (stripos($sys, "PC")) {
        $os = "Macintosh";
    } else if(stripos($sys, "AIX")) {
        $os = "AIX";
    } else {
        $os = "未知操作系统";
    }

    return $os;
}

/**
 * 生成新的token，即自动登录功能使用的动态密码
 * Function generate_token
 * User: edgeto
 * Date: 2016/04/12
 * Time: 16:00
 * @return int
 */
function generate_token(){
    $token = mt_rand(10000000, 99999999);  //8位随机数
    return $token;
}

/**
 * 获得当前格林威治时间的时间戳
 * Function gmtime
 * User: edgeto
 * Date: 2016/04/12
 * Time: 16:00
 * @return int
 */
function gmtime() {
    return (time () - date ( 'Z' ));
}

/**
 * 生成随机字符串
 * @param int $length 生成字符串长度
 * @param bool $symbol 是否包含符号生成字符串长度
 * @param bool $casesensitivity 是否区分大小写，默认区分
 * @return string
 */
function generate_rand_text($length = 8, $symbol=false, $casesensitivity=true){
    // 字母和数字
    //$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    //去掉 小写字母 i o l 大写字母 I O L 数字 0 1 9 figochen 2014-10-09
    $chars = 'abcdefghjkmnprstuvwxyz2345678';
    if($casesensitivity) {
        $chars .= 'ABCDEFGHJKMNPRSTUVWXYZ';
    }
    if($symbol)
    {
        // 标点符号
        $chars .= '!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
    }

    $text = '';
    for ( $i = 0; $i < $length; $i++ )
    {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        $text .= $chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    if($casesensitivity==false){
        $text=strtolower($text);
    }

    return $text;
}

/**
 * 生成随机字符串，字母+数字，可指定字母和数字的数量，位置随机
 * @param int $number_count
 * @param int $char_count
 * @param int $upper_count 大写字母数量
 * @return string
 */
function generate_simple_rand_text($number_count=4, $char_count=2, $upper_count=0){
    $chars = 'abcdefghjkmnprstuvwxyz';
    $upper='ABCDEFGHJKMNPRSTUVWXYZ';
    $nums = '2345678';

    $arr=array();
    for ( $i = 0; $i < $char_count; $i++ )
    {
        $arr[]=$chars[ mt_rand(0, strlen($chars) - 1) ];
    }
    for ( $i = 0; $i < $upper_count; $i++ )
    {
        $arr[]=$upper[ mt_rand(0, strlen($upper) - 1) ];
    }
    for ( $i = 0; $i < $number_count; $i++ )
    {
        $arr[]=$nums[ mt_rand(0, strlen($nums) - 1) ];
    }
    shuffle($arr);
    $text=implode('', $arr);

    return $text;
}

/**
 * 时间格式化
 * @param string $str
 * @return int
 */
function local_strtotime($str = ''){
    $time = time();
    if($str){
        $time = strtotime($str);
    }
    return $time;
}

/**
 * curl操作
 * @param $url
 * @param null $post_data
 * @param string $get_post
 * @param bool $http_build_query
 * @param bool $check_ssl
 * @param array $headers
 * @param string $log
 * @return string
 */
function curl($url, $post_data=NULL, $get_post='post', $http_build_query=false, $check_ssl=false, $headers=array(),$log=''){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    if(is_array($headers) && $headers){
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    if($check_ssl){
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);// 对认证证书来源的检查，0表示阻止对证书的合法性的检查。
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);// 从证书中检查SSL加密算法是否存在
    }
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
    if($get_post == 'post'){
        curl_setopt($ch, CURLOPT_POST, 1);
        if($http_build_query)$post_data = http_build_query($post_data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch,CURLOPT_REFERER ,$url);
    $return = curl_exec($ch);
    curl_close($ch);
    return trim($return);
}

/**
 * 是否微信
 * Function is_weixin
 * User: edgeto
 * Date: 2016/07/12
 * Time: 14:00
 * @return bool
 */
function is_weixin(){
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
        return true;
    }
    return false;
}

/**
 * 是否手机
 * Function is_mobile
 * User: edgeto
 * Date: 2016/07/12
 * Time: 14：00
 * @return bool
 */
function is_mobile() {
    //通过域名进行判断
    $serverName = $_SERVER['SERVER_NAME'];
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        }
    }
    return false;
}

/**
 * 用户是否已登陆
 * Function is_user
 * User: edgeto
 * Date: 2016/04/12
 * Time: 16:00
 * @return int|mixed
 */
function is_user(){
    check_cookie_user();
    $user = session('user_auth');
    $user_auth_sign = session('user_auth_sign');
    if (empty($user)) {
        return 0;
    } else {
        return $user_auth_sign == data_user_auth_sign($user) ? $user['id'] : 0;
    }
}

/**
 * 用户Cookie
 * Function check_cookie_user
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13：00
 * @return bool
 */
function check_cookie_user(){
    $user_id = cookie(md5('user_auth'));
    $user_auth_sign = cookie(md5('user_auth_sign'));
    //TODO::登录前操作
    if(empty($user_id)){
        return false;
    }
    $User = D('User');
    $user_info = $User->field(true)->find($user_id);
    if(empty($user_info))
    {
        return false;
    }
    $auth = array(
        'id'             => $user_info['id'],
        'username'        => $user_info['username'],
    );
    if($user_auth_sign != data_user_auth_sign($auth)){
        return false;
    }
    $User->login($user_info['id']);
}

/**
 * 用户加密
 * Function data_user_auth_sign
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param $data
 * @return string
 */
function data_user_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

//用户加密KEY
define('USER_AUTH_KEY', 'Kamh5DZb_&E_dUkf|+:fw[m>PZ4Z;!$PF$*[|vU/');

/**
 * 用户密码加密
 * Function user_md5
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param $str
 * @param string $key
 * @return string
 */
function user_md5($str, $key = 'ThinkUCenter'){
    return '' === $str ? '' : md5(sha1($str) . $key);
}

/**
 * 取文章分类名
 * Function get_artcategory_name
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_art_category_name($id = 0){
    $data = S('art_category_list');
    if(empty($data)){
        $data = M('ArtCategory')->select();
        $tmp = array();
        foreach($data as $d){
            $tmp[$d['id']] = $d['name'];
        }
        $data = $tmp;
        S('art_category_list',$data);
    }
    if($id && isset($data[$id])){
        return $data[$id];
    }
    return '';
}
/**
 * 取文章分类列表
 * Function get_art_category_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_art_category_list(){
    $data = S('art_category_list');
    if(!$data){
        cache_art_category_list();
        $data = S('art_category_list');
    }
    return $data;
}

/**
 * 更新文章分类缓存
 * Function cache_art_category_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 */
function cache_art_category_list(){
    $data = M('ArtCategory')->select();
    $tmp = array();
    foreach($data as $d){
        $tmp[$d['id']] = $d['name'];
    }
    $data = $tmp;
    S('art_category_list',$data);
}

/**
 * 取视频分类名
 * Function get_video_category_name
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_video_category_name($id = 0){
    $data = S('video_category_list');
    if(empty($data)){
        $data = M('VideoCategory')->select();
        $tmp = array();
        foreach($data as $d){
            $tmp[$d['id']] = $d['name'];
        }
        $data = $tmp;
        S('video_category_list',$data);
    }
    if($id && isset($data[$id])){
        return $data[$id];
    }
    return '';
}

/**
 * 取视频分类列表
 * Function get_video_category_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_video_category_list(){
    $data = S('video_category_list');
    return $data;
}

/**
 * 更新视频分类缓存
 * Function cache_role_name
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 */
function cache_video_category_list(){
    $data = M('VideoCategory')->select();
    $tmp = array();
    foreach($data as $d){
        $tmp[$d['id']] = $d['name'];
    }
    $data = $tmp;
    S('video_category_list',$data);
}

/**
 * 取相册分类名
 * Function get_pic_name
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_pic_category_name($id = 0){
    $data = S('pic_category_list');
    if(empty($data)){
        $data = M('pic_category')->select();
        $tmp = array();
        foreach($data as $d){
            $tmp[$d['id']] = $d['name'];
        }
        $data = $tmp;
        S('pic_category_list',$data);
    }
    if($id && isset($data[$id])){
        return $data[$id];
    }
    return '';
}

/**
 * 取相册分类列表
 * Function get_pic_category_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_pic_category_list(){
    $data = S('pic_category_list');
    if(!$data){
        cache_pic_category_list();
        $data = S('pic_category_list');
    }
    return $data;
}

/**
 * 更新相册分类缓存
 * Function cache_pic_name
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 */
function cache_pic_category_list(){
    $data = M('pic_category')->select();
    $tmp = array();
    foreach($data as $d){
        $tmp[$d['id']] = $d['name'];
    }
    $data = $tmp;
    S('pic_category_list',$data);
}

/**
 * 取区域名
 * Function get_region_name
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_region_name($id = 0){
    $data = S('region_list');
    if(empty($data)){
        cache_region_list();
        $data = S('region_list');
    }
    if($id && isset($data[$id])){
        return $data[$id]['name'];
    }
    return '';
}

/**
 * 取区域列表
 * Function get_region_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_region_list(){
    $data = S('region_list');
    if(empty($data)){
        cache_region_list();
        $data = S('region_list');
    }
    return $data;
}

/**
 * 根据国家取省份
 * Function get_province_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_province_list($id = 1){
    $data = S('region_list');
    if(empty($data)){
        cache_region_list();
        $data = S('region_list');
    }
    $province = array();
    foreach ($data as $key => $value) {
        if($value['pid'] == $id){
            $province[] = $value;
        }
    }
    return $province;
}

/**
 * 更新区域缓存
 * Function cache_region_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 */
function cache_region_list(){
    $map = array(
        'status' => 0 
    );
    $data = M('Region')->where($map)->select();
    $tmp = array();
    foreach($data as $d){
        $tmp[$d['id']] = $d;
    }
    $data = $tmp;
    S('region_list',$data);
}

/**
 * 导航格式化
 * Function format_nav
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param  array  $data 
 * @return array
 */
function format_nav($data = array())
{
    if($data){
        $current_url = strtolower(__SELF__);
        $action_url = strtolower(__ACTION__);
        $action_url = str_ireplace('_','',$action_url);//不知道为什么linux下会出现admin_user这样的控制器
        foreach ($data as $key => &$value) {
            $value['active'] = 0;
            if(strtolower($value['url']) == $current_url || strtolower($value['url']) == $action_url || stripos($value['url'],$action_url) !== false){
                $value['active'] = 1;
            }   
        }
    }
    return $data;
}

/**
 * 取区来源名称
 * Function get_infofrom_name
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_infofrom_name($id = 0){
    $data = S('infofrom_list');
    if(empty($data)){
        $data = M('InfoFrom')->select();
        $tmp = array();
        foreach($data as $d){
            $tmp[$d['id']] = $d['name'];
        }
        $data = $tmp;
        S('infofrom_list',$data);
    }
    if($id && isset($data[$id])){
        return $data[$id];
    }
    return '';
}

/**
 * 取来源列表
 * Function get_infofrom_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_infofrom_list(){
    $data = S('infofrom_list');
    return $data;
}

/**
 * 更新来源缓存
 * Function cache_region_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 */
function cache_infofrom_list(){
    $data = M('InfoFrom')->select();
    $tmp = array();
    foreach($data as $d){
        $tmp[$d['id']] = $d['name'];
    }
    $data = $tmp;
    S('infofrom_list',$data);
}

/**
 * 取网站配置
 * Function get_web_config
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 */
function get_web_config(){
    $data = S('web_config');
    if(!$data){
        $map = array(
            'status' => 0
        );
        $data = D('config')->where($map)->getField('config_sign, config_value');
        if($data){
            S('web_config',$data);
        }
    }
    if($data){
        //添加配置
        C($data);
    }
    return $data;
}

/**
 * 上传图片
 * Function uploadPic
 * User: edgeto
 * Date: 2016/06/22
 * Time: 9:00
 * @param $rootPath 上传根目录
 * @return array|bool
 */
function uploadPic($rootPath = ''){
    $pic_upload_config = C('PICTURE_UPLOAD');
    if($rootPath){
        $pic_upload_config['rootPath'] =  $rootPath;
    }
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize   =     $pic_upload_config['maxSize'] ;// 设置附件上传大小
    $upload->exts      =     $pic_upload_config['exts'];// 设置附件上传类型
    $upload->rootPath  =    $pic_upload_config['rootPath']; // 设置附件上传根目录
    $upload->savePath  =     $pic_upload_config['savePath']; // 设置附件上传（子）目录
    $upload->saveName  =     $pic_upload_config['saveName']; // 上传文件的保存规则，支持数组和字符串方式定义
    $upload->autoSub  =     $pic_upload_config['autoSub']; // 自动使用子目录保存上传文件 默认为true
    $upload->subName  =     $pic_upload_config['subName']; // 子目录创建方式，采用数组或者字符串方式定义
    $upload->hash  =     $pic_upload_config['hash']; // 是否生成文件的hash编码 默认为true
    // 上传文件
    $info   =   $upload->upload();
    if(!$info) {// 上传错误提示错误信息
        return $upload->getError();
//            return false;
    }else{// 上传成功
        $url = array();
        $data = array();
        foreach($info as $i){
            $_url = $upload->rootPath .$i['savepath'].$i['savename'];
            $url[]= $_url;
            $data[] = array('url'=>$_url);
        }
        //添加到静态资源表 不加
       /* if($data){
            $Static = D("Static");
            $Static->addAll($data);
        }*/
        return $url ;
    }
}

/**
 * 用户密码强度
 * Function user_password_intensity
 * User: edgeto
 * Date: 2016/06/22
 * Time: 9:00
 * @param  string $password [description]
 * @return [type]           [description]
 */
function user_password_intensity($password = ''){
    $intensity = 0;
    if($password){
        if(preg_match("/[0-9]+/",$password))
           {
              $intensity ++; 
           }
           if(preg_match("/[0-9]{3,}/",$password))
           {
              $intensity ++; 
           }
           if(preg_match("/[a-z]+/",$password))
           {
              $intensity ++; 
           }
           if(preg_match("/[a-z]{3,}/",$password))
           {
              $intensity ++; 
           }
           if(preg_match("/[A-Z]+/",$password))
           {
              $intensity ++; 
           }
           if(preg_match("/[A-Z]{3,}/",$password))
           {
              $intensity ++; 
           }
           if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/",$password))
           {
              $intensity += 2; 
           }
           if(preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]{3,}/",$password))
           {
              $intensity ++ ; 
           }
           if(strlen($password) >= 10)
           {
              $intensity ++; 
           }
    }
    return $intensity;
}

/**
 * 发送邮件
 * Function sendMail
 * User: edgeto
 * Date: 2016/06/22
 * Time: 9:00
 * @param  [type] $to      [description]
 * @param  [type] $title   [description]
 * @param  [type] $content [description]
 * @return [array|bool]    [description]
 */
function sendMail($to, $title, $content) {
    import("Common.Common.PHPMailer-master.PHPMailerAutoload");
    PHPMailerAutoload('phpmailer');
    PHPMailerAutoload('smtp');
    $mail           = new PHPMailer();       //得到一个PHPMailer实例 
    $mail->CharSet  = "utf-8";                //设置采用utf-8中文编码(内容不会乱码) 
    $mail->IsSMTP();                          //设置采用SMTP方式发送邮件 
    $mail->Host     = "smtp.163.com";        //设置邮件服务器的地址(若为163邮箱，则是smtp.163.com)
    $mail->Port     = 25;                     //设置邮件服务器的端口，默认为25 
    $mail->From     = C('mail');             //设置发件人的邮箱地址
    $mail->FromName = C('systemName');           //设置发件人的姓名(可随意) 
    $mail->SMTPAuth = true;                   //设置SMTP是否需要密码验证，true表示需要 
    $mail->Username = C('mail');               //(后面有解释说明为何设置为发件人)
    $mail->Password = C('mailPassword');
    $mail->Subject  = $title;                 //主题 
    $mail->AltBody  = "text/html";
    $contents = $content;
    $mail->Body = $contents;            //内容   
    $mail->IsHTML(true);      
    $mail->WordWrap = 50;                    //设置每行的字符数
    $mail->AddReplyTo(C('mail'), "from");    //设置回复的收件人的地址(from可随意) 
    $mail->AddAddress($to, "to");        //设置收件的地址(to可随意) 
    $result = $mail->Send();
    if ($result) {
      $returnData['err'] = 0;
      $returnData['msg'] = 'send success'; 
    } else {
      $returnData['err'] = 1;
      $returnData['msg'] = "{$mail->ErrorInfo}";
    }
    return $returnData;
}

//用户加密KEY
define('user_verify_code_expire', C('user_verify_code_expire'));
function expire_time($expire_time = 600){
    return NOW_TIME + $expire_time;
}

/**
 * [sphinx 返回一个sphinx对象]
 * @author StanleyYuen <350204080@qq.com>
 */
function sphinx() {
    $sphinx = new \SphinxClient();
    $sphinx->setServer(C('SPHINX_ADDRESS'), C('SPHINX_PORT'));
    return $sphinx;
}

/**
 * 二维码生成
 * @param  string $str [description]
 * @return [type]      [description]
 */
function qrcode($str ='')
{
    $data = '';
    if($str){
        $a = import("Common.Common.Phpqrcode.phpqrcode");
        $url = urldecode($str);
        $data = QRcode::png($url);
    }
    // return $data;
}

/**
 * 获取后台默认模板
 * @return [type] [description]
 */
function get_admin_theme()
{
    return 'Default';
}

/**
 * 压缩html
 * @return [type] [description]
 */
function trim_html($content){
    //1、去掉//注释
    $content = preg_replace('/(?<![:\'"\*\w\-\(\=])\/\/[^\r\n].*/', '', $content); 
    //2、将多个空格转换成一个空格
    $content = preg_replace('/\s{2,}/', ' ', $content);  
    //3、去掉><之间的空格
    $content = preg_replace('/>\s?(\S*)?\s?</', '>$1<', $content);
    //4、去掉/**/注释
    $content = preg_replace('/\/\*[\s\S]*?\*\//', '', $content); 
    //5、去掉<!---->l注释，但不包含<!--[if]><![]-->
    $content = preg_replace('/\<\!\-\-(?!\[if)[\s\S]*?\-\-\>/', '', $content);  
    $content = preg_replace('/((?<=[^\x{4e00}-\x{9fa5}\w\*\)])\s)|((?<=\w)\s(?=[^\x{4e00}-\x{9fa5}\w\.\#\$\-]))/u', '', $content);
    return $content;
}