<?php
/**
 * News首页
 * Class IndexController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */

namespace Pic\Controller;

class IndexController extends BaseController {

	/**
     * 首页
     * Function index
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function index(){
        header('Cache-Control: max-age=600');
    	$Pic = D('Pic');
    	// 轮播图
        $this->assign('carousel',D('Ad')->getAds(5));
        // 精选专辑
        $this->assign('special_list',$Pic->getPicIndex()); 
        // 热门推荐
        $this->assign('recompend_list',$Pic->getRecompendIndex());
        $this->display();
    }

    /**
     * 搜索
     * Function search
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function search(){
        $p = I('get.p',1);
        $eachPageNum = 16;
        $keyword =I('get.keyword');
        $is_search = true;
        $list = array();
        $show = '';
        $map = array(
            'status' => 0
        );
        if($keyword){
            $sphinx = sphinx();
            $sphinx->setFilter('status', array(0));
            $sphinx->setLimits(($p-1) * $eachPageNum, $eachPageNum, 1000);
            $res = $sphinx->Query ($keyword, "pic");
            $ids = array_keys($res['matches']);
            if($ids){
                $map['id'] = array('in',$ids);
                $list = M('pic')->where($map)->select();
            }
            //分页
            $Page      = new \Think\Page($res['total'], $eachPageNum);
            $show      = $Page->show();
        }
        if(empty($list)){
            $is_search = false;
            $list = D('Pic')->getPicIndex();
        }
        $this->assign('keyword',$keyword);
        $this->assign('is_search',$is_search);
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }

    /**
     * 版权声明
     * Function shengming
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function shengming($id = 0){
        header('Cache-Control: max-age=600');
        $id = $id ? $id : 626;
        if(empty($id)){
            $this->error('内容不存在!');exit;
        }
        $Article = D('Article');
        $map = array(
            'status' => 0,
            'id' => intval($id)
        );
        $data = $Article->where($map)->find();
        if(empty($data)){
            $this->error('内容不存在!');exit;
        }
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 关于我们
     * Function about
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function about($id = 0){
        header('Cache-Control: max-age=600');
        $id = $id ? $id : 627;
        if(empty($id)){
            $this->error('内容不存在!');exit;
        }
        $Article = D('Article');
        $map = array(
            'status' => 0,
            'id' => intval($id)
        );
        $data = $Article->where($map)->find();
        if(empty($data)){
            $this->error('内容不存在!');exit;
        }
        $this->assign('data',$data);
        $this->display('shengming');
    }
}