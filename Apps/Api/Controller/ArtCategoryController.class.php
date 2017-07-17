<?php
/**
 * 分类首页
 * Class ArtCategoryController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Api\Controller;

class ArtCategoryController extends BaseController {

    public function index(){
        
    }

    /**
     * M站首页推荐
     * Function getWapRecommend
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param bool $return
     * @param int $category_id
     * @param int $limit
     * @return mixed
     */
    public function getAllWapRecommend($return = false,$category_id = 0,$limit = 10){
        $ArtCategory = D('ArtCategory');
        $map = array(
            'is_index' => 1,
            'is_wap' => 1,
            'status' => 0,
        );
        $category_id = $category_id ? $category_id : I('get.category_id',0,'intval');
        if($category_id){
            $map['id'] = $category_id;
        }
        $art_category_list = $ArtCategory->where($map)->order('sort desc')->field('id,name,desc')->select();
        if($art_category_list){
            $Article = D('Article');
            $tmp = array();
            foreach($art_category_list as $k => &$category){
                $map = array(
                    'is_index' => 1,
                    'is_wap' => 1,
                    'status' => 0,
                    'category_id' => $category['id'],
                );
                $article_list = $Article->relation(true)->where($map)->field('id,title')->limit($limit)->select();
                if($article_list)
                {
                    $category['article'] = $article_list;
                }else{
                    unset($art_category_list[$k]);
                }
            }
            $this->_code['code'] = 0;
            $this->_code['data'] = $art_category_list;
        }else{
            $this->_code['msg'] = '没有记录!';
        }
        if($return){
            return $art_category_list;
        }
        $this->ajaxReturn($this->_code,'JSONP');exit;
    }

}