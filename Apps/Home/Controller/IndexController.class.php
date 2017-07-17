<?php
/**
 * Home首页
 * Class IndexController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Home\Controller;

class IndexController extends BaseController {

    /**
     * 首页
     * Function index
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function index(){
        // 轮播图
        $this->assign('carousel',$this->getAds());
        // 首页四张图
        $this->assign('ad_list',$this->getAds(2));
        // 首页一张横图
        $this->assign('ad',$this->getAds(3));
        // 首页主体文章
        $this->assign('article_list',$this->getArticleIndex());
        // 首页主体视频
        $this->assign('video_list',$this->getVideoIndex());
        // 首页热门视频
        $this->assign('video_hot_list',$this->getVideoHot());
        // 首页图片
        $this->assign('pic_list',$this->getPicIndex());
        // dump($this->getPicRecommend());exit;
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
     * Function getArticleIndex
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $category_id
     * @param bool $return
     * @return mixed
     */
    public function getArticleIndex($category_id = 0,$return = false){
        return D('Article')->getArticleIndex();
    }

    /**
     * 首页视频推荐
     * Function getVideoIndex
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $category_id
     * @param bool $return
     * @return mixed
     */
    public function getVideoIndex($category_id = 0,$return = false){
        return D('Video')->getVideoIndex();
    }

    /**
    * 首页热门视频
    * Function getVideoHot
    * User: edgeto
    * Date: 2016/07/05
    * Time: 17：20
    * @param int $category_id
    * @param bool $return
    * @return mixed
    */
    public function getVideoHot($category_id = 0,$return = false){
        return D('Video')->getVideoHot();
    }

    /**
     * 首页图片推荐
     * Function getPicIndex
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $category_id
     * @param bool $return
     * @return mixed
     */
    public function getPicIndex($category_id = 0,$return = false){
        return D('Pic')->getPicIndex();
    }

}