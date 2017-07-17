<?php
/**
 * 后台问答管理器
 * Class AskController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class AskController extends BaseController {

    /**
     * 问答列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $Ask = D('Ask');
        $count = $Ask->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Ask->relation(true)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加问答
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Ask = D('Ask');
            if (!$Ask->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Ask->getError());exit;
            }else{
                if(!empty($_FILES) && empty($Ask->img)){
                    $res =  R('Public/uploadPic');
                    if(!is_string($res) && !empty($res) && count($res) == 1){
                        $Ask->img = $res[0];
                    }
                }
                $Ask->content = I('post.content','','addslashes');
                // 验证通过 可以进行其他数据操作
                $result = $Ask->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->display();
        $this->assign('infofrom_list',get_infofrom_list());
    }

    /**
     * 修改问答
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
        $Ask = D('Ask');
        $where = array();
        $where['id'] = intval($id);
        $res = $Ask->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Ask->create();
            if(!$data){
                $this->error($Ask->getError());exit;
            }
            if(!empty($_FILES)&& empty($data['img'])){
                $res =  R('Public/uploadPic');
                if(!is_string($res) && !empty($res) && count($res) == 1){
                    $data['img'] = $res[0];
                }
            }
            if(empty($data['img'])){
                //不删
                unset($data['img']);
            }
            $data['content'] = I('post.content','','addslashes');
            $where['id'] = intval($data['id']);
            $res = $Ask->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('infofrom_list',get_infofrom_list());
        $this->display();
    }

    /**
     * 删掉问答
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0);
        if($id){
            $Ask = D('Ask');
            $where = array();
            $where['id'] = intval($id);
            $res = $Ask->where($where)->delete();
            if($res){
                $result['status'] = 1;
                $result['info'] = '删除成功';
            }else{
                $result['info'] = '删除失败';
            }
        }
        echo json_encode($result);exit;
    }

    /**
     * 回答列表
     * Function answer
     * User: edgeto
     * Date: 2016/07/07
     * Time: 12：00
     * @param null $id
     */
    public function answer($id = null){
        if(!$id){
            $this->error('参数有误！');exit;
        }
        $Answer = D('Answer');
        $map = array(
            'ask_id'=>$id
        );
        $count = $Answer->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Answer->relation(true)->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加回答
     * Function addAnswer
     * User: edgeto
     * Date: 2016/07/07
     * Time: 12:00
     * @param null $id
     */
    public function addAnswer($id = null){
        if(!$id){
            $this->error('参数有误！');exit;
        }
        $Ask = D('Ask');
        $where = array();
        $where['id'] = intval($id);
        $res = $Ask->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $Answer = D('Answer');
            if (!$Answer->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Answer->getError());exit;
            }else{
                $Answer->content = I('post.content','','addslashes');
                // 验证通过 可以进行其他数据操作
                $result = $Answer->add(); // 写入数据到数据库
                $this->success('添加成功','/ask/index');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }

}