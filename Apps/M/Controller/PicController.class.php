<?php
/**
 * M站h图片
 * Class PicController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace M\Controller;

class PicController extends BaseController {

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
     * 图片分类列表
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
            //文章内容
            $pic = D('PicCategory')->where($map)->order('id desc,sort desc')->limit($limit)->select();
            if($article){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '';
                $this->_code['data'] = $pic;
            }
            $this->ajaxReturn($this->_code);
        }else{
            $map = array('status'=>0,'is_wap'=>1);
            $lists = D('PicCategory')->where($map)->order('id desc,sort desc')->select();
            $this->assign('lists',$lists);
            $this->display();
        }
    }

    /**
     * 图片列表
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
            //文章内容
            $pic = D('Pic')->where($map)->order('id desc,sort desc')->limit($limit)->select();
            if($pic){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '';
                $this->_code['data'] = $pic;
            }
            $this->ajaxReturn($this->_code);
        }
        $this->assign('id',$id);
        $this->display();
    }

    /**
     * 图片页面
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
        $Pic = D('Pic');
        $data = $Pic->where($map)->find();
        if(empty($data)){
            $this->error('没有数据');exit;
        }
        $this->assign('data',$data);
        //找图片
        $p_map = array(
            'pic_id' => $id,
        );
        $picImg = M('PicImg')->where($p_map)->order('sort desc')->select();
        $this->assign('picImg',$picImg);
        //同类其他
        $o_map = array(
            'status' => 0
        );
        $others = $Pic->relation(true)->order('rand()')->where($o_map)->limit(5)->select();
        $this->assign('others',$others);
        $this->display();
    }

}