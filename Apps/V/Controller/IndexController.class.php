<?php
/**
 * Home首页
 * Class IndexController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace V\Controller;

class IndexController extends BaseController {

	/**
     * 首页
     * Function index
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function index(){
    	// 头部列表
    	$video_header = $this->getIndex();
        $this->assign('video_header',$video_header);
        // 热门推荐
        $recompend_list = $this->getRecompend();
        $this->assign('recompend_list',$recompend_list);
        // 中间导航
        $this->assign('center_nav',D('NavPosition')->getNavByPosition(7));
        // 主体列表
        $map = array(
            'status' => 0
        );
        $list = M('Video')->where($map)->order('id desc')->limit(12)->select();
        $this->assign('list',$list);// 赋值数据集
    	$this->display();
    }

    /**
     * 首页推荐
     * Function getIndex
     * User: edgeto
     * Date: 2016/06/15
     * Time: 11:00
     */
    public function getIndex(){
    	$data = array();
    	$map = array(
    		'status' => 0,
    		'is_index' => 1
    	);
    	$data = M('Video')->where($map)->limit(9)->order('sort desc')->select();
    	return $data;
    }

    /**
     * 推荐
     * Function getRecompend
     * User: edgeto
     * Date: 2016/06/15
     * Time: 11:00
     */
    public function getRecompend(){
        $data = array();
        $map = array(
            'status' => 0,
            'is_recompend' => 1
        );
        $data = M('Video')->where($map)->limit(12)->order('sort desc')->select();
        return $data;
    }

    /**
     * 更多
     * Function more
     * User: edgeto
     * Date: 2016/06/15
     * Time: 11:00
     */
    public function more(){
        $Video = D('Video');
        $count = $Video->count();// 查询满足要求的总记录数
        $this->assign('count',$count);
        $Page = new \Think\Page($count,16);// 实例化分页类 
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $order = I('get.order','new');
        $this->assign('order',$order);
        if($order == 'view'){
            $order = 'view desc';
        }else{
            $order = 'id desc';
        }
        $list = $Video->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
}