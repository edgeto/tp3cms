<?php
/**
 * User收藏
 * Class CollectController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace User\Controller;

class CollectController extends BaseController {

    /**
     * 返回格式
     * @var array
     */
    protected $_code = array(
        'code' => 1,//失败
        'msg' => '操作失败!',
        'data' => '',
    );

    public function index(){
        $user_id = USER_ID;
        $Collect = M('Collect');
        $map = array(
            'user_id' => $user_id,
            'article_id' => array('neq',0),
        );
        // C('ADMIN_LIST_ROWS')
        $count = $Collect->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,1);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Collect->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    public function video(){
        $user_id = USER_ID;
        $Collect = M('Collect');
        $map = array(
            'user_id' => $user_id,
            'video_id' => array('neq',0),
        );
        // C('ADMIN_LIST_ROWS')
        $count = $Collect->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,1);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Collect->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    public function pic(){
        $user_id = USER_ID;
        $Collect = M('Collect');
        $map = array(
            'user_id' => $user_id,
            'pic_id' => array('neq',0),
        );
        // C('ADMIN_LIST_ROWS')
        $count = $Collect->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,1);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Collect->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

}