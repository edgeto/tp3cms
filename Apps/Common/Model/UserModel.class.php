<?php

/**
 * 用户模型
 * Class UserModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Common\Model;
use Think\Model\RelationModel;

class UserModel extends RelationModel{

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('username','require','请输入用户名'), //默认情况下用正则进行验证
        array('username','','用户名已经存在！',0,'unique',self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('username','6,32','用户名长度不能小于6',2,'length',self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('nickname','','昵称已经存在！',2,'unique',self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('password','require','请输入密码'), //默认情况下用正则进行验证
        array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
        array('password','6,20','密码长度不能小于6',2,'length',self::MODEL_BOTH),
        array('email','','邮箱已被占用！',0,'unique',self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
    );

    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array (
        array('add_time',NOW_TIME),  // 新增的时候把status字段设置为1
        array('last_login_time',NOW_TIME),  // 新增的时候把status字段设置为1
        array('update_time',NOW_TIME,self::MODEL_BOTH), // 对update_time字段在更新的时候写入当前时间戳
        array('password','user_md5',3,'function',USER_AUTH_KEY) , // 对password字段在新增和编辑的时候使md5函数处理
        array('birthday','strtotime',3,'function') , 
    );

    /**
     * 检查登陆
     * Function login
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $username
     * @param $password
     * @return bool
     */
    public function chekcLogin($username = null, $password = null){
        if(empty($username)){
            $this->error = '帐号不能为空!';
            return false;
        }
        if(empty($password)){
            $this->error = '密码不能为空!';
            return false;
        }
        $where['username'] = $username;
        $user = $this->where($where)->find();
        if(is_array($user)){
            if($user['status'] == 1){
                $this->error = '用户被禁用!';
                return false;
            }
            /* 验证用户密码 */
            if(user_md5($password, USER_AUTH_KEY) === $user['password']){
                $this->autoLogin($user);
                $this->updateLogin($user['id']);
                return $user['id']; //登录成功，返回用户ID
            } else {
                $this->error = '密码错误!';
                return false;
            }
        } else {
            $this->error = '用户不存在或被禁用!';
            return false; //用户不存在或被禁用
        }
    }

    /**
     * 自动登陆
     * Function login
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $user
     * @return bool
     */
    private function autoLogin($user){
        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'id'             => $user['id'],
            'username'        => $user['username'],
        );
        cookie(md5('user_auth'),$user['id'],2678400);
        cookie(md5('user_auth_sign'),data_user_auth_sign($auth),2678400);
        session('user_auth', $auth);
        session('user_auth_sign', data_user_auth_sign($auth));
    }

    /**
     * 更新登陆信息
     * Function login
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $user_id
     * @return bool
     */
    public function updateLogin($user_id){
        $data = array(
            'id'              => $user_id,
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(),
        );
        $this->save($data);
        //登陆log
        $log = array(
            'user_id'              => $user_id,
            'login_time' => NOW_TIME,
            'login_ip'   => get_client_ip(),
        );
        D('UserLog')->add($log);
    }

    /**
     * 登陆
     * Function login
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $user_id
     * @return bool
     */
    public function login($user_id){
        /* 检测是否在当前应用注册 */
        $user = $this->field(true)->find($user_id);
        if(!$user || 1 == $user['status']) {
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return false;
        }
        //记录日志
        /* 登录用户 */
        $this->autoLogin($user);
        return true;
    }

    /**
     * 注销登陆
     * Function login
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $admin_id
     * @return bool
     */
    public function logout(){
        cookie(md5('user_auth'),null);
        cookie(md5('user_auth_sign'),null);
        session('user_auth', null);
        session('user_auth_sign', null);
        cookie(null); // 清空当前设定前缀的所有cookie值
        session(null); // 清空当前的session
    }

}