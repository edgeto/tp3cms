<?php

/**
 * 导航位模型
 * Class NavPositionModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Common\Model;
use Think\Model\RelationModel;

class NavPositionModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'nav' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'Nav',
            'foreign_key' => 'nav_position_id',
            'mapping_fields' => 'id,name,url',
            'mapping_order' => 'sort desc',
            'condition' => 'status = 0'//关联条件
        )
    );

    /**
     * 根据导航位置取导航
     * Function getNavByPosition
     * User: edgeto
     * Date: 2016/06/17
     * Time: 17：00
     * [getNavByPosition description]
     * @param  integer $nav_position_id 
     * @return array                
     */
    public function getNavByPosition($nav_position_id = 0){
        $data = array();
        if($nav_position_id){
            $map = array(
                'nav_position_id'=>$nav_position_id,
                'status'=>0,
            );
            $data = M('Nav')->where($map)->order('sort desc')->select();
            if($data){
               $data = format_nav($data);
            }
        }
        return $data;
    }
}