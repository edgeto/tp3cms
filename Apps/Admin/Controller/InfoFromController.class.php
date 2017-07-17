<?php
/**
 * 内容来源管理器
 * Class InfoFromController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class InfoFromController extends BaseController {

    /**
     * 内容来源列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $InfoFrom = D('InfoFrom');
        $count = $InfoFrom->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $InfoFrom->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加内容来源
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $InfoFrom = D('InfoFrom');
            if (!$InfoFrom->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($InfoFrom->getError());exit;
            }else{
                // 验证通过 可以进行其他数据操作
                $result = $InfoFrom->add(); // 写入数据到数据库
                cache_infofrom_list();
                $this->success('添加成功','index');exit;
            }
        }
        $this->display();
    }

    /**
     * 修改内容来源
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
        $InfoFrom = D('InfoFrom');
        $where['id'] = intval($id);
        $res = $InfoFrom->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $InfoFrom->create();
            if(!$data){
                $this->error($InfoFrom->getError());exit;
            }
            $res = $InfoFrom->where($where)->save($data);
            if($res){
                cache_infofrom_list();
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }


    /**
     * 删掉内容来源
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $InfoFrom = D('InfoFrom');
            $where = array();
            $where['id'] = intval($id);
            $res = $InfoFrom->where($where)->delete();
            if($res){
                cache_infofrom_list();
                $result['status'] = 1;
                $result['info'] = '删除成功';
            }else{
                $result['info'] = '删除失败';
            }
        }
        echo json_encode($result);exit;
    }

    /**
     * 列表
     * Function getList
     * User: edgeto
     * Date: 2016/06/20
     * Time: 11:00
     */
    public function getList(){
        return get_infofrom_list();
    }

}