<?php
/**
 * 网站配置管理器
 * Class ConfigController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class ConfigController extends BaseController {

    /**
     * 网站配置列表
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
                'config_sign',
                'config_name',
                'config_value',
                'explain',
            );
            foreach($arr_str as $item){
                if(isset($get[$item]) && $get[$item]) $map[$item] = array('like',"%{$get[$item]}%");
            }
            $arr_int = array(
                'status',
            );
            foreach($arr_int as $item){
                if(isset($get[$item]) && $get[$item] > -1){
                    $map[$item] = $get[$item];
                }else{
                    $get[$item] = -1;
                }
            }
        }
        $this->assign('map',$get);// 搜索
        $Config = D('Config');
        $count = $Config->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Config->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加网站配置
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Config = D('Config');
            if (!$Config->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Config->getError());exit;
            }else{
                // 验证通过 可以进行其他数据操作
                $result = $Config->add(); // 写入数据到数据库
                //更新缓存
                $this->cacheConfig();
                $this->success('添加成功','index');exit;
            }
        }
        $this->display();
    }

    /**
     * 修改网站配置
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
        $Config = D('Config');
        $where['id'] = intval($id);
        $res = $Config->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Config->create();
            if(!$data){
                $this->error($Config->getError());exit;
            }
            $res = $Config->where($where)->save($data);
            if($res){
                //更新缓存
                $this->cacheConfig();
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }


    /**
     * 删掉网站配置
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'删除失败');
        if($id){
            $Config = D('Config');
            $where = array();
            $where['id'] = intval($id);
            $res = $Config->where($where)->delete();
            if($res){
                $result['status'] = 1;
                $result['info'] = '删除成功';
                //更新缓存
                $this->cacheConfig();
            }else{
                $result['info'] = '删除失败';
            }
        }
        echo json_encode($result);exit;
    }

    /**
     * 更新缓存
     * Function
     * User: edgeto
     * Date: 2016/07/06
     * Time: 14:00
     */
    public function cacheConfig(){
        $Config = D('Config');
        $map = array(
            'status' => 0
        );
        $data = $Config->where($map)->getField('config_sign, config_value');
        if($data){
            S('web_config',$data);
        }
    }

    /**
     * 更新网站缓存
     * Function cacheWeb
     * User: edgeto
     * Date: 2016/08/05
     * Time: 16：00
     */
    public function cacheWeb(){
        //更新缓存
        $dir = RUNTIME_PATH;
        $res = $this->delDirFile($dir);
        if($res){
            $this->success('更新缓存成功!','/');
        }else{
            $this->error('更新缓存失败!');
        }
    }

    /**
     * 删除指定文件夹或文件
     * Function delDir
     * User: edgeto
     * Date: 2016/08/05
     * Time: 16:00
     * @param string $DirFile
     * @return bool
     */
    private function delDirFile($DirFile = ''){
        $str = '';
        if($DirFile){
            if(is_dir($DirFile) || is_file($DirFile)){
                if(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
                    $str = "rmdir /s/q " . $DirFile;
                } else {
                    $str = "rm -Rf " . $DirFile;
                }
            }
        }
        $res = system($str,$retval);
        if($retval == 0){
            return true;
        }else{
            return false;
        }
    }

}