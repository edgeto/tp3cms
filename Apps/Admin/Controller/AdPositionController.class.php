<?php
/**
 * 广告位管理器
 * Class AdPositionController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class AdPositionController extends BaseController {

    /**
     * 广告位列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $AdPosition = D('AdPosition');
        $count = $AdPosition->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $AdPosition->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加广告位
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $AdPosition = D('AdPosition');
            if (!$AdPosition->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($AdPosition->getError());exit;
            }else{
                // 验证通过 可以进行其他数据操作
                $result = $AdPosition->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->display();
    }

    /**
     * 修改广告位
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
        $AdPosition = D('AdPosition');
        $where['id'] = intval($id);
        $res = $AdPosition->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $AdPosition->create();
            if(!$data){
                $this->error($AdPosition->getError());exit;
            }
            $res = $AdPosition->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }


    /**
     * 删掉广告位
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $AdPosition = D('AdPosition');
            $where = array();
            $where['id'] = intval($id);
            $res = $AdPosition->where($where)->delete();
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
     * 空方法
     * Function _empty
     * User: edgeto
     * Date: 2016/06/20
     * Time: 11:00
     */
    public function _empty(){
        $this->error('没有此方法!');
    }

}