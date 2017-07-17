<?php
/**
 * 区域管理器
 * Class RegionController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class RegionController extends BaseController {

    /**
     * 区域列表
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function index(){
        $map = array();
        if(IS_GET){
            $get = I('get.');
            $arr_str=array(
                'name',
                'pid'
            );
            foreach($arr_str as $item){
                if(isset($get[$item]) && $get[$item]){
                    if(is_numeric($get[$item])){
                        $map[$item] = array('eq',$get[$item]);
                    }else{
                        $map[$item] = array('like',"%{$get[$item]}%");
                    }
                }
            }
        }
        $this->assign('map',$get);// 搜索
        $Region = D('Region');
        $count = $Region->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Region->order('id desc')->where($map)->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加区域
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Region = D('Region');
            if (!$Region->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Region->getError());exit;
            }else{
                // 验证通过 可以进行其他数据操作
                $result = $Region->add(); // 写入数据到数据库
                cache_region_list();
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('list',$this->getList());
        $this->display();
    }

    /**
     * 修改区域
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
        $Region = D('Region');
        $where['id'] = intval($id);
        $res = $Region->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Region->create();
            if(!$data){
                $this->error($Region->getError());exit;
            }
            $res = $Region->where($where)->save($data);
            if($res){
                cache_region_list();
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('list',$this->getList());
        $this->display();
    }


    /**
     * 删掉区域
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $Region = D('Region');
            $where = array();
            $where['id'] = intval($id);
            $res = $Region->where($where)->delete();
            if($res){
                $result['status'] = 1;
                $result['info'] = '删除成功';
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
        return get_region_list();
    }
}