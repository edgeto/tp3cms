<?php
/**
 * 友情链接管理器
 * Class FriendlyController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class FriendlyController extends BaseController {

    /**
     * 友情链接列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $Friendly = D('Friendly');
        $count = $Friendly->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Friendly->relation(true)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加友情链接
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Friendly = D('Friendly');
            if (!$Friendly->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Friendly->getError());exit;
            }else{
                if(!empty($_FILES) && empty($Friendly->img)){
                   $res =  R('Public/uploadPic');
                    if(!is_string($res) && !empty($res) && count($res) == 1){
                        $Friendly->img = $res[0];
                    }
                }
                // 验证通过 可以进行其他数据操作
                $result = $Friendly->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('friendly_postition_list',$this->getFriendlyPositionList());
        $this->display();
    }

    /**
     * 修改友情链接
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
        $Friendly = D('Friendly');
        $where['id'] = intval($id);
        $res = $Friendly->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Friendly->create();
            if(!$data){
                $this->error($Friendly->getError());exit;
            }
            if(!empty($_FILES) && empty($data['img'])){
                $res =  R('Public/uploadPic');
                if(!is_string($res) && !empty($res) && count($res) == 1){
                    $data['img'] = $res[0];
                }
            }
            if(empty($data['img'])){
                //不删
                unset($data['img']);
            }
            $res = $Friendly->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('friendly_postition_list',$this->getFriendlyPositionList());
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 删掉友情链接
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $Friendly = D('Friendly');
            $where = array();
            $where['id'] = intval($id);
            $res = $Friendly->where($where)->delete();
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
     * 找友情链接位
     * Function getAdPositionList
     * User: edgeto
     * Date: 2016/061/6
     * Time: 16:00
     * @return array|mixed
     */
    public function getFriendlyPositionList(){
        $Friendly_position_list = array();
        $FriendlyPosition = D('FriendlyPosition');
        $Friendly_position_list = $FriendlyPosition->select();
        return $Friendly_position_list;
    }

}