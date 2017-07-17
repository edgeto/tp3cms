<?php

/**
 * 资源模型
 * Class ResourceModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model;

class ResourceModel extends Model{

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('name','require','请输入名称'), //默认情况下用正则进行验证
        array('controller','require','请输入控制器'), //默认情况下用正则进行验证
        array('action','require','请输入动作'), //默认情况下用正则进行验证
    );

    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array (
        array('add_time',NOW_TIME),  // 新增的时候把status字段设置为1
        array('update_time',NOW_TIME,self::MODEL_BOTH), // 对update_time字段在更新的时候写入当前时间戳
    );
}