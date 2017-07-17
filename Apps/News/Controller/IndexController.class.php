<?php
/**
 * News首页
 * Class IndexController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace News\Controller;

class IndexController extends BaseController {

	/**
     * 首页
     * Function index
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function index(){
        $keyword = I('get.keyword','');
        $expire = 600;
        header("Expires: " . gmdate("D, d M Y H:i:s",time()+$expire) . "GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s",time()) . "GMT");
    	// 轮播图
        $this->assign('carousel',$this->getAds(4));
        // 轮播图右边
        $this->assign('right_list',$this->getArticleRecommend());
        // 随机视频？
        // $this->assign('video',$this->getRandVideo(4));
        // 中间导航
        // 主体列表
        $map = array('status'=>0);
        $Article = D('Article');
        $this->assign('center_nav',D('NavPosition')->getNavByPosition(5));
        if($keyword){
            $this->search($keyword);
        }else{
            $count = $Article->where($map)->count();// 查询满足要求的总记录数
            $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
            $Page = new \Think\Page($count,$HOME_LIST_ROWS);// 实例化分页类 
            $show = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $Article->where($map)->order('id desc,sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
            $this->assign('list',$list);// 赋值数据集
            $this->assign('page',$show);// 赋值分页输出
        }
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex()); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList());
        $this->display();
    }

    /**
     * 广告
     * Function getAds
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $ad_position_id
     * @param bool $return
     * @return mixed
     */
    public function getAds($ad_position_id = 1,$return = false){
        return D('Ad')->getAds($ad_position_id,$return);
    }

    /**
     * 首页文章推荐
     * Function getArticleRecommend
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $category_id
     * @param bool $return
     * @return mixed
     */
    public function getArticleRecommend($category_id = 0,$return = false){
        return D('Article')->getArticleRecommend($category_id,$return);
    }

    /**
     * 随机视频
     * Function getRandVideo
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $limit
     * @return array
     */
    public function getRandVideo($limit = 1){
        $data = array();
        $data = D('Video')->order('rand()')->limit($limit)->select();
        return $data;
    }

    public function search($keyword){
        $p = I('get.p',1);
        $eachPageNum = 10;
        $keyword =I('get.keyword');
        $type =I('get.type','news');
        $query_str = urldecode($_SERVER['REQUEST_URI']);
        $list = array();
        $show = '';
        $map = array(
            'status' => 0
        );
        if($keyword){
            $sphinx = sphinx();
            $sphinx->setFilter('status', array(0));
            $sphinx->setLimits(($p-1) * $eachPageNum, $eachPageNum, 1000);
            $res = $sphinx->Query ($keyword, "article");
            $ids = array_keys($res['matches']);
            if($ids){
                $map['id'] = array('in',$ids);
                $list = M('article')->where($map)->select();
            }
            //分页
            $Page      = new \Think\Page($res['total'], $eachPageNum);
            $show      = $Page->show();
        }
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('keyword',$keyword);
    }

}