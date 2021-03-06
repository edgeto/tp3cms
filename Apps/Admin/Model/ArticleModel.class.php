<?php

/**
 * 文章模型
 * Class ArticleModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class ArticleModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'category' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'ArtCategory',
            'foreign_key' => 'category_id',
            'mapping_fields' => 'id,name',
            'as_fields' => 'name:category_name'
        ),
        'comment' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'ArtComment',
            'foreign_key' => 'article_id',
            'mapping_fields' => 'id,content',
            'condition' => 'status = 0'//关联条件
        )
    );

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('title','require','请输入标题'), //默认情况下用正则进行验证
        array('title','','标题已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
//        array('content','require','请输入内容'), //默认情况下用正则进行验证
        array('sort','number','排序请输入纯数字！'),
        array('view','number','查看次数请输入纯数字！'),
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