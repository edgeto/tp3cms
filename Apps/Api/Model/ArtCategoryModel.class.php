<?php

/**
 * 分类模型
 * Class ArtCategoryModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Api\Model;
use Think\Model\RelationModel;

class ArtCategoryModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'article' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'Article',
            'foreign_key' => 'category_id',
            'mapping_fields' => 'id,title',
        )
    );

}