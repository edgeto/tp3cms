<?php

/**
 * 相册相片模型
 * Class PicImgModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class PicImgModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'pic' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Pic',
            'foreign_key' => 'pic_id',
            'mapping_fields' => 'id,name',
            'as_fields' => 'name:pic_name'
        )
    );

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('pic_id','require','请输入相册ID'), //默认情况下用正则进行验证
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