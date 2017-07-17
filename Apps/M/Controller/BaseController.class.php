<?php
/**
 * M站底层控制器
 * Class BaseController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace M\Controller;
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
        //配置
        $this->config();
        //apiurl
        $this->apiUrl();
        //weixin
        $this->weiXin();
    }

    /**
     * 取网站配置
     * Function filterAccess
     * User: edgeto
     * Date: 2016/06/17
     * Time: 16:00
     */
    public function config(){
        $data = get_web_config();
        //标题、关键词和描述
        $this->assign('title',C('title'));
        $this->assign('keywords',C('keywords'));
        $this->assign('description',C('description'));
        $this->assign('isLogin',is_user());
    }

    /**
     * api接口链接
     * Function apiUrl
     * User: edgeto
     * Date: 2016/07/12
     * Time: 14:00
     */
    public function apiUrl(){
        $this->assign('api_url',C('API_URl'));
        $this->assign('qd_url',C('UPLOAD_URL'));
    }

    /**
     * weixin相关操作
     * Function weiXin
     * User: edgeto
     * Date: 2016/07/12
     * Time: 14：00
     */
    public function weiXin(){
        if(is_weixin()){
            $wx_config = R('Api/weixin/genConfig');
            $this->assign('is_weixin',is_weixin());
            $this->assign('wx_config',$wx_config);
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
        $this->error('没有这个页面!');
    }

}