<?php

/**
 * 角色模型
 * Class RoleModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model;

class RoleModel extends Model{

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('name','require','请输入名称'), //默认情况下用正则进行验证
    );

    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array (
        array('add_time',NOW_TIME),  // 新增的时候把status字段设置为1
        array('update_time',NOW_TIME,self::MODEL_BOTH), // 对update_time字段在更新的时候写入当前时间戳
    );

    /**
     * 是否是超级分组
     * Function isSuper
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param bool $return
     */
    public function isSuper($user_id = 0,$role_id = 0)
    {
        $return = false;
        if($user_id){
            $role_id = M('admin')->where("id = {$user_id}")->getField('role_id');
        }
        if($role_id){
            $return = $this->where("id = {$role_id}")->getField('is_super');
        }
        return $return;
    }
}