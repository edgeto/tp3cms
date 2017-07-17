<?php

/**
 * 评论模型
 * Class ArtCommentModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class ArtCommentModel extends RelationModel{

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
        )
    );

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('article_id','require','请针对问题进行回答'), //默认情况下用正则进行验证
        array('content','require','请输入内容'), //默认情况下用正则进行验证
        array('sort','number','排序请输入纯数字！'),
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