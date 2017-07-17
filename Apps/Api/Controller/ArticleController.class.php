<?php
/**
 * 文章首页
 * Class ArticleController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Api\Controller;

class ArticleController extends BaseController {

    public function index(){
        
    }

    /**
     * M站首页推荐
     * Function getWapRecommend
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $category_id
     * @param bool $return
     * @return mixed
     */
    public function getWapRecommend($return = false,$category_id = 0){
        $Article = D('Article');
        $map = array(
            'is_index' => 1,
            'is_wap' => 1,
            'status' => 0,
        );
        $category_id = $category_id ? $category_id : I('get.category_id',0,'intval');
        if($category_id){
            $map['category_id'] = $category_id;
        }
        $article_list = $Article->relation(true)->where($map)->order('sort desc')->field('id,title,abstract,category_id')->select();
        if($article_list){
            $this->_code['code'] = 0;
            $this->_code['data'] = $article_list;
        }else{
            $this->_code['msg'] = '没有记录!';
        }
        if($return){
            return $article_list;
        }
        $this->ajaxReturn($this->_code,'JSONP');exit;
    }

    /**
     * M站首页最新
     * Function getWapRecommend
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $category_id
     * @param bool $return
     * @return mixed
     */
    public function getWapLists($return = false,$category_id = 0){
        $map = array(
            'is_index' => 0,
            'is_wap' => 1,
            'status' => 0,
        );
        $category_id = $category_id ? $category_id : I('get.category_id',0,'intval');
        if($category_id){
            $map['category_id'] = $category_id;
        }
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('HOME_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('id desc')->field('id,title,abstract')->limit($Page->firstRow.','.$Page->listRows)->select();
        if($list){
            $this->_code['code'] = 0;
            $this->_code['data'] = $list;
        }else{
            $this->_code['msg'] = '没有记录!';
        }
        if($return){
            return $list;
        }
        $this->ajaxReturn($this->_code);exit;
    }

    /**
     * 文章详细
     * Function detail
     * User: edgeto
     * Date: 2016/07/07
     * Time: 11:00
     * @param int $id
     */
    public function detail($id = 0){
        if(!$id){
            $this->_code['msg'] = '参数出错!';
        }else{
            $map = array(
                'id' => intval($id),
                'status' => 0
            );
            $Article = D('Article');
            $data = $Article->relation(true)->where($map)->find();
            if($data){
                $this->_code['code'] = 0;
                $this->_code['data'] = $data;
            }else{
                $this->_code['msg'] = '没有记录!';
            }
        }
        $this->ajaxReturn($this->_code);exit;
    }

}