<?php
/**
 * 搜索首页
 * Class IndexController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Search\Controller;

class IndexController extends BaseController {

	/**
     * 首页
     * Function index
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function index(){
        $p = I('get.p',1);
        $eachPageNum = 100;
        $keyword =I('get.keyword');
        $type =I('get.type','news');
        $query_str = urldecode($_SERVER['REQUEST_URI']);
        $list = array();
        $show = '';
        $map = array(
            'status' => 0
        );
        // 首页文章推荐 首页图片推荐 首页视频推荐
        $article_list = $pic_list = $video_list = array();
        if($keyword){
        	$sphinx = sphinx();
        	$sphinx->setFilter('status', array(0));
            $sphinx->setLimits(($p-1) * $eachPageNum, $eachPageNum, 1000);
            switch ($type) {
                case 'video':
                    $res = $sphinx->Query ($keyword, "video");
                    $ids = array_keys($res['matches']);
                    if($ids){
                        $map['id'] = array('in',$ids);
                        $list = M('video')->where($map)->select();
                    }else{
                       $video_list = D('Video')->getVideoIndex();
                    }
                    break;
                case 'pic':
                    $res = $sphinx->Query ($keyword, "pic");
                    $ids = array_keys($res['matches']);
                    if($ids){
                        $map['id'] = array('in',$ids);
                        $list = M('pic')->where($map)->select();
                    }else{
                        $pic_list = D('Pic')->getPicIndex();
                    }
                    break;
                default:
        	        $res = $sphinx->Query ($keyword, "article");
                    $ids = array_keys($res['matches']);
                    if($ids){
                        $map['id'] = array('in',$ids);
                        $list = M('article')->where($map)->select();
                    }else{
                        $article_list = D('Article')->getArticleIndex();
                    }
                    break;
            }
            //分页
            $Page      = new \Think\Page($res['total'], $eachPageNum);
            $show      = $Page->show();
        }else{
            switch ($type) {
                case 'video':
                    $video_list = D('Video')->getVideoIndex();
                    break;
                case 'pic':
                    $pic_list = D('Pic')->getPicIndex();
                    break;
                default:
                    $article_list = D('Article')->getArticleIndex();
                    break;
            }
        }
        // 热点资讯
        $hot_article = M('article')->where('status=0')->order('view desc')->limit(2)->select();
        // 热点视频
        $hot_video = M('video')->where('status=0')->order('view desc')->limit(2)->select();
        // 热点图片
        $hot_pic = M('pic')->where('status=0')->order('view desc')->limit(2)->select();
        $this->assign('hot_article',$hot_article);
        $this->assign('hot_video',$hot_video);
        $this->assign('hot_pic',$hot_pic);
        $this->assign('article_list',$article_list);
        $this->assign('video_list',$video_list);
        $this->assign('pic_list',$pic_list);
        $this->assign('keyword',$keyword);
        $this->assign('type',$type);
        $this->assign('query_str',$query_str);
        $this->assign('list',$list);
        $this->assign('page',$show);
    	$this->display();
    }

}