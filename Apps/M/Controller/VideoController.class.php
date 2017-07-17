<?php
/**
 * M站视频
 * Class VideoController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace M\Controller;

class VideoController extends BaseController {

    /**
     * 返回格式
     * @var array
     */
    protected $_code = array(
        'code' => 1,//失败
        'msg' => '操作失败!',
        'data' => '',
    );


    /**
     * 视频详情页面
     * Function getWapRecommend
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $id
     */
    public function detail($id = 0){
        $id = $id ? intval($id) : I('get.id',0,'intval');
        $map = array(
            'id' => $id,
            'status' => 0
        );
        $Video = D('Video');
        $data = $Video->where($map)->find();
        if(empty($data)){
            $this->error('没有数据');exit;
        }
        $this->assign('data',$data);
        //同类其他
        $category_id = $data['category_id'];
        $c_map = array(
            'is_index' => 0,
            'is_wap' => 1,
            'status' => 0,
            'category_id' => $category_id
        );
        $others = $Video->relation(true)->order('rand()')->where($c_map)->limit(5)->select();
        $this->assign('others',$others);
        $this->display();
    }

    /**
     * 视频分类列表
     * Function cateLists
     * User: edgeto
     * Date: 2016/06/22
     * Time: 9:00
     */
    public function cateLists(){
        $p = I('get.p',0);
        if($p){
            $map = array('status'=>0,'is_wap'=>1);
            $p = max(1,intval($p));
            $pSize = 10;
            $limit = ($p - 1) * $pSize.','.$pSize;
            //视频内容
            $Video = D('VideoCategory')->where($map)->order('id desc,sort desc')->limit($limit)->select();
            if($Video){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '';
                $this->_code['data'] = $Video;
            }
            $this->ajaxReturn($this->_code);
        }else{
            $map = array('status'=>0,'is_wap'=>1);
            $lists = D('VideoCategory')->where($map)->order('id desc,sort desc')->select();
            $this->assign('lists',$lists);
            $this->display();
        }
    }

    /**
     * 视频列表
     * Function lists
     * User: edgeto
     * Date: 2016/06/22
     * Time: 9:00
     * @param int $id 分类ID
     */
    public function lists($id = 0){
        $p = I('get.p',0);
        if($id && $p){
            $map = array('status'=>0,'is_wap'=>1,'category_id'=>intval($id));
            $p = max(1,intval($p));
            $pSize = 10;
            $limit = ($p - 1) * $pSize.','.$pSize;
            //视频内容
            $Video = D('Video')->relation('static')->where($map)->order('id desc,sort desc')->limit($limit)->select();
            if($Video){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '';
                $this->_code['data'] = $Video;
            }
            $this->ajaxReturn($this->_code);
        }
        $this->assign('id',$id);
        $this->display();
    }

}