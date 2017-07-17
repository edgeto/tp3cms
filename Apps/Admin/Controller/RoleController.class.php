<?php
/**
 * 后台角色管理器
 * Class RoleController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class RoleController extends BaseController {

    /**
     * 角色列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $Role = D('Role');
        $count = $Role->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Role->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加角色
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Role = D('Role');
            if (!$Role->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Role->getError());exit;
            }else{
                // 验证通过 可以进行其他数据操作
                $result = $Role->add(); // 写入数据到数据库
                cache_role_list();
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('is_super',D('Role')->isSuper(ADMIN_ID));
        $this->assign('list',$this->getList());
        $this->display();
    }

    /**
     * 修改角色
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
        $Role = D('Role');
        $where = array();
        $where['id'] = intval($id);
        $res = $Role->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if($res['is_super']){
            $this->error('您没有权限执行该操作！');exit;
        }
        if(IS_POST){
            $data = $Role->create();
            if(!$data){
                $this->error($Role->getError());exit;
            }
            $res = $Role->where($where)->save($data);
            if($res){
                cache_role_list();
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('list',$this->getList());
        $this->assign('is_super',D('Role')->isSuper(ADMIN_ID));
        $this->display();
    }

    /**
     * 删掉角色
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $Role = D('Role');
            $where = array();
            $where['id'] = intval($id);
            $_res = $Role->where($where)->find();
            if($_res['is_super']){
                $result['info'] = '您没有权限执行该操作！';
            }else{
                $res = $Role->where($where)->delete();
                if($res){
                    $result['status'] = 1;
                    $result['info'] = '删除成功';
                    cache_role_list();
                }else{
                    $result['info'] = '删除失败';
                }
            }
        }
        echo json_encode($result);exit;
    }

    /**
     * 找父角色
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
        $Role = D('Role');
        $where['pid'] = intval($pid);
        $where['is_super'] = 0;//不显示超级管理员
        $parent_list = $Role->where($where)->select();
        return $parent_list;
    }

    /**
     * 分配权限
     * Function assignAccess
     * User: edgeto
     * Date: 2016/06/16
     * Time: 18:00
     * @param null $id
     */
    public function assignAccess($id = null){
        if(!$id){
            $this->error('参数有误！');exit;
        }
        $Role = D('Role');
        $where = array();
        $where['id'] = intval($id);
        $res = $Role->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        $this->assign('role',$res);
        //找资源列表
        $Resource = D('Resource');
        $reource_list = D('Resource')->select();
        $reource_list = $this->getLevel($reource_list);
        $this->assign('reource_list',$reource_list);
        //角色所拥有的权限
        $RoleAclList = $this->getRoleAclList($id);
        $this->assign('role_acl',$RoleAclList);
        $this->display();
    }

    /**
     * 权限分层
     * Function getLevel
     * User: edgeto
     * Date: 2016/06/17
     * Time: 17:00
     * @param array $reource_list
     * @return array
     */
    public function getLevel($reource_list = array(),$id_name = 'id', $parent_id_name = 'role_channel_id', $son = 'son'){
        $res = array();
        $tmpData = array();
        foreach ($reource_list as $dataValue) {
            $tmpData[$dataValue[$id_name]] = $dataValue;
        }
        foreach ($tmpData as &$tmpValue) {
            if (isset($tmpData[$tmpValue[$parent_id_name]])) {
                $tmpData[$tmpValue[$parent_id_name]][$son][] = &$tmpData[$tmpValue[$id_name]];
            } else {
                $res[] = &$tmpData[$tmpValue[$id_name]];
            }
        } 
        return $res;
        /*if($reource_list){
            $Level = array();
            $_Level = array();
            foreach($reource_list as $r){
                if($r['pid'] == 0){
                    $r['list'] = array();
                    $Level[$r['id']] = $r;
                }else{
                    $_Level[] = $r;
                }
            }
            if($Level && $_Level){
                foreach($_Level as $_L){
                    if(array_key_exists($_L['pid'],$Level)){
                        $Level[$_L['pid']]['list'][] = $_L;
                    }
                }
            }
            $res = $Level;
        }*/
        return $res;
    }

    /**
     * 角色列表
     * Function getRoleAclList
     * User: edgeto
     * Date: 2016/06/17
     * Time: 14:00
     * @param int $role_id
     * @return string
     */
    public function getRoleAclList($role_id = 0){
        $RoleAclList = array();
        if($role_id){
            $RoleAcl = D('RoleAcl');
            $where['role_id'] = intval($role_id);
            $RoleAclList = $RoleAcl->where($where)->select();
            if($RoleAclList){
                $tmp = array();
                foreach($RoleAclList as $Acl){
                    $tmp[] = $Acl['resource_id'];
                }
                $RoleAclList = $tmp;
            }
        }
        return json_encode($RoleAclList);
}

    /**
     * 分配权限
     * Function doAssignAccess
     * User: edgeto
     * Date: 2016/06/17
     * Time: 17：00
     */
    public function doAssignAccess(){
        $json['status'] = 0;//失败
        $json['info'] = '';//失败信息
        $json['url'] = '';//刷新当前页头
        $id = I('post.id',0);
        $resource_id = I('post.resource_id',0);
        if(!$id){
            $json['info'] = '参数错误！';
        }else{
            $Role = D('Role');
            $where = array();
            $where['id'] = intval($id);
            $res = $Role->where($where)->find();
            if(!$res){
                $json['info'] = '参数错误！';
            }else{
                $RoleAcl = D('RoleAcl');
                //开启事务
                $RoleAcl->startTrans();
                //删掉权限
                $RoleAcl->where("role_id={$id}")->delete();
                if($resource_id == 0){
                    $RoleAcl->commit();
                    $json['status'] = 1;//成功
                    $json['info'] = '删除权限成功！';
                }else{
                    $newResource = array();
                    foreach ($resource_id as $item) {
                        $item = explode(':', $item);
                        $newResource[] = array('role_id' => $id, 'resource_id' => $item[0]);
                    }
                    // 插入新权限
                    if (false === $RoleAcl->addAll($newResource)) {
                        $RoleAcl->rollback();
                        $json['status'] = 0;//失败
                        $json['info'] = '分配权限失败！';
                    }
                    $RoleAcl->commit();
                    $json['status'] = 1;//成功
                    $json['info'] = '分配权限成功！';
                }
            }
        }
        $this->ajaxReturn($json);
    }

    /**
     * 列表
     * Function getList
     * User: edgeto
     * Date: 2016/06/20
     * Time: 11:00
     */
    public function getList(){
        return get_role_list();
    }

}