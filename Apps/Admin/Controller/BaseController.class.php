<?php
/**
 * 后台底层控制器
 * Class BaseController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller {

    /**
     * 后台控制器初始化
     * Function _initialize
     * User: edgeto
     * Date: 2016/06/15
     * Time: 11:00
     */
    protected function _initialize(){
        define("ADMIN_ID",is_admin());
        define("SUPER_ID",is_super());
        if( !ADMIN_ID ){// 还没登录 跳转到登录页面
            $this->redirect('public/login');
        }
        //配置
        $this->config();
        //检查权限
        $this->filterAccess();
        //菜单
        $this->assignMenu();
    }

    /**
     * 取网站配置
     * Function config
     * User: edgeto
     * Date: 2016/06/17
     * Time: 16:00
     */
    public function config(){
        $data = get_web_config();
    }

    /**
     * 检查权限
     * Function filterAccess
     * User: edgeto
     * Date: 2016/06/17
     * Time: 16:00
     */
    protected function filterAccess(){
        if(!ADMIN_ID){
           return $this->error('您没有权限执行该操作！');
        }
        $Admin = D('Admin');
        $where['id'] = ADMIN_ID;
        $admin_user = $Admin->relation(true)->where($where)->find();
        if(!$admin_user){
            return $this->error('您没有权限执行该操作！');
        }
        //超级管理员
        if($admin_user['is_super']){
            $Resource = D('Resource');
            $role_list = $Resource->order('show_order desc')->select();
            foreach($role_list as $list){
                $config_role[] = $list['id'];
            }
            C('config_role',$config_role);
            return true;
        }
        $role_id = $admin_user['role_id'];
        //拿权限
        $RoleAcl = D('RoleAcl');
        $role_list = $RoleAcl->relation(true)->where("role_id = {$role_id}")->select();
        $Controller = strtolower (CONTROLLER_NAME) ;
        $Action = strtolower (ACTION_NAME);
        if($role_list){
            $isCan = false;
            $config_role = array();
            foreach($role_list as $list){
                $config_role[] = $list['role_acl']['id'];
                if(strtolower($list['role_acl']['controller']) == $Controller && strtolower($list['role_acl']['action']) == $Action){
                    $isCan = true;
                }
            }
            //权限列表
            if($config_role){
                C('config_role',$config_role);
            }
            if($isCan){
                return true;
            }else{
                if($Controller == 'index' && $Action == 'index'){
                    // 首页没有权限 找第一个权限
                    $this->defaultPage(SUPER_ID);
                }
            }
        }
        return $this->error('您没有权限执行该操作！');
    }

    public function defaultPage($is_super = 0){
        if(!$is_super){
            $role_id = D('admin')->getRoleIdByAdminId(ADMIN_ID);
            $page = D('RoleAcl')->getOneResourceByRoleAcl($role_id);
            if($page){
                $this->redirect($page);
            }
        }
    }

    /**
     * 菜单管理
     * Function assignMenu
     * User: edgeto
     * Date: 2016/06/17
     * Time: 17：00
     */
    public function assignMenu(){
        $role_list = C('config_role');
        //头部 pid = 0
        $headerMenu = array();
        $leftMenu = array();
        $tag = $subTag = '';
        if($role_list){
            $map['id']  = array('in',$role_list);
            $Resource = D('Resource');
            $res = $Resource->where($map)->order('show_order desc')->select();
            $action_url = strtolower(__ACTION__ );
            $action_url = str_ireplace('_','',$action_url);//不知道为什么linux下会出现admin_user这样的控制器
            // 找路由
            $current_id = 0;
            $self_res_arr = $current_id_arr = array();
            $self_map['route'] = __SELF__;
            $self_res = $Resource->where($self_map)->order('id desc')->field('id,pid')->find();
            if($self_res){
                $self_res_arr = $self_res;
            }else{
                $self_con_act_map['controller'] = CONTROLLER_NAME;
                $self_con_act_map['action'] = ACTION_NAME;
                $self_res = $Resource->where($self_con_act_map)->order('id desc')->field('id,pid')->find();
                $self_res_arr = $self_res;
            }
            if($self_res_arr){
                $current_id = $self_res['id'];
                $current_pid = $self_res['pid'];
                $current_id_arr = getResourcePid($current_id);
            }
            $sidebar = structureTree($res,'id','pid','son',$current_id_arr);
            $this->assign('sidebar',$sidebar);
            // 当前ID
            $this->assign('current_id',$current_id);
            $breadcrumbRes = array();
            if($current_id_arr){
                $breadcrumbsMap['id'] = array('in',$current_id_arr);
                // $breadcrumbsMap['show_nav'] = 1;
                $breadcrumbRes = $Resource->where($breadcrumbsMap)->field('id,nav_name,route')->select();
                if($breadcrumbRes){
                    foreach ($breadcrumbRes as $b => &$bv) {
                        $bv['current'] = 0;
                        if($bv['id'] == $current_id){
                            $bv['current'] = 1;
                        }
                    }
                }
            }
            $this->assign('breadcrumbs',$breadcrumbRes);
            // dump($sidebar);exit;
            /*dump($action_url);
            dump(__SELF__);exit;*/
            $action_id = 0;
            $action_pid = 0;
            $subTag = '';
            foreach($res as $r){
                if(strtolower($r['route']) == $action_url && $r['pid'] ){
                    $action_id = $r['id'];
                    $action_pid = $r['pid'];
                    $subTag .= $r['name'];
                }
            }
            //没有路由的
            if($action_pid == 0){
                foreach($res as $r){
                    $Controller = strtolower (CONTROLLER_NAME) ;
                    $Action = strtolower (ACTION_NAME);
                    if(strtolower($r['controller']) == $Controller && strtolower($r['action']) == $Action){
                        $action_pid = $r['pid'];
                        $subTag .= $r['name'];
                    }
                }
            }
            foreach($res as $_r){
                if($_r['show_nav'] == 1){
                    if($_r['pid'] == 0){
                        $_r['current'] = 0;
                        if($action_pid == $_r['id']){
                            $_r['current'] = 1;
                            $tag .= $_r['name'];
                        }
                        $headerMenu[] = $_r;
                    }else{
                        //子
                        if($action_pid == $_r['pid']){
                            $_r['current'] = 0;
                            if($action_id ==$_r['id'] ){
                                $_r['current'] = 1;
                            }
                            $leftMenu[] = $_r;
                        }
                    }
                }
            }
        }
        $this->assign('headerMenu',$headerMenu);
        $this->assign('leftMenu',$leftMenu);
        // $breadcrumbs = $tag.' > '.$subTag;
        // $this->assign('breadcrumbs',$breadcrumbs);
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