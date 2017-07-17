<?php
/**
 * 用户首页
 * Class UserController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Api\Controller;

class UserController extends BaseController {

    public function index(){
        
    }

    public function isLogin(){
        $this->_code['code'] = 0;
        $this->_code['data'] = 1;
        $jsoncallback = htmlspecialchars($_REQUEST ['jsoncallback']);
//        echo $jsoncallback  .'('. json_encode($this->_code).')';exit;
        $this->ajaxReturn($this->_code,'JSONP');
    }

    public function ajaxLogin($username = null, $password = null){
        $this->ajaxReturn($_GET['data'].'++++'.$_GET['data1'],'JSONP');exit;
    }
    
}