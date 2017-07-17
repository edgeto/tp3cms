<?php

/**
 * 收藏模型
 * Class CollectModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Common\Model;
use Think\Model\RelationModel;

class CollectModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'news' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Article',
            'foreign_key' => 'article_id',
            'mapping_fields' => 'title,abstract,add_time,view,img',
            'as_fields' => 'title:title,abstract:abstract,add_time:add_time,view:view,img:img',
        ),
        'video' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Video',
            'foreign_key' => 'video_id',
            'mapping_fields' => 'title,abstract,add_time,view,img',
            'as_fields' => 'title:title,abstract:abstract,add_time:add_time,view:view,img:img',
        ),
        'pic' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Pic',
            'foreign_key' => 'pic_id',
            'mapping_fields' => 'name,img,desc,add_time,view',
            'as_fields' => 'name:name,img:img,desc:desc,add_time:add_time,view:view',
        ),
    );

}