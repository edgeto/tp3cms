<?php
/**
 * User帐号首页
 * Class SecurityController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace User\Controller;

class SecurityController extends BaseController {

    /**
     * 返回格式
     * @var array
     */
    protected $_code = array(
        'code' => 1,//失败
        'msg' => '操作失败!',
        'data' => '',
    );

    /**
     * 首页
     * Function index
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function index(){
        $user_id = USER_ID;
        // 账号安全级别
        $user_info = M('user')->where(array('id'=>$user_id))->find();
        $this->assign('user',$user_info);
        $securityRank = '低';//低
        $securityRankPercent = 30;//低
        if(!empty($user_info['email']) && !empty($user_info['phone'])){
            $securityRank = '高';//高
            $securityRankPercent = 90;
        }
        if(empty($user_info['email']) && !empty($user_info['phone'])){
            $securityRank = '中';//中
            $securityRankPercent = 60;
        }
        if(!empty($user_info['email']) && empty($user_info['phone'])){
            $securityRank = '中';//中
            $securityRankPercent = 60;
        }
        $this->assign('securityRank',$securityRank);
        $this->assign('securityRankPercent',$securityRankPercent);
        // 密码等级
        $passwrodRank = '低';
        if($user_info['password_intensity'] > 5 && $user_info['password_intensity'] < 10){
            $passwrodRank = '中';
        }
        if($user_info['password_intensity'] >= 10){
            $passwrodRank = '高';
        }
        $this->assign('passwrodRank',$passwrodRank);
        $this->display();
    }

    /**
     * 修改密码
     * Function pwd
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function pwd(){
        $user_id = USER_ID;
        if(IS_POST){
            $map = array('id'=>$user_id);
            $User = D('user');
            $oldpassword = I('post.oldpassword');
            $user_info = M('user')->where($map)->find();
            if(user_md5($oldpassword,USER_AUTH_KEY) != $user_info['password']){
                $this->error('请输入正确的原密码！');exit;
            }
            $data = $User->create();
            if(!$data){
                $this->error($User->getError());exit;
            }
            $data['password_intensity'] = user_password_intensity(I('post.password'));
            $res = $User->where($map)->save($data);
            if($res){
                $this->success('修改成功！','/security/index');exit;
            }
        }
        $this->display();
    }

    /**
     * 绑定邮箱
     * Function email
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function email(){
        if(IS_POST){
            $this->_code['msg'] = '绑定邮箱失败！';
            $post = file_get_contents("php://input");
            $post = json_decode($post,true);
            if(empty($post['email'])){
                $this->_code['msg'] = '请选填写邮箱地址！';
            }else if(empty($post['code'])){
                $this->_code['msg'] = '请输入正确的验证码！';
            }else{
                $user_id = USER_ID;
                $email = addslashes($post['email']);
                $code = addslashes($post['code']);
                $map = array(
                    'user_id' => $user_id,
                    'email' => $email,
                    'status' => 0,
                    'type' => 3,//绑定
                );
                $userVerifyCode = D('UserVerifyCode');
                $codeInfo = $userVerifyCode->where($map)->order('id desc')->limit(1)->find();
                if(empty($codeInfo) || NOW_TIME > $codeInfo['expire_time']){
                    $this->_code['msg'] = '验证码过期，请重新获取！';
                }else{
                    if($code != $codeInfo['code']){
                        $this->_code['msg'] = '请输入正确的验证码！';
                    }else{
                        $data['email'] = $email;
                        $User = D('user');
                        if(!$User->create($data)){
                            $this->_code['msg'] = $User->getError();
                        }else{
                            $res = M('user')->where(array('id'=>$user_id))->save($data);
                            if($res){
                                $userVerifyCode->where(array('id'=>$codeInfo['id']))->save(array('status'=>1));
                                $this->_code['code'] = 0;
                                $this->_code['msg'] = '绑定邮箱成功！';
                                $this->_code['data'] = U('index');
                            }
                        }
                    }
                }
            }
            $this->ajaxReturn($this->_code);    
        }
        $this->display();
    }

    /**
     * 获取出箱验证码
     * Function getCode
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function getEmailCode(){
        $this->_code['msg'] = '获取验证码失败！';
        $post = file_get_contents("php://input");
        $post = json_decode($post,true);
        if(empty($post) || empty($post['email'])){
            $this->_code['msg'] = '请选填写邮箱地址！';
        }else{
            $code = '';
            $toemail = addslashes($post['email']);
            $now = NOW_TIME;
            $map = array(
                'user_id' => USER_ID,
                'expire_time' => array('gt',$now),
                'status' => 0,
                'email' => $toemail,
                'type' => 3,//绑定
            );
            $data = array(
                'user_id' => USER_ID,
                'email' => $toemail
            );
            $userVerifyCode = D('UserVerifyCode');
            $codeInfo = $userVerifyCode->where($map)->order('id desc')->limit(1)->find();
            if(empty($codeInfo)){
                $code = generate_simple_rand_text();
                $data['code'] = $code;
                $data['type'] = 3;//绑定
                if (!$userVerifyCode->create($data)){
                    // 如果创建失败 表示验证没有通过 输出错误提示信息
                    $this->_code['msg'] = $userVerifyCode->getError();
                }else{
                    // 验证通过 可以进行其他数据操作
                    $userVerifyCode->add();
                }
            }else{
                $code = $codeInfo['code'];
            }
            if($code){
                $to = $toemail;
                $title = '衍生网邮箱验证';
                $content = '尊敬的用户您好，衍生网邮箱验证码: '.$code.'('.intval(C('user_verify_code_expire')/60).'分钟内有效!)';
                $result = sendMail($to,$title,$content);
                if($result['err'] == 0){
                    $this->_code['code'] = 0;
                    $this->_code['msg'] = '获取验证码成功！';
                }
            }
        }
        $this->ajaxReturn($this->_code);
    }
 
}