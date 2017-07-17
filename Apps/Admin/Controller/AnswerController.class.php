<?php
/**
 * 后台回答管理器
 * Class AnswerController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class AnswerController extends BaseController {

    /**
     * 回答列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $Answer = D('Answer');
        $count = $Answer->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Answer->relation(true)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加回答
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Answer = D('Answer');
            if (!$Answer->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Answer->getError());exit;
            }else{
                $Answer->content = I('post.content','','addslashes');
                // 验证通过 可以进行其他数据操作
                $result = $Answer->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('answer_list',$this->getAnswerList());
        $this->display();
    }

    /**
     * 问题
     * Function getAnswerList
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function getAnswerList(){
        $Ask = D('Ask');
        $list = $Ask->select();
        return $list;
    }

    /**
     * 修改回答
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
        $Answer = D('Answer');
        $where = array();
        $where['id'] = intval($id);
        $res = $Answer->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Answer->create();
            if(!$data){
                $this->error($Answer->getError());exit;
            }
            $data['content'] = I('post.content','','addslashes');
            $where['id'] = intval($data['id']);
            $res = $Answer->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('answer_list',$this->getAnswerList());
        $this->display();
    }

    /**
     * 删掉回答
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0);
        if($id){
            $Answer = D('Answer');
            $where = array();
            $where['id'] = intval($id);
            $res = $Answer->where($where)->delete();
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