<?php
/**
 * 分类首页
 * Class ArtCategoryController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace M\Controller;

class ArtCategoryController extends BaseController {

    public function index(){
        
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
        $id = $id >0 ? $id :I('get.id',0,'intval');
        if(empty($id)){
            $this->error('分类不存在！');
        }
        $map = array(
            'id' => $id
        );
        $data = M('ArtCategory')->where($map)->find();
        if(empty($data)){
            $this->error('分类不存在！');
        }
        $this->assign('data',$data);
        $_map = array(
          'category_id' => $id,
          'status' => 0
        );
        $Article = D('Article');
        $lists = $Article->relation(true)->where($_map)->select();
        $this->assign('lists',$lists);
        $this->display();
    }


}