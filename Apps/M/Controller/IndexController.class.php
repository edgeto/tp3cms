<?php
/**
 * M站首页
 * Class IndexController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace M\Controller;

class IndexController extends BaseController {

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
     * M站首页
     * Function index
     * User: edgeto
     * Date: 2016/06/22
     * Time: 9:00
     */
    public function index(){
        //ionic 轮播 ion-slide 异步不可以repeat
        $Carousel = D('Ad')->getAds(6);
        $this->assign('Carousel',$Carousel);
        $map = array('status'=>0,'is_index'=>1,'is_wap'=>1);
        //文章内容
        $article = D('Article')->relation('static')->where($map)->order('sort desc')->limit(5)->select();
        $this->assign('article',$article);
        //视频
        $video = D('Video')->getVideo($map,5);
        $this->assign('video',$video);
        //图片
        $pic = D('Pic')->getPic($map,5);
        $this->assign('pic',$pic);
        $this->display();
    }

    public function lists(){
        $this->display();
    }

    /**
     * 更多或发现
     * Function found
     * User: edgeto
     * Date: 2016/06/22
     * Time: 9:00
     */
    public function found(){
        $p = I('get.p',0);
        if($p){
            $data = array();
            $map = array('status'=>0,'is_wap'=>1);
            $p = max(1,intval($p));
            $pSize = 5;
            $limit = ($p - 1) * $pSize.','.$pSize;
            //文章内容
            $article = D('Article')->relation('static')->where($map)->order('id desc,sort desc')->limit($limit)->select();
            if($article){
                foreach ($article as $key => $value) {
                    $value['type'] = 1;// 资讯
                    $data[] = $value;
                }
            }
            //视频
            $video = M('Video')->where($map)->order('id desc,sort desc')->limit($limit)->select();
            if($video){
                foreach ($video as $key => $value) {
                    $value['type'] = 2;// 视频
                    $data[] = $value;
                }
            }
            //图片
            $pic = M('Pic')->where($map)->order('id desc,sort desc')->limit($limit)->select();
            if($pic){
                foreach ($pic as $key => $value) {
                    $value['type'] = 3;// 图片
                    $data[] = $value;
                }
            }
            if($data){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '';
                $this->_code['data'] = $data;
            }
            $this->ajaxReturn($this->_code);
        }
        $this->display();
    }

    public function ask(){
        $this->display();
    }

    public function me(){
        $this->display();
    }

    public function down(){
        header('Content-type: image/jpeg');
        header("Content-Disposition: attachment; filename='http://ubmcmm.baidustatic.com/media/v1/0f000Zsn27COT6WuTanrP6.jpg'");
        exit;//结束程序
    }
}