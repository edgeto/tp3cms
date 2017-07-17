<?php
/**
 * 友情链接位管理器
 * Class FriendlyPositionController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class FriendlyPositionController extends BaseController {

    /**
     * 友情链接位列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $FriendlyPosition = D('FriendlyPosition');
        $count = $FriendlyPosition->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $FriendlyPosition->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加友情链接位
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $FriendlyPosition = D('FriendlyPosition');
            if (!$FriendlyPosition->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($FriendlyPosition->getError());exit;
            }else{
                // 验证通过 可以进行其他数据操作
                $result = $FriendlyPosition->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->display();
    }

    /**
     * 修改友情链接位
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
        $FriendlyPosition = D('FriendlyPosition');
        $where['id'] = intval($id);
        $res = $FriendlyPosition->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $FriendlyPosition->create();
            if(!$data){
                $this->error($FriendlyPosition->getError());exit;
            }
            $res = $FriendlyPosition->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }


    /**
     * 删掉友情链接位
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $FriendlyPosition = D('FriendlyPosition');
            $where = array();
            $where['id'] = intval($id);
            $res = $FriendlyPosition->where($where)->delete();
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