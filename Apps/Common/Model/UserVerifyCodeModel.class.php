<?php

/**
 * 用户验证码
 * Class UserVerifyCodeModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Common\Model;
use Think\Model;

class UserVerifyCodeModel extends Model{

    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array (
        array('add_time',NOW_TIME), 
        array('expire_time','expire_time',3,'function',user_verify_code_expire) ,
    );

}