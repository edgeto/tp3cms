<?php

/**
 * 广告模型
 * Class AdModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Common\Model;
use Think\Model\RelationModel;

class AdModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'ad_position' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'AdPosition',
            'foreign_key' => 'ad_position_id',
            'mapping_fields' => 'id,name'
        )
    );

    /**
     * 广告
     * Function getAds
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param bool $return
     */
    public function getAds($ad_position_id = 1,$return = false){
        //广告位 M站轮播图
        $now = NOW_TIME ;
        $map = array(
            'ad_position_id' => $ad_position_id,
            'status' => 0,
            'start_time' => array('lt',$now),
            'end_time' => array('gt',$now),
        );
        $ad_list = $this->where($map)->order('sort desc')->select();
        if($ad_list){
            $this->_code['code'] = 0;
            $this->_code['data'] = $ad_list;
        }
        return $ad_list;
    }

}