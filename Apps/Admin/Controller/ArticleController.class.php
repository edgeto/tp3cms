<?php
/**
 * 后台文章管理器
 * Class ArticleController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class ArticleController extends BaseController {

    /**
     * 文章列表
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
                'category_id',
            );
            foreach($arr_int as $item){
                if(isset($get[$item]) && $get[$item] > -1) $map[$item] = $get[$item];
            }
        }
        $this->assign('map',$get);// 搜索
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->relation('category')->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $category_list = array_merge(array(0=>'未分类'),get_art_category_list());
        $this->assign('category_list',$category_list);// 分类
        $this->display();
    }

    /**
     * 添加文章
     * Function index
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function add(){
        if(IS_POST){
            $Article = D('Article');
            if (!$Article->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($Article->getError());exit;
            }else{
                if($_FILES && empty($Article->img)){
                   $res =  R('Public/uploadPic');
                    if(!is_string($res) && !empty($res) && count($res) == 1){
                        $Article->img = $res[0];
                    }
                }
                // 验证通过 可以进行其他数据操作
                $result = $Article->add(); // 写入数据到数据库
                $this->success('添加成功','index');exit;
            }
        }
        $this->assign('article_category',$this->getArticleCateList());
        $this->assign('infofrom_list',get_infofrom_list());
        $this->display();
    }

    /**
     * 分类
     * Function getArticleCateList
     * User: edgeto
     * Date: 2016/06/16
     * Time: 14:00
     */
    public function getArticleCateList(){
        $ArtCategory = D('ArtCategory');
        $where['status'] = 0;
        $list = $ArtCategory->where($where)->select();
        return $list;
    }

    /**
     * 修改文章
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
        $Article = D('Article');
        $where = array();
        $where['id'] = intval($id);
        $res = $Article->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $Article->create();
            if(!$data){
                $this->error($Article->getError());exit;
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
            $where['id'] = intval($data['id']);
            $res = $Article->where($where)->save($data);
            if($res){
                $this->sph_update_article($id);
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->assign('article_category',$this->getArticleCateList());
        $this->assign('infofrom_list',get_infofrom_list());
        $this->display();
    }

    /**
     * 删掉文章
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function delete($id = null){
        $result= array('status'=>0);
        if($id){
            $Article = D('Article');
            $where = array();
            $where['id'] = intval($id);
            $res = $Article->where($where)->delete();
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
     * 文章图片列表
     * Function articleImgList
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param int $id
     */
    public function listImg($id = 0){
        if(!$id){
            $this->error('参数出错!');exit;
        }
        $where['article_id'] = intval($id);
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
     * 添加文章图片
     * Function addArticleImg
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
                    $url[] = array('article_id'=>$id,'url'=>$urlArr,'sort'=>$sort[$k],'add_time'=>$now,'desc'=>$desc[$k],'name'=>$name[$k]);
                }
            }else{
                $res =  R('Public/uploadPic');
                if(!is_string($res) && !empty($res)){
                    foreach($res as $k=> $r){
                        $url[] = array('article_id'=>$id,'url'=>$r,'sort'=>$sort[$k],'add_time'=>$now,'desc'=>$desc[$k],'name'=>$name[$k]);
                    }
                }else{
                    $this->error($res);exit;
                }
            }
            if($url){
                D('Static')->addAll($url);
                $this->success('上传成功!',"/article/listimg/id/{$id}");exit;
            }
            $this->error('上传出错!');exit;
        }
        $where['id'] =  intval($id);
        $data = D('Article')->where($where)->find();
        $this->assign('data',$data);
        $this->display('addimg');
    }

    /**
     * 修改文章图片
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
     * 删掉图片
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
     * 评论列表
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
        $ArtComment = D('ArtComment');
        $map = array(
            'article_id'=>$id
        );
        $count = $ArtComment->where($map)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,C('ADMIN_LIST_ROWS'));// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $ArtComment->relation(true)->where($map)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('count',$count);// 总数
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 添加评论
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
        $Article = D('Article');
        $where = array();
        $where['id'] = intval($id);
        $res = $Article->relation(true)->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $ArtComment = D('ArtComment');
            if (!$ArtComment->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                $this->error($ArtComment->getError());exit;
            }else{
                $ArtComment->content = I('post.content','','addslashes');
                // 验证通过 可以进行其他数据操作
                $result = $ArtComment->add(); // 写入数据到数据库
                $this->success('添加成功',"/article/comment/id/{$id}");exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 修改文章评论
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
        $ArtComment = D('ArtComment');
        $where = array();
        $where['id'] = intval($id);
        $res = $ArtComment->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        if(IS_POST){
            $data = $ArtComment->create();
            if(!$data){
                $this->error($ArtComment->getError());exit;
            }
            $data['content'] = I('post.content','','addslashes');
            $where['id'] = intval($data['id']);
            $res = $ArtComment->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
    }

    /**
     * 删掉评论
     * Function delete
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function deleteComment($id = null){
        $result= array('status'=>0);
        if($id){
            $ArtComment = D('ArtComment');
            $where = array();
            $where['id'] = intval($id);
            $res = $ArtComment->where($where)->delete();
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
     * 文章sphinx更新
     * Function sph_update_article
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     * @param null $id
     */
    public function sph_update_article($article_id = 0){
        if($article_id){
            $Mysql = M();
            $sph_update_article = $Mysql->query("select * from sph_update_article where article_id = {$article_id}");
            if(!$sph_update_article){
                $Mysql->execute("insert into sph_update_article(`article_id`) values ({$article_id})");
            }
        }
    }

}