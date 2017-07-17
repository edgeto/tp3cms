<?php
/**
 * 后台登陆控制器
 * Class IndexController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;
use Think\Controller;

class PublicController extends Controller {

    /**
     * 后台登陆
     * Function login
     * User: edgeto
     * Date: 2016/06/13
     * Time: 13:00
     * @param null $username
     * @param null $password
     */
    public function login($username = null, $password = null){
        if(IS_POST){
            if(empty($username) || empty($password)){
                $this->error('请输入管理员帐号或密码');
            }
            $verify_code = I('post.verify_code',0);
            if(empty($verify_code)){
                $this->error('请输入验证码！');
            }
            $check = check_verify($verify_code);
            if(!$check){
                $this->error('验证码错误！');
            }
            $Admin = D('Admin');
            $res = $Admin->chekcLogin($username,$password);
            if(!$res){
                $this->error($Admin->getError());
            }
            $this->success('登录成功！', U('/'));
        }else{
            if(is_admin()){
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
        if(is_admin()){
            D('Admin')->logout();
            //session('[destroy]');
            $this->success('退出成功！', U('public/login'));
        } else {
            $this->redirect('login');
        }
    }

    /**
     * 验证码
     * Function verifyCode
     * User: edgeto
     * Date: 2016/06/16
     * Time: 10:00
     */
    public function verifyCode(){
        $config =    array(
            'imageW' => 100,            //验证码宽度
            'imageH' => 26,             //验证码高度
            'fontSize' => 14,           //验证码字体大小（像素） 默认为25
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
            'codeSet' => '0123456789' //验证码字符集合
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();
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

    /**
     * 上传图片
     * Function uploadPic
     * User: edgeto
     * Date: 2016/06/22
     * Time: 9:00
     * @param $rootPath 上传根目录
     * @return array|bool
     */
    public function uploadPic($rootPath = ''){
        $pic_upload_config = C('PICTURE_UPLOAD');
        if($rootPath){
            $pic_upload_config['rootPath'] =  $rootPath;
        }
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     $pic_upload_config['maxSize'] ;// 设置附件上传大小
        $upload->exts      =     $pic_upload_config['exts'];// 设置附件上传类型
        $upload->rootPath  =    $pic_upload_config['rootPath']; // 设置附件上传根目录
        $upload->savePath  =     $pic_upload_config['savePath']; // 设置附件上传（子）目录
        $upload->saveName  =     $pic_upload_config['saveName']; // 上传文件的保存规则，支持数组和字符串方式定义
        $upload->autoSub  =     $pic_upload_config['autoSub']; // 自动使用子目录保存上传文件 默认为true
        $upload->subName  =     $pic_upload_config['subName']; // 子目录创建方式，采用数组或者字符串方式定义
        $upload->hash  =     $pic_upload_config['hash']; // 是否生成文件的hash编码 默认为true
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            return $upload->getError();
//            return false;
        }else{// 上传成功
            $url = array();
            $data = array();
            foreach($info as $i){
                $_url = $upload->rootPath .$i['savepath'].$i['savename'];
                $url[]= $_url;
                $data[] = array('url'=>$_url);
            }
            //添加到静态资源表 不加
           /* if($data){
                $Static = D("Static");
                $Static->addAll($data);
            }*/
            return $url ;
        }
    }
    /**
     * 上传视频
     * Function uploadVideo
     * User: edgeto
     * Date: 2016/06/22
     * Time: 9:00
     * @param $rootPath 上传根目录
     * @return array|bool
     */
    public function uploadVideo($rootPath = ''){
        $video_upload_config = C('VIDEO_UPLOAD');
        if($rootPath){
            $video_upload_config['rootPath'] =  $rootPath;
        }
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = $video_upload_config['maxSize'] ;// 设置附件上传大小
        $upload->exts = $video_upload_config['exts'];// 设置附件上传类型
        $upload->rootPath = $video_upload_config['rootPath']; // 设置附件上传根目录
        $upload->savePath = $video_upload_config['savePath']; // 设置附件上传（子）目录
        $upload->saveName = $video_upload_config['saveName']; // 上传文件的保存规则，支持数组和字符串方式定义
        $upload->autoSub = $video_upload_config['autoSub']; // 自动使用子目录保存上传文件 默认为true
        $upload->subName = $video_upload_config['subName']; // 子目录创建方式，采用数组或者字符串方式定义
        $upload->hash = $video_upload_config['hash']; // 是否生成文件的hash编码 默认为true
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            return $upload->getError();
//            return false;
        }else{// 上传成功
            $url = array();
            $data = array();
            foreach($info as $i){
                $_url = $upload->rootPath .$i['savepath'].$i['savename'];
                $url[]= $_url;
                $data[] = array('url'=>$_url);
            }
            //添加到静态资源表 不加
           /* if($data){
                $Static = D("Static");
                $Static->addAll($data);
            }*/
            return $url ;
        }
    }

    /**
     * 后面nginx 错误页面 404,50X
     * @param  integer $type [description]
     * @return [type]        [description]
     */
    public function errorError($type = 404){
        $this->display($type);
    }

}