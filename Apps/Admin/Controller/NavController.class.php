<?php
/**
 * 导航管理器
 * Class NavController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class NavController extends BaseController {

    /**
     * 导航列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $Nav = D('Nav');
        $count = $Nav->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Nav->relation(true)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加导航
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Nav = D('Nav');
            if (!$Nav->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Nav->getError());exit;
            }else{
                // 验证通过 可以进行其他数据操作
                $result = $Nav->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('nav_postition_list',$this->getNavPositionList());
        $this->display();
    }

    /**
     * 修改导航
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
        $Nav = D('Nav');
        $where['id'] = intval($id);
        $res = $Nav->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Nav->create();
            if(!$data){
                $this->error($Nav->getError());exit;
            }
            $res = $Nav->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        // 返回
        $back = $_SERVER['HTTP_REFERER'];
        $this->assign('nav_postition_list',$this->getNavPositionList());
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 删掉导航
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $Nav = D('Nav');
            $where = array();
            $where['id'] = intval($id);
            $res = $Nav->where($where)->delete();
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
     * 找导航位
     * Function getAdPositionList
     * User: edgeto
     * Date: 2016/061/6
     * Time: 16:00
     * @return array|mixed
     */
    public function getNavPositionList(){
        $Nav_position_list = array();
        $NavPosition = D('NavPosition');
        $Nav_position_list = $NavPosition->select();
        return $Nav_position_list;
    }

}