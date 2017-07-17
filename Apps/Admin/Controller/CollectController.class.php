<?php
/**
 * 用户收藏管理器
 * Class CollectController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class CollectController extends BaseController {

    /**
     * 用户收藏列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index($id=1){
        $map = array(
            'user_id' => $id
        );
        $Collect = D('Collect');
        $count = $Collect->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Collect->relation(true)->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加用户收藏
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Collect = D('Collect');
            if (!$Collect->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Collect->getError());exit;
            }else{
                // 验证通过 可以进行其他数据操作
                $result = $Collect->add(); // 写入数据到数据库
                //更新缓存
                $this->cacheCollect();
                $this->success('添加成功','index');exit;
            }
        }
        $this->display();
    }

    /**
     * 修改用户收藏
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
        $Collect = D('Collect');
        $where['id'] = intval($id);
        $res = $Collect->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Collect->create();
            if(!$data){
                $this->error($Collect->getError());exit;
            }
            $res = $Collect->where($where)->save($data);
            if($res){
                //更新缓存
                $this->cacheCollect();
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }


    /**
     * 删掉用户收藏
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $Collect = D('Collect');
            $where = array();
            $where['id'] = intval($id);
            $res = $Collect->where($where)->delete();
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