<?php
/**
 * 后台公共库文件
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 10:58
 */

/**
 * 管理员是否已登陆
 * Function is_admin
 * User: edgeto
 * Date: 2016/04/12
 * Time: 16:00
 * @return int|mixed
 */
function is_admin(){
    check_cookie_admin();
    $admin = session('admin_auth');
    $admin_auth_sign = session('admin_auth_sign');
    if (empty($admin)) {
        return 0;
    } else {
        return $admin_auth_sign == data_admin_auth_sign($admin) ? $admin['id'] : 0;
    }
}

/**
 * 管理员Cookie
 * Function check_cookie_admin
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13：00
 * @return bool
 */
function check_cookie_admin(){
    $admin_id = cookie(md5('admin_auth'));
    $admin_auth_sign = cookie(md5('admin_auth_sign'));
    //TODO::登录前操作
    if(empty($admin_id)){
        return false;
    }
    $Admin = D('Admin');
    $admin_user = $Admin->field(true)->find($admin_id);
    if(empty($admin_user))
    {
        return false;
    }
    $auth = array(
        'id'             => $admin_user['id'],
        'username'        => $admin_user['username'],
        'id' => $admin_user['id'],
    );
    if($admin_auth_sign != data_admin_auth_sign($auth)){
        return false;
    }
    $Admin->login($admin_user['id']);
}

/**
 * 管理员加密
 * Function data_admin_auth_sign
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param $data
 * @return string
 */
function data_admin_auth_sign($data) {
    //数据类型检测
    if(!is_array($data)){
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

//后台管理员加密KEY
define('ADMIN_AUTH_KEY', '8#n>Y8,J]?5Ps7cNX4-w /!SHf}2$]TFA@b?]#,j');

/**
 * 管理员密码加密
 * Function admin_md5
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param $str
 * @param string $key
 * @return string
 */
function admin_md5($str, $key = 'ThinkUCenter'){
    return '' === $str ? '' : md5(sha1($str) . $key);
}

/**
 * 管理员是否是超级管理员
 * Function is_super
 * User: edgeto
 * Date: 2016/04/12
 * Time: 16:00
 * @return int|mixed
 */
function is_super(){
    $is_super = D('Role')->isSuper(ADMIN_ID);
    return $is_super;
}

/**
 * 取资源名
 * Function getResourceName
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_resource_name($id = 0){
    $data = S('admin_resource_list');
    if(empty($data)){
        $data = M('Resource')->select();
        $tmp = array();
        foreach($data as $d){
            $tmp[$d['id']] = $d['name'];
        }
        $data = $tmp;
        S('admin_resource_list',$data);
    }
    if($id && isset($data[$id])){
        return $data[$id];
    }
    return '';
}

/**
 * 更新资源缓存
 * Function cache_resource_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 */
function cache_resource_list(){
    $data = M('Resource')->select();
    $tmp = array();
    foreach($data as $d){
        $tmp[$d['id']] = $d['name'];
    }
    $data = $tmp;
    S('admin_resource_list',$data);
}

/**
 * 取角色名
 * Function get_role_name
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_role_name($id = 0){
    $data = S('admin_role_list');
    if(empty($data)){
        $map['is_super'] = 0;//不显示超级管理员
        $data = M('Role')->where($map)->select();
        $tmp = array();
        foreach($data as $d){
            $tmp[$d['id']] = $d['name'];
        }
        $data = $tmp;
        S('admin_role_list',$data);
    }
    if($id && isset($data[$id])){
        return $data[$id];
    }
    return '';
}

/**
 * 更新角色缓存
 * Function cache_role_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 */
function cache_role_list(){
    $map['is_super'] = 0;//不显示超级管理员
    $data = M('Role')->where($map)->select();
    $tmp = array();
    foreach($data as $d){
        $tmp[$d['id']] = $d['name'];
    }
    $data = $tmp;
    S('admin_role_list',$data);
}

/**
 * 取角色列表
 * Function get_role_list
 * User: edgeto
 * Date: 2016/06/15
 * Time: 13:00
 * @param int $id
 * @return string
 */
function get_role_list(){
    $data = S('admin_role_list');
    return $data;
}