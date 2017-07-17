<?php

/**
 * 用户模型
 * Class UserModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class UserModel extends RelationModel{

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('username','require','请输入用户名'), //默认情况下用正则进行验证
        array('username','','用户名已经存在！',0,'unique',self::MODEL_BOTH), // 在新增的时候验证name字段是否唯一
        array('username','6,32','用户名长度不能小于6',2,'length',self::MODEL_BOTH),
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
    );

}