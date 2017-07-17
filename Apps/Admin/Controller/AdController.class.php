<?php
/**
 * 广告管理器
 * Class AdController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class AdController extends BaseController {

    /**
     * 广告列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $Ad = D('Ad');
        $count = $Ad->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Ad->relation(true)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加广告
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Ad = D('Ad');
            if (!$Ad->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Ad->getError());exit;
            }else{
                if(!empty($_FILES) && empty($Ad->img)){
                   $res =  R('Public/uploadPic');
                    if(!is_string($res) && !empty($res) && count($res) == 1){
                        $Ad->img = $res[0];
                    }
                }
                // 验证通过 可以进行其他数据操作
                $result = $Ad->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('ad_postition_list',$this->getAdPositionList());
        $this->display();
    }

    /**
     * 修改广告
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
        $Ad = D('Ad');
        $where['id'] = intval($id);
        $res = $Ad->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Ad->create();
            if(!$data){
                $this->error($Ad->getError());exit;
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
            $res = $Ad->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('ad_postition_list',$this->getAdPositionList());
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 删掉广告
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $Ad = D('Ad');
            $where = array();
            $where['id'] = intval($id);
            $res = $Ad->where($where)->delete();
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
     * 找广告位
     * Function getAdPositionList
     * User: edgeto
     * Date: 2016/061/6
     * Time: 16:00
     * @return array|mixed
     */
    public function getAdPositionList(){
        $ad_position_list = array();
        $AdPosition = D('AdPosition');
        $ad_position_list = $AdPosition->select();
        return $ad_position_list;
    }

}