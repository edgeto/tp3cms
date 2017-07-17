<?php
/**
 * 管理员管理器
 * Class AdminController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class AdminController extends BaseController {

    /**
     * 管理员列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $Admin = D('Admin');
        $count = $Admin->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Admin->relation(true)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加管理员
     * Function add
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Admin = D('Admin');
            if (!$Admin->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Admin->getError());exit;
            }else{
                // 验证通过 可以进行其他数据操作
                $result = $Admin->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        //角色列表
        $this->assign('role_list',$this->getRoleList());
        $this->display();
    }

    /**
     * 修改管理员
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
        $Admin = D('Admin');
        $where['id'] = intval($id);
        $res = $Admin->relation(true)->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if($res['role']['is_super']){
            $this->error('您没有权限执行该操作！');exit;
        }
        if(IS_POST){
            $data = $Admin->create();
            if(!$data){
                $this->error($Admin->getError());exit;
            }
            $res = $Admin->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('role_list',$this->getRoleList());
        $this->display();
    }

    /**
     * 修改管理员密码
     * Function edit
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function password($id = null){
        if(!$id){
            $this->error('参数有误！');exit;
        }
        $Admin = D('Admin');
        $where = array();
        $where['id'] = intval($id);
        $res = $Admin->relation(true)->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if($res['role']['is_super']){
            $this->error('您没有权限执行该操作！');exit;
        }
        if(IS_POST){
            $data = $Admin->create();
            if(!$data){
                $this->error($Admin->getError());exit;
            }
            $res = $Admin->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 删掉管理员
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $Admin = D('Admin');
            $where = array();
            $where['id'] = intval($id);
            $_res = $Admin->where($where)->find();
            if(!$_res || $_res['role']['is_super']){
                $result['info'] = '参数错误！';
            }else{
                $res = $Admin->where($where)->delete();
                if($res){
                    $result['status'] = 1;
                    $result['info'] = '删除成功';
                }else{
                    $result['info'] = '删除失败';
                }
            }
        }
        echo json_encode($result);exit;
    }

    /**
     * 拿角色列表
     * Function getRoleList
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @return array|mixed
     */
    public function getRoleList(){
        $role_list = array();
        $Role = D('Role');
        $where['status'] = 0;
        $where['is_super'] = 0;//不显示超级管理员
        $role_list = $Role->where($where)->select();
        return $role_list;
    }

    /**
     * 登陆日志
     * Function log
     * User: edgeto
     * Date: 2016/06/20
     * Time: 11:00
     */
    public function log($id = null){
        $id = $id ? $id : ADMIN_ID;
        $AdminLog = D('AdminLog');
        $where['user_id'] = $id;
        $count = $AdminLog->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $AdminLog->relation(true)->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

}