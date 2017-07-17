<?php
/**
 * 后台视频管理器
 * Class VideoController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class VideoController extends BaseController {

    /**
     * 视频列表
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
                'title',
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
            );
            foreach($arr_int as $item){
                if(isset($get[$item]) && $get[$item] > -1) $map[$item] = $get[$item];
            }
        }
        $this->assign('map',$get);// 搜索
        $Video = D('Video');
        $count = $Video->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Video->relation('category')->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加视频
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Video = D('Video');
            if (!$Video->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Video->getError());exit;
            }else{
                if(!empty($_FILES)  && empty($Video->url)){
                    $res =  R('Public/uploadVideo');
                    if(!is_string($res) && !empty($res) && count($res) == 1){
                        $Video->url = $res[0];
                    }else{
                        //$this->error($res);exit;
                    }
                }
                if(!empty($_FILES) && empty($Video->img)){
                   $res =  R('Public/uploadPic');
                    if(!is_string($res) && !empty($res) && count($res) == 1){
                        $Video->img = $res[0];
                    }
                }
                // 验证通过 可以进行其他数据操作
                $result = $Video->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('Video_category',$this->getVideoCateList());
        $this->assign('infofrom_list',get_infofrom_list());
        $this->display();
    }

    /**
     * 分类
     * Function getVideoCateList
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function getVideoCateList(){
        $ArtCategory = D('VideoCategory');
        $where['status'] = 0;
        $list = $ArtCategory->where($where)->select();
        return $list;
    }

    /**
     * 修改视频
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
        $Video = D('Video');
        $where = array();
        $where['id'] = intval($id);
        $res = $Video->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Video->create();
            if(!$data){
                $this->error($Video->getError());exit;
            }
            if(!empty($_FILES)  && empty($data['url'])){
                $res =  R('Public/uploadVideo');
                if(!is_string($res) && !empty($res) && count($res) == 1){
                    $data['url'] = $res[0];
                }else{
                    //$this->error($res);exit;
                }
            }
            if(empty($data['url'])){
                //不删
                unset($data['url']);
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
            $where['id'] = intval($data['id']);
            $res = $Video->where($where)->save($data);
            if($res){
                $this->sph_update_video($id);
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('Video_category',$this->getVideoCateList());
        $this->assign('infofrom_list',get_infofrom_list());
        $this->display();
    }

    /**
     * 删掉视频
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0);
        if($id){
            $Video = D('Video');
            $where = array();
            $where['id'] = intval($id);
            $res = $Video->where($where)->delete();
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
     * 视频图片列表
     * Function VideoImgList
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param int $id
     */
    public function listImg($id = 0){
        if(!$id){
            $this->error('参数出错!');exit;
        }
        $where['video_id'] = intval($id);
        $Static = D('Static');
        $count = $Static->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Static->relation(true)->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('');

    }

    /**
     * 添加视频图片
     * Function addVideoImg
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param int $id
     */
    public function addImg($id = null){
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
                    $url[] = array('video_id'=>$id,'url'=>$urlArr,'sort'=>$sort[$k],'add_time'=>$now,'desc'=>$desc[$k],'name'=>$name[$k]);
                }
            }else{
                $res =  R('Public/uploadPic');
                if(!is_string($res) && !empty($res)){
                    foreach($res as $k=> $r){
                        $url[] = array('video_id'=>$id,'url'=>$r,'sort'=>$sort[$k],'add_time'=>$now,'desc'=>$desc[$k],'name'=>$name[$k]);
                    }
                }else{
                    $this->error($res);exit;
                }
            }
            if($url){
                D('Static')->addAll($url);
                $this->success('上传成功!',"/Video/listimg/id/{$id}");exit;
            }
            $this->error('上传出错!');exit;
        }
        $where['id'] =  intval($id);
        $data = D('Video')->where($where)->find();
        $this->assign('data',$data);
        $this->display('addimg');
    }

    /**
     * 修改视频图片
     * Function editImg
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
        $Static = D('Static');
        $_static = $Static->relation(true)->where($map)->find();
        if(!$_static){
            $this->error('参数出错!');exit;
        }
        if(IS_POST){
            $data = $Static->create();
            if(!$data){
                $this->error($Static->getError());exit;
            }
            if(!empty($_FILES) && empty($data['url'])){
                $res =  R('Public/uploadPic');
                if(!is_string($res) && $res && count($res) == 1){
                    $data['url'] = $res[0];
                }
            }
            $where['id'] = intval($data['id']);
            $res = $Static->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$_static);
        $this->display();
    }

    /**
     * 删掉视频图片
     * Function deleteImg
     * User: edgeto
     * Date: 2016/07/06
     * Time: 17:00
     * @param int $id
     */
    public function deleteImg($id = 0){
        $result= array('status'=>0,'info'=>'');
        if($id){
            $Static = D('Static');
            $where = array();
            $where['id'] = intval($id);
            $res = $Static->where($where)->delete();
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
     * 视频评论列表
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
        $VideoComment = D('VideoComment');
        $map = array(
            'video_id'=>$id
        );
        $count = $VideoComment->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $VideoComment->relation(true)->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加视频评论
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
            $VideoComment = D('VideoComment');
            if (!$VideoComment->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($VideoComment->getError());exit;
            }else{
                $VideoComment->content = I('post.content','','addslashes');
                // 验证通过 可以进行其他数据操作
                $result = $VideoComment->add(); // 写入数据到数据库
                $this->success('添加成功',"/Video/comment/id/{$id}");exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 修改视频评论
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
        $VideoComment = D('VideoComment');
        $where = array();
        $where['id'] = intval($id);
        $res = $VideoComment->relation(true)->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $VideoComment->create();
            if(!$data){
                $this->error($VideoComment->getError());exit;
            }
            $data['content'] = I('post.content','','addslashes');
            $where['id'] = intval($data['id']);
            $res = $VideoComment->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 删掉视频评论
     * Function deleteComment
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function deleteComment($id = null){
        $result= array('status'=>0);
        if($id){
            $VideoComment = D('VideoComment');
            $where = array();
            $where['id'] = intval($id);
            $res = $VideoComment->where($where)->delete();
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
     * 视频sphinx更新
     * Function sph_update_video
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function sph_update_video($video_id = 0){
        if($video_id){
            $Mysql = M();
            $sph_update_video = $Mysql->query("select * from sph_update_video where video_id = {$video_id}");
            if(!$sph_update_video){
                $Mysql->execute("insert into sph_update_video(`video_id`) values ({$video_id})");
            }
        }
    }

    public function generate($url=''){
        if($url){
            $file_name = time().'.mp4';
            $content = file_get_contents($url);
            file_put_contents($file_name,$content);
            echo "success";
        }
        exit(1);
    }

}