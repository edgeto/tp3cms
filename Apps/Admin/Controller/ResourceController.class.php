<?php
/**
 * 后台资源管理器
 * Class ResourceController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class ResourceController extends BaseController {

    /**
     * 资源列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        //搜索 Get对分页好处
        $map = array();
        if(IS_GET){
            $get = I('get.');
            if($get){
                foreach($get as $k => $p){
                    if($p){
                        $map[$k] = array('like',"%{$p}%");
                    }
                }
            }
        }
        $this->assign('map',$get);// 搜索
        $Resource = D('Resource');
        $count = $Resource->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Resource->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加资源
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Resource = D('Resource');
            if (!$Resource->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Resource->getError());exit;
            }else{
                // 验证通过 可以进行其他数据操作
                $result = $Resource->add(); // 写入数据到数据库
                cache_resource_list();
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('parent_list',$this->getParentList(0));
        $this->assign('role_channel_list',$this->getRoleChannelList());
        $this->display();
    }

    /**
     * 修改资源
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
        $Resource = D('Resource');
        $where = array();
        $where['id'] = intval($id);
        $res = $Resource->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Resource->create();
            if(!$data){
                $this->error($Resource->getError());exit;
            }
            $where['id'] = intval($data['id']);
            $res = $Resource->where($where)->save($data);
            if($res){
                cache_resource_list();
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('parent_list',$this->getParentList(0));
        $this->assign('role_channel_list',$this->getRoleChannelList());
        $this->display();
    }

    /**
     * 删掉资源
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0);
        if($id){
            $Resource = D('Resource');
            $where = array();
            $where['id'] = intval($id);
            $res = $Resource->where($where)->delete();
            if($res){
                $result['status'] = 1;
                $result['info'] = '删除成功';
                cache_resource_list();
            }else{
                $result['info'] = '删除失败';
            }
        }
        echo json_encode($result);exit;
    }

    /**
     * 找父资源
     * Function getParentList
     * User: edgeto
     * Date: 2016/061/6
     * Time: 16:00
     * @param int $pid
     * @return array|mixed
     */
    public function getParentList($pid = 0){
        $parent_list = array();
        $pid = intval($pid);
        $Resource = D('Resource');
        $parent_list = $Resource->where("show_nav = 1")->select();
        // 树状分类
        $parent_list = structureTree($parent_list);
        return $parent_list;
    }

    /**
     * 找权限频道列表
     * Function getRoleChannelList
     * User: edgeto
     * Date: 2016/061/6
     * Time: 16:00
     * @param int $pid
     * @return array|mixed
     */
    public function getRoleChannelList(){
        $RoleChannelList = array();
        $Resource = D('Resource');
        $RoleChannelList = $Resource->where("is_role_channel != 0")->select();
        return $RoleChannelList;
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