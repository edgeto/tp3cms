<?php
/**
 * 用户底层控制器
 * Class BaseController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace User\Controller;
use Think\Controller;

class BaseController extends Controller {

    /**
     * 返回格式
     * @var array
     */
    protected $_code = array(
        'code' => 1,//失败
        'msg' => '',
        'data' => '',
    );


    /**
     * 后台控制器初始化
     * Function _initialize
     * User: edgeto
     * Date: 2016/06/15
     * Time: 11:00
     */
    protected function _initialize(){
        /*if(is_mobile()){
            header('Location: '.C('DEFAULT_URL.M').__SELF__);
            die();
        }*/
        define("USER_ID",is_user());
        if(!USER_ID){// 还没登录 跳转到登录页面
            $this->redirect('public/login');
        }
        //配置
        $this->config();
        //导航
        $this->nav();
        //友情链接
        $this->friendly();
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
        //网站禁用或维护
        if(isset($data['status'])){
            if($data['status'] == -1){
                $this->error('网站禁用!');exit;
            }
            if($data['status'] == 1){
                $this->error('网站维护中!');exit;
            }
        }
        //标题、关键词和描述
        $this->assign('title',C('title'));
        $this->assign('keywords',C('keywords'));
        $this->assign('description',C('description'));
        $this->assign('isLogin',is_user());
    }

    /**
     * 导航
     * Function nav
     * User: edgeto
     * Date: 2016/06/17
     * Time: 16:00
     */
    public function nav(){
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
        // 会员中心导航
        $nav = $NavPosition->getNavByPosition(9);
        // 面包屑
        $breadcrumbs[]  = array(
            'url' => '/',
            'name' => '首页'
        );
        //文章导航 
        $pattern = '/\d+/';
        preg_match($pattern,__SELF__,$id);
        if($id){
            $id = $id[0];
            $category_name = D('Pic')->getCategoryByPicId($id);
            if($category_name){
                foreach ($nav as $key => &$value) {
                    if($value['name'] == $category_name){
                        $value['active'] = 1;
                        $breadcrumbs[] = array(
                            'url' => $value['url'],
                            'name' => $category_name
                        );
                    }
                }
            }
        }
        $this->assign('breadcrumbs',$breadcrumbs);
        $this->assign('nav',$nav);
        $this->assign('collectNav',$collectNav);
    }

    /**
     * 友情链接
     * Function friendly
     * User: edgeto
     * Date: 2016/06/17
     * Time: 16:00
     */
    public function friendly()
    {
        $FriendlyPosition = D('FriendlyPosition');
        $friendly = $FriendlyPosition->getFriendlyByPosition(1);
        $this->assign('friendly',$friendly);
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