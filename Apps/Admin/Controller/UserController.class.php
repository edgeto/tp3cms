<?php
/**
 * 用户管理
 * Class UserController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class UserController extends BaseController {

    /**
     * 用户列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $User = D('User');
        $count = $User->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加用户
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $User = D('User');
            if (!$User->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($User->getError());exit;
            }else{
                //用户相关
                $user_conf = C("USER_INFO");
                if(!empty($_FILES) && empty($User->headimgurl)){
                    $res =  R('Public/uploadPic',array($user_conf['headimgurl']));
                    if(!is_string($res) && !empty($res) && count($res) == 1){
                        $User->headimgurl = $res[0];
                    }
                }
                $User->password_intensity = user_password_intensity(I('post.password'));
                // 验证通过 可以进行其他数据操作
                $result = $User->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->display();
    }

    /**
     * 修改用户
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
        $User = D('User');
        $where['id'] = intval($id);
        $res = $User->relation(true)->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $User->create();
            if(!$data){
                $this->error($User->getError());exit;
            }
            //用户相关
            $user_conf = C("USER_INFO");
            if(!empty($_FILES) && empty($data['headimgurl'])){
                $res =  R('Public/uploadPic',array($user_conf['headimgurl']));
                if(!is_string($res) && !empty($res) && count($res) == 1){
                    $data['headimgurl'] = $res[0];
                }
            }
            if(empty($data['headimgurl'])){
                //不删
                unset($data['headimgurl']);
            }
            $res = $User->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 修改用户密码
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
        $User = D('User');
        $where = array();
        $where['id'] = intval($id);
        $res = $User->relation(true)->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $User->create();
            if(!$data){
                $this->error($User->getError());exit;
            }
            $data['password_intensity'] = user_password_intensity(I('post.password'));
            $res = $User->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 删掉用户
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $User = D('User');
            $where = array();
            $where['id'] = intval($id);
            $_res = $User->where($where)->find();
            if(!$_res){
                $result['info'] = '参数错误！';
            }else{
                $res = $User->where($where)->delete();
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
     * 登陆日志
     * Function log
     * User: edgeto
     * Date: 2016/06/20
     * Time: 11:00
     */
    public function log($id = null){
        if(empty($id)){
            $this->error('参数出错');exit;
        }
        $UserLog = D('UserLog');
        $where['user_id'] = $id;
        $count = $UserLog->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $UserLog->relation(true)->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
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