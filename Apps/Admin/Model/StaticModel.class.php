<?php

/**
 * 静态资源模型
 * Class StaticModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class StaticModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'article' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Article',
            'foreign_key' => 'article_id',
            'mapping_fields' => 'id,title'
        ),
        'video' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Video',
            'foreign_key' => 'video_id',
            'mapping_fields' => 'id,title'
        )
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