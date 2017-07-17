<?php
/**
 * 后台评论管理器
 * Class ArtCommentController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class ArtCommentController extends BaseController {

    /**
     * 评论列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $ArtComment = D('ArtComment');
        $count = $ArtComment->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $ArtComment->relation(true)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加评论
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $ArtComment = D('ArtComment');
            if (!$ArtComment->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($ArtComment->getError());exit;
            }else{
                $ArtComment->content = I('post.content','','addslashes');
                // 验证通过 可以进行其他数据操作
                $result = $ArtComment->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('art_comment_list',$this->getArtCommentList());
        $this->display();
    }

    /**
     * 问题
     * Function getArtCommentList
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function getArtCommentList(){
        $Article = D('Article');
        $list = $Article->select();
        return $list;
    }

    /**
     * 修改评论
     * Function edit
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function edit($id = null){
        if(!$id){
            $this->error('参数有误！');exit;
        }
        $ArtComment = D('ArtComment');
        $where = array();
        $where['id'] = intval($id);
        $res = $ArtComment->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $ArtComment->create();
            if(!$data){
                $this->error($ArtComment->getError());exit;
            }
            $data['content'] = I('post.content','','addslashes');
            $where['id'] = intval($data['id']);
            $res = $ArtComment->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('art_comment_list',$this->getArtCommentList());
        $this->display();
    }

    /**
     * 删掉评论
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0);
        if($id){
            $ArtComment = D('ArtComment');
            $where = array();
            $where['id'] = intval($id);
            $res = $ArtComment->where($where)->delete();
            if($res){
                $result['status'] = 1;
                $result['info'] = '删除成功';
            }else{
                $result['info'] = '删除失败';
            }
        }
        echo json_encode($result);exit;
    }

}