<?php

/**
 * 收藏模型
 * Class CollectModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class CollectModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'user' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'User',
            'foreign_key' => 'user_id',
            'mapping_fields' => 'username'
        ),
        'article' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Article',
            'foreign_key' => 'article_id',
            'mapping_fields' => 'id,title',
        ),
        'video' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Video',
            'foreign_key' => 'video_id',
            'mapping_fields' => 'id,title',
        ),
        'pic' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Pic',
            'foreign_key' => 'pic_id',
            'mapping_fields' => 'id,name',
        ),
    );


    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array (
        array('add_time',NOW_TIME),  // 新增的时候把status字段设置为1
    );

}