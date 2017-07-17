<?php

/**
 * 管理员模型
 * Class AdminModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class AdminModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'role' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Role',
            'foreign_key' => 'role_id',
            'mapping_fields' => 'is_super,name',
            'as_fields' => 'is_super:is_super,name:role_name'
        )
    );

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('username','require','请输入名称'), //默认情况下用正则进行验证
        array('username','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('password','require','请输入密码'), //默认情况下用正则进行验证
        array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
    );

    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array (
        array('add_time',NOW_TIME),  // 新增的时候把status字段设置为1
        array('update_time',NOW_TIME,self::MODEL_BOTH), // 对update_time字段在更新的时候写入当前时间戳
        array('password','admin_md5',3,'function',ADMIN_AUTH_KEY) , // 对password字段在新增和编辑的时候使md5函数处理
    );

    /**
     * 登陆
     * Function login
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $admin_id
     * @return bool
     */
    public function login($admin_id){
        /* 检测是否在当前应用注册 */
        $admin_user = $this->field(true)->find($admin_id);
        if(!$admin_user || 1 == $admin_user['status']) {
            $this->error = '用户不存在或已被禁用！'; //应用级别禁用
            return false;
        }
        //记录日志
        /* 登录用户 */
        $this->autoLogin($admin_user);
        return true;
    }

    /**
     * 检查登陆
     * Function login
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $admin_id
     * @return bool
     */
    public function chekcLogin($username = null, $password = null){
        if(empty($username) || empty($password)){
            $this->error = '管理员帐号或密码不能为空!';
            return false;
        }
        $where['username'] = $username;
        $admin_user = $this->where($where)->find();
        if(is_array($admin_user)){
            if($admin_user['status'] == 1){
                $this->error = '用户被禁用!';
                return false;
            }
            //未分组
            if(!$admin_user['role_id']){
                $this->error = '您还没有权限，请联系管理员添加权限!';
                return false;
            }
            /* 验证用户密码 */
            if(admin_md5($password, ADMIN_AUTH_KEY) === $admin_user['password']){
                $this->autoLogin($admin_user);
                $this->updateLogin($admin_user['id']);
                return $admin_user['id']; //登录成功，返回用户ID
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
     * 更新登陆信息
     * Function login
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $admin_id
     * @return bool
     */
    protected function updateLogin($admin_id){
        $data = array(
            'id'              => $admin_id,
            'login'           => array('exp', '`login`+1'),
            'last_login_time' => NOW_TIME,
            'last_login_ip'   => get_client_ip(),
        );
        $this->save($data);
        //登陆log
        $log = array(
            'user_id'              => $admin_id,
            'login_time' => NOW_TIME,
            'login_ip'   => get_client_ip(),
        );
        D('AdminLog')->add($log);
    }

    /**
     * 自动登陆
     * Function login
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param $admin_id
     * @return bool
     */
    private function autoLogin($admin_user){
        /* 记录登录SESSION和COOKIES */
        $auth = array(
            'id'             => $admin_user['id'],
            'username'        => $admin_user['username'],
            'id' => $admin_user['id'],
        );
        cookie(md5('admin_auth'),$admin_user['id'],2678400);
        cookie(md5('admin_auth_sign'),data_admin_auth_sign($auth),2678400);
        session('admin_auth', $auth);
        session('admin_auth_sign', data_admin_auth_sign($auth));
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
        cookie(md5('admin_auth'),null);
        cookie(md5('admin_auth_sign'),null);
        session('admin_auth', null);
        session('admin_auth_sign', null);
    }

    /**
     * 取管理员角色id
     * Function getRoleIdByAdminId
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     * @param  integer $admin_id [description]
     * @return [type]            [description]
     */
    public function getRoleIdByAdminId($admin_id = 0){
        $role_id = 0;
        if($admin_id){
            $map['id'] = $admin_id;
            $role_id = $this->where($map)->getField('role_id');
        }
        return $role_id;
    }

}