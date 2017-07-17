<?php
/**
 * User首页
 * Class IndexController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace User\Controller;

class IndexController extends BaseController {

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
        $user_info = M('user')->where(array('id'=>$user_id))->find();
        $this->assign('user',$user_info);
    	// 热门资讯
    	$article = D('Article')->getArticle(array('status'=>0),4,'view desc');
    	$this->assign('article',$article);
    	// 热门视频
    	$video = D('Video')->getVideo(array('status'=>0),4,'view desc');
    	$this->assign('video',$video);
    	// 热门图片
    	$pic = D('Pic')->getPic(array('status'=>0),4,'view desc');
    	$this->assign('pic',$pic);
    	$this->display();
    }

    /**
     * 个人资料
     * Function info
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function info(){
        if(IS_POST){
            $User = D('User');
            $data = $User->create();
            if(!$data){
                $this->error($User->getError());exit;
            }
            $res = $User->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $user_id = is_user();
        $data = M('User')->where(array('id'=>$user_id))->find();
        if(empty($data['birthday'])){
            $data['birthday'] = NOW_TIME;
        }
        $this->assign('data',$data);
        $province = get_province_list();
        $this->assign('province',$province);
        $city = array();
        if(!empty($data['province'])){
            // 找城市列表
            $city = get_province_list($data['province']);
        }
        $this->assign('city',$city);
        $area = array();
        if(!empty($data['city'])){
            // 找城市列表
            $area = get_province_list($data['city']);
        }
        $this->assign('area',$area);
        $this->display();
    }

    /**
     * 省份列表
     * Function ajaxGetRegion
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function ajaxGetRegion($id=1){
        $data = get_province_list($id);
        $this->ajaxReturn($data);
    }

    /**
     * 用户头像
     * Function avatur
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function avatur(){
        //用户相关
        $user_conf = C("USER_INFO");
        $res = array();
        if(!empty($_FILES)){
            $res =  uploadPic($user_conf['headimgurl']);
        }
        if($res){
            $data['headimgurl'] = $res[0];
            $where['id'] = USER_ID;
            $User = M('user');
            $re = $User->where($where)->save($data);
            if($re){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '操作成功！';
            }
        }
        $this->ajaxReturn($this->_code);
    }

    public function collect(){
        $type = I('get.type','news');
        $user_id = USER_ID;
        $Collect = D('Collect');
        $map = array(
            'user_id' => $user_id,
        );
        switch ($type) {
            case 'video':
                $map['video_id'] = array('neq',0);
                break;
            case 'pic':
                $map['pic_id'] = array('neq',0);
                break;
            default:
                $map['article_id'] = array('neq',0);
                break;
        }
        $count = $Collect->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('HOME_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Collect->relation($type)->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->assign('type',$type);// 赋值分页输出
        // dump($show);
        // dump($list);exit;
        $this->display();
    }

    public function security(){
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
 
}