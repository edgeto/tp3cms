<?php

/**
 * 友情链接位模型
 * Class NavPositionModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Common\Model;
use Think\Model\RelationModel;

class FriendlyPositionModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'friendly' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'Friendly',
            'foreign_key' => 'friendly_position_id',
            'mapping_fields' => 'id,name,url',
            'mapping_order' => 'sort desc',
            'condition' => 'status = 0'//关联条件
        )
    );

    /**
     * 根据友情链接位置取链接
     * Function getFriendlyByPosition
     * User: edgeto
     * Date: 2016/06/17
     * Time: 17：00
     * [getNavByPosition description]
     * @param  integer $friendly_position_id 
     * @return array                
     */
    public function getFriendlyByPosition($friendly_position_id = 0){
        $data = array();
        if($friendly_position_id){
            $map = array(
                'friendly_position_id'=>$friendly_position_id,
                'status'=>0,
            );
            $data = M('Friendly')->where($map)->order('sort desc')->select();
        }
        return $data;
    }
}