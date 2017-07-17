<?php

/**
 * 图片模型
 * Class PicModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Common\Model;
use Think\Model\RelationModel;

class PicModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'picImg' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'PicImg',
            'foreign_key' => 'pic_id',
            'mapping_fields' => 'id,name,url',
            // 'mapping_limit' => 1,
            'mapping_order' => 'sort desc'
        ),
        'category' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'PicCategory',
            'foreign_key' => 'category_id',
            'mapping_fields' => 'id,name'
        ),
        'from' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'InfoFrom',
            'foreign_key' => 'from',
            'mapping_fields' => 'id,name,url',
            'as_fields' => 'id:from_id,name:from_name,url:from_url',
            'condition' => 'status = 0'//关联条件
        ),
    );

    /**
     * [$_code description]
     * @var array
     */
    public $_code = array(
        'code'=>1,
        'data'=>'',
        'msg'=>'',
    );

    /**
     * 首页推荐
     * Function getPicIndex
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param bool $return
     * @param int $category_id
     * @return mixed
     */
    public function getPicIndex($category_id = 0,$return = false){
        $map = array(
            'is_index' => 1,
            'status' => 0,
        );
        if($category_id){
            $map['category_id'] = $category_id;
        }
        $Pic_list = $this->where($map)->order('sort desc')->field('id,name,img,add_time,view,v_no')->select();
        if($Pic_list){
            $this->_code['code'] = 0;
            $this->_code['data'] = $Pic_list;
        }else{
            $this->_code['msg'] = '没有记录!';
        }
        return $Pic_list;
    }

    /**
     * 热门推荐
     * Function getRecompendIndex
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param bool $return
     * @param int $category_id
     * @return mixed
     */
    public function getRecompendIndex($category_id = 0,$return = false){
        $map = array(
            'is_recompend' => 1,
            'status' => 0,
        );
        if($category_id){
            $map['category_id'] = $category_id;
        }
        $Pic_list = $this->where($map)->order('sort desc')->field('id,name,img,add_time,view,v_no')->select();
        if($Pic_list){
            $this->_code['code'] = 0;
            $this->_code['data'] = $Pic_list;
        }else{
            $this->_code['msg'] = '没有记录!';
        }
        return $Pic_list;
    }

    /**
     * 取图片分类ID
     * Function getCategoryByPicId
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param  integer $pic_id [description]
     * @return [type]              [description]
     */
    public function getCategoryByPicId($pic_id = 0){
        $data = '';
        if($pic_id){
            $map = array(
                'id' => intval($pic_id)
            );
            $res = $this->relation('category')->where($map)->find();
            if(!empty($res['category'])){
                $data = $res['category']['name'];
            }
        }
        return $data;
    }

    /**
     * 根据条件获取数据
     * Function getPic
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param array $map
     * @param bool $return
     * @param string $order_by
     * @return mixed
     */
    public function getPic($map = array(),$limit = 0,$order_by = 'sort desc',$return = false){
        $pic_list = $this->where($map)->order($order_by)->field('id,name,desc,category_id,img,add_time,view,v_no')->limit($limit)->select();
        if($pic_list){
            $this->_code['code'] = 0;
            $this->_code['data'] = $pic_list;
        }else{
            $this->_code['msg'] = '没有记录!';
        }
        return $pic_list;
    }

}