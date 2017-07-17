<?php

/**
 * 广告位模型
 * Class AdPositionModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class AdPositionModel extends RelationModel{

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('name','require','请输入名称'), //默认情况下用正则进行验证
        array('width','number','宽度请输入纯数字！'),
        array('height','number','高度请输入纯数字！'),
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