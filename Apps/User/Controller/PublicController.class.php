<?php
/**
 * User公共部份
 * Class PublicController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace User\Controller;
use Think\Controller;
use User\Controller\BaseController as Base;

class PublicController extends Controller {

	/**
     * 后台控制器初始化
     * Function _initialize
     * User: edgeto
     * Date: 2016/06/15
     * Time: 11:00
     */
    protected function _initialize(){
    	$config = get_web_config();
    	$this->assign('title',C('title').'-会员登陆');
    	$NavPosition = D('NavPosition');
        // main 公共头部
        $main_nav = $NavPosition->getNavByPosition(1);
        $this->assign('main_nav',$main_nav);
        // 公共底部
        $footer_nav = $NavPosition->getNavByPosition(2);
        $this->assign('footer_nav',$footer_nav); 
        // 公共底部版权
        $copyright_nav = $NavPosition->getNavByPosition(3);
        $this->assign('copyright_nav',$copyright_nav);
    }

    /**
     * 用户登陆
     * Function login
     * User: edgeto
     * Date: 2016/06/15
     * Time: 11:00
     */
    public function login($username = null,$password = null){
    	if(IS_POST){
            if(empty($username)){
                $this->error('请输入帐号!');
            }
            if(empty($password)){
                $this->error('请输入密码!');
            }
            $from = I('post.referer_url');
            /*$verify_code = I('post.verify_code',0);
            if(empty($verify_code)){
                $this->error('请输入验证码！');
            }
            $check = check_verify($verify_code);
            if(!$check){
                $this->error('验证码错误！');
            }*/
            $User = D('User');
            $res = $User->chekcLogin($username,$password);
            if(!$res){
                $this->error($User->getError());
            }
            $url = U('/');
            if($from){
                $url = $from;
            }
            $this->success('登录成功！', $url);
        }else{
            if(is_user()){
                $this->redirect('/');
            }else{
                $this->display();
            }
        }
    }

    /**
     * 用户注册
     * Function register
     * User: edgeto
     * Date: 2016/06/15
     * Time: 11:00
     */
    public function register($username = null,$password = null){
        if(IS_POST){
            if(empty($username)){
                $this->error('请输入帐号!');
            }
            if(empty($password)){
                $this->error('请输入密码!');
            }
            $from = I('post.referer_url');
            $User = D('User');
            $res = $User->create();
            if(!$res){
                $this->error($User->getError());
            }
            $User->password_intensity = user_password_intensity(I('post.password'));
            $result = $User->add(); // 写入数据到数据库
            if($result){
                //添加成功，登陆
                $User->login($result);
                // 记录日志
                $User->updateLogin($result);
                $url = U('/');
                if($from){
                    $url = $from;
                }
                $this->success('注册成功！', $url);
            }else{
                $this->error($User->getError());
            }
        }else{
            if(is_user()){
                $this->redirect('/');
            }else{
                $this->display();
            }
        }
    }

    /**
     * 退出登陆
     * Function logout
     * User: edgeto
     * Date: 2016/06/15
     * Time: 13:00
     */
    public function logout(){
        $url = U('public/login');
        $referer_url = I('get.referer_url');
        if($referer_url){
            $url = $from;
        }
        if(is_user()){
            D('User')->logout();
            //session('[destroy]');
            $this->success('退出成功！', $url);
        } else {
            $this->redirect('login');
        }
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