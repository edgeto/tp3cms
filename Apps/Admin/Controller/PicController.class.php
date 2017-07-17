<?php
/**
 * 相册管理器
 * Class PicController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class PicController extends BaseController {

    /**
     * 相册列表
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
            );
            foreach($arr_str as $item){
                if(isset($get[$item]) && $get[$item]) $map[$item] = array('like',"%{$get[$item]}%");
            }
            $arr_int = array(
                'is_index',
                'status',
                'is_pc',
                'is_wap',
                'is_recompend',
                'category_id',
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
        $Pic = D('Pic');
        $count = $Pic->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Pic->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $category_list = array_merge(array(0=>'未分类'),get_pic_category_list());
        $this->assign('category_list',$category_list);// 分类
        $this->display();
    }

    /**
     * 添加相册
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Pic = D('Pic');
            if (!$Pic->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Pic->getError());exit;
            }else{
                if($_FILES && empty($Pic->img)){
                   $res =  R('Public/uploadPic');
                    if(!is_string($res) && !empty($res) && count($res) == 1){
                        $Pic->img = $res[0];
                    }
                }
                // 验证通过 可以进行其他数据操作
                $result = $Pic->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('list',$this->getList());
        $this->assign('infofrom_list',get_infofrom_list());
        $this->display();
    }

    /**
     * 修改相册
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
        $Pic = D('Pic');
        $where['id'] = intval($id);
        $res = $Pic->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Pic->create();
            if(!$data){
                $this->error($Pic->getError());exit;
            }
            if($_FILES && empty($data['img'])){
                $res =  R('Public/uploadPic');
                if(!is_string($res) && !empty($res) && count($res) == 1){
                    $data['img'] = $res[0];
                }
            }
            if(empty($data['img'])){
                //不删
                unset($data['img']);
            }
            $res = $Pic->where($where)->save($data);
            if($res){
                $this->sph_update_pic($id);
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('list',$this->getList());
        $this->assign('infofrom_list',get_infofrom_list());
        $this->display();
    }

    /**
     * 删掉相册
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $Pic = D('Pic');
            $where = array();
            $where['id'] = intval($id);
            $res = $Pic->where($where)->delete();
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
     * 找上级
     * Function getParentList
     * User: edgeto
     * Date: 2016/061/6
     * Time: 16:00
     * @param int $pid
     * @return array|mixed
     */
    public function getParentList($pid = 0){
        $parent_list = array();
        $pid = intval($pid);
        $Pic = D('Pic');
        $where['pid'] = intval($pid);
        $parent_list = $Pic->where($where)->select();
        return $parent_list;
    }

    /**
     * 给指定相册添加图片
     * Function
     * User: edgeto
     * Date: 2016/07/06
     * Time: 17:00
     * @param int $id
     */
    public function addImg($id = 0){
        if(!$id){
            $this->error('参数出错!');exit;
        }
        if(IS_POST){
            if(empty($_FILES)){
                $this->error('请至少选择一张图片!');exit;
            }
            $now = NOW_TIME;
            $desc = I('post.desc');
            $sort = I('post.sort');
            $name = I('post.name');
            $urlArr = I('post.url');
            $url = array();
            if(!empty($urlArr[0])){
                foreach($urlArr as $k => $urlArr){
                    $url[] = array('pic_id'=>$id,'url'=>$urlArr,'sort'=>$sort[$k],'add_time'=>$now,'desc'=>$desc[$k],'name'=>$name[$k]);
                }
            }else{
                $res =  R('Public/uploadPic');
                if(!is_string($res) && !empty($res)){
                    foreach($res as $k=> $r){
                        $url[] = array('pic_id'=>$id,'url'=>$r,'sort'=>$sort[$k],'add_time'=>$now,'desc'=>$desc[$k],'name'=>$name[$k]);
                    }
                }else{
                    $this->error($res);exit;
                }
            }
            if($url){
                D('PicImg')->addAll($url);
                $this->success('上传成功!',"/pic/listimg/id/{$id}");exit;
            }
            $this->error('上传出错!');exit;
        }
        $where['id'] =  intval($id);
        $data = D('Pic')->where($where)->find();
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 相册图片列表
     * Function
     * User: edgeto
     * Date: 2016/07/06
     * Time: 17:00
     * @param int $id
     */
    public function listImg($id = 0){
        if(!$id){
            $this->error('参数出错!');exit;
        }
        $where['pic_id'] = intval($id);
        $PicImg = D('PicImg');
        $count = $PicImg->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,18);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $PicImg->relation(true)->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 修改指定相册下指定的一张图片
     * Function
     * User: edgeto
     * Date: 2016/07/06
     * Time: 17:00
     * @param int $id
     */
    public function editImg($id = 0){
        if(!$id){
            $this->error('参数出错!');exit;
        }
        $map = array(
            'id' => $id
        );
        $PicImg = D('PicImg');
        $pic_img = $PicImg->relation(true)->where($map)->find();
        if(!$pic_img){
            $this->error('参数出错!');exit;
        }
        if(IS_POST){
            $data = $PicImg->create();
            if(!$data){
                $this->error($PicImg->getError());exit;
            }
            if(!empty($_FILES) && empty($data['url'])){
                $res =  R('Public/uploadPic');
                if(!is_string($res) && !empty($res) && count($res) == 1){
                    $data['url'] = $res[0];
                }
            }
            $where['id'] = intval($data['id']);
            $res = $PicImg->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$pic_img);
        $this->display();
    }

    /**
     * 删掉相册图片
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function deleteimg($id = null){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $PicImg = D('PicImg');
            $where = array();
            $where['id'] = intval($id);
            $res = $PicImg->where($where)->delete();
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
        return get_pic_category_list();
    }

    /**
     * 相册评论列表
     * Function comment
     * User: edgeto
     * Date: 2016/07/07
     * Time: 12:00
     * @param null $id
     */
    public function comment($id = null){
        if(!$id){
            $this->error('参数有误！');exit;
        }
        $PicComment = D('PicComment');
        $map = array(
            'pic_id'=>$id
        );
        $count = $PicComment->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $PicComment->relation(true)->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加相册评论
     * Function addComment
     * User: edgeto
     * Date: 2016/06/07
     * Time: 12:00
     * @param null $id
     */
    public function addComment($id = null){
        if(!$id){
            $this->error('参数有误！');exit;
        }
        $Video = D('Video');
        $where = array();
        $where['id'] = intval($id);
        $res = $Video->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $PicComment = D('PicComment');
            if (!$PicComment->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($PicComment->getError());exit;
            }else{
                $PicComment->content = I('post.content','','addslashes');
                // 验证通过 可以进行其他数据操作
                $result = $PicComment->add(); // 写入数据到数据库
                $this->success('添加成功',"/Pic/comment/id/{$id}");exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 修改相册评论
     * Function editComment
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function editComment($id = null){
        if(!$id){
            $this->error('参数有误！');exit;
        }
        $PicComment = D('PicComment');
        $where = array();
        $where['id'] = intval($id);
        $res = $PicComment->relation(true)->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $PicComment->create();
            if(!$data){
                $this->error($PicComment->getError());exit;
            }
            $data['content'] = I('post.content','','addslashes');
            $where['id'] = intval($data['id']);
            $res = $PicComment->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 删掉相册评论
     * Function deleteComment
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function deleteComment($id = null){
        $result= array('status'=>0);
        if($id){
            $PicComment = D('PicComment');
            $where = array();
            $where['id'] = intval($id);
            $res = $PicComment->where($where)->delete();
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
     * 图片sphinx更新
     * Function sph_update_pic
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function sph_update_pic($pic_id = 0){
        if($pic_id){
            $Mysql = M();
            $sph_update_pic = $Mysql->query("select * from sph_update_pic where pic_id = {$pic_id}");
            if(!$sph_update_pic){
                $Mysql->execute("insert into sph_update_pic(`pic_id`) values ({$pic_id})");
            }
        }
    }
}