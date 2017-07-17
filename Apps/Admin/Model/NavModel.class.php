<?php

/**
 * 导航模型
 * Class NavModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class NavModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'nav_position' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'NavPosition',
            'foreign_key' => 'nav_position_id',
            'mapping_fields' => 'id,name',
            'as_fields' => 'id:nav_position_id,name:nav_position_name',
        )
    );

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('sort','number','排序请输入纯数字！'),
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

}