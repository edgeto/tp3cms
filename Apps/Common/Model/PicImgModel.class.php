<?php

/**
 * 图片模型
 * Class PicImgModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Common\Model;
use Think\Model\RelationModel;

class PicImgModel extends RelationModel{

    /**
     * 根据导航位置取导航
     * Function getPicImgBypicId
     * User: edgeto
     * Date: 2016/06/17
     * Time: 17：00
     * [getPicImgBypicId description]
     * @param  integer $pic_id 
     * @return array                
     */
    public function getPicImgBypicId($pic_id = 0){
        $data = array();
        if($pic_id){
            $map = array(
                'pic_id'=>$pic_id,
            );
            $data = $this->where($map)->order('sort desc')->select();
        }
        return $data;
    }
}