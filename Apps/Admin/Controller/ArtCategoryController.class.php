<?php
/**
 * 文章分类管理器
 * Class ArtCategoryController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class ArtCategoryController extends BaseController {

    /**
     * 文章分类列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        //搜索 Get对分页好处
        $map = array();
        if(IS_GET){
            $get = I('get.');
            $arr_str=array(
                'name',
                'desc',
            );
            foreach($arr_str as $item){
                if(isset($get[$item]) && $get[$item]) $map[$item] =  array('like',"%{$get[$item]}%");
            }
            $arr_int = array(
                'is_index',
                'status',
                'is_pc',
                'is_wap',
            );
            foreach($arr_int as $item){
                if(isset($get[$item]) && $get[$item] > -1) $map[$item] = $get[$item];
            }
        }
        $this->assign('map',$get);// 搜索
        $ArtCategory = D('ArtCategory');
        $count = $ArtCategory->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $ArtCategory->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加文章分类
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $ArtCategory = D('ArtCategory');
            if (!$ArtCategory->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($ArtCategory->getError());exit;
            }else{
                if(!empty($_FILES) && empty($ArtCategory->img)){
                   $res =  R('Public/uploadPic');
                    if(!is_string($res) && !empty($res) && count($res) == 1){
                        $ArtCategory->img = $res[0];
                    }
                }
                // 验证通过 可以进行其他数据操作
                $result = $ArtCategory->add(); // 写入数据到数据库
                cache_art_category_list();
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('list',$this->getList());
        $this->display();
    }

    /**
     * 修改文章分类
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
        $ArtCategory = D('ArtCategory');
        $where['id'] = intval($id);
        $res = $ArtCategory->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $ArtCategory->create();
            if(!$data){
                $this->error($ArtCategory->getError());exit;
            }
            if(!empty($_FILES) && empty($data['img'])){
                $res =  R('Public/uploadPic');
                if(!is_string($res) && !empty($res) && count($res) == 1){
                    $data['img'] = $res[0];
                }
            }
            if(empty($data['img'])){
                //不删
                unset($data['img']);
            }
            $res = $ArtCategory->where($where)->save($data);
            if($res){
                cache_art_category_list();
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('list',$this->getList());
        $this->display();
    }


    /**
     * 删掉文章分类
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $ArtCategory = D('ArtCategory');
            $where = array();
            $where['id'] = intval($id);
            $res = $ArtCategory->where($where)->delete();
            if($res){
                $result['status'] = 1;
                $result['info'] = '删除成功';
                cache_art_category_list();
            }else{
                $result['info'] = '删除失败';
            }
        }
        echo json_encode($result);exit;
    }

    /**
     * 列表
     * Function getList
     * User: edgeto
     * Date: 2016/06/20
     * Time: 11:00
     */
    public function getList(){
        return get_art_category_list();
    }

}