<?php

/**
 * 管理员日志模型
 * Class AdminLog
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class AdminLogModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'admin' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Admin',
            'foreign_key' => 'user_id',
            'mapping_fields' => 'id,username',
            'as_fields' => 'id:admin_id,username:username',
        )
    );

}