<?php
/**
 * 广告首页
 * Class ApiController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Api\Controller;

class AdController extends BaseController {

    public function index(){
        
    }

    /**
     * M站轮播图
     * Function getWapCarousel
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param bool $return
     */
    public function getWapCarousel($return = false){
        //广告位 M站轮播图
        $ad_position_id = 1;
        $now = NOW_TIME ;
        $Ad = M('Ad');
        $map = array(
            'ad_position_id' => $ad_position_id,
            'status' => 0,
            'start_time' => array('lt',$now),
            'end_time' => array('gt',$now),
        );
        $ad_list = $Ad->where($map)->order('sort desc')->select();
        if($ad_list){
            $this->_code['code'] = 0;
            $this->_code['data'] = $ad_list;
        }
        if($return){
            return $ad_list;
        }
        $this->ajaxReturn($this->_code,'JSONP');exit;
    }

    
}