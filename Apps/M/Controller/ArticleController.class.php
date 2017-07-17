<?php
/**
 * M站文章
 * Class ArticleController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace M\Controller;

class ArticleController extends BaseController {

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
     * 文章详情页面
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
        $Article = D('Article');
        $data = $Article->relation(true)->where($map)->find();
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
        $others = $Article->relation(true)->order('rand()')->where($c_map)->limit(5)->select();
        $this->assign('others',$others);
        $this->display();
    }

    /**
     * 文章分类列表
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
            $article = D('ArtCategory')->where($map)->order('id desc,sort desc')->limit($limit)->select();
            if($article){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '';
                $this->_code['data'] = $article;
            }
            $this->ajaxReturn($this->_code);
        }else{
            $map = array('status'=>0,'is_wap'=>1);
            $lists = D('ArtCategory')->where($map)->order('id desc,sort desc')->select();
            $this->assign('lists',$lists);
            $this->display();
        }
    }

    /**
     * 文章列表
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
            $article = D('Article')->relation('static')->where($map)->order('id desc,sort desc')->limit($limit)->select();
            if($article){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '';
                $this->_code['data'] = $article;
            }
            $this->ajaxReturn($this->_code);
        }
        $this->assign('id',$id);
        $this->display();
    }

}