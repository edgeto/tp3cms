<?php
/**
 * 图片
 * Class PicController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Pic\Controller;

class PicController extends BaseController {

	/**
     * 首页
     * Function index
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function index(){
    	$this->display();
    }

    /**
     * 女神
     * Function girls
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function girls(){
        $category_id = 1;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Pic = D('Pic');
        $count = $Pic->where($map)->count();// 查询满足要求的总记录数
        $this->assign('count',$count);
        $Page = new \Think\Page($count,16);// 实例化分页类 
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Pic->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 热门推荐
        $this->assign('recompend_list',$Pic->getRecompendIndex());
        $category_title = M('pic_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 国产美女
     * Function guochan
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function guochan(){
        $category_id = 2;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Pic = D('Pic');
        $count = $Pic->where($map)->count();// 查询满足要求的总记录数
        $this->assign('count',$count);
        $Page = new \Think\Page($count,16);// 实例化分页类 
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Pic->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 热门推荐
        $this->assign('recompend_list',$Pic->getRecompendIndex());
        $category_title = M('pic_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 港台美女
     * Function gangtai
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function gangtai(){
        $category_id = 3;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Pic = D('Pic');
        $count = $Pic->where($map)->count();// 查询满足要求的总记录数
        $this->assign('count',$count);
        $Page = new \Think\Page($count,16);// 实例化分页类 
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Pic->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 热门推荐
        $this->assign('recompend_list',$Pic->getRecompendIndex());
        $category_title = M('pic_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 日韩美女
     * Function rihan
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function rihan(){
        $category_id = 4;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Pic = D('Pic');
        $count = $Pic->where($map)->count();// 查询满足要求的总记录数
        $this->assign('count',$count);
        $Page = new \Think\Page($count,16);// 实例化分页类 
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Pic->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 热门推荐
        $this->assign('recompend_list',$Pic->getRecompendIndex());
        $category_title = M('pic_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 精选美女
     * Function xihuan
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function xihuan(){
        $category_id = 5;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Pic = D('Pic');
        $count = $Pic->where($map)->count();// 查询满足要求的总记录数
        $this->assign('count',$count);
        $Page = new \Think\Page($count,16);// 实例化分页类 
        $show = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Pic->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 热门推荐
        $this->assign('recompend_list',$Pic->getRecompendIndex());
        $category_title = M('pic_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 详情页面
     * Function detail
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function detail($id = 0){
        if(empty($id)){
            $this->error('内容不存在!');exit;
        }
        $Pic = D('Pic');
        $id = intval($id);
        $map = array(
            'status' => 0,
            'id' => $id
        );
        if(I('get.from') == 'test'){
            $map = array(
                'id' => intval($id)
            );
        }
        $data = $Pic->relation('from')->where($map)->find();
        if(empty($data)){
            $this->error('内容不存在!');exit;
        }
        $this->assign('data',$data);
        $title = $data['name'].'-'.C('main_title');
        $category_title = M('pic_category')->where("id={$data['category_id']}")->getField('name');
        if($category_title){
            $title = $data['name'].'-'.$category_title.'-'.C('main_title');
        }
        $this->assign('title',$title);
        if(!empty($data['keyword'])){
            $this->assign('keywords',$data['keyword']);
        }
        if(!empty($data['desc'])){
            $this->assign('description',$data['desc']);
        }
        // 取图片
        $PicImg = D('PicImg');
        $pic_map = array(
            'pic_id' => $id
        );
        $count = $PicImg->where($pic_map)->count();
        $this->assign('count',$count);
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS);// 实例化分页类 
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        $list = $PicImg->where($pic_map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $category_id = $data['category_id'];
        // 相关推荐
        $cate_map = array(
            'status' => 0,
            'category_id' => $category_id
        );
        $about = $Pic->getPic($cate_map,6);
        $this->assign('about',$about);
        // 更新浏览数
        $Pic->where("id={$id}")->setInc('view',1);
        // 收藏
        $co_map = array(
            'user_id' =>USER_ID,
            'pic_id'=> $id
        );
        $collect = M('collect')->where($co_map)->find();
        $this->assign('collect',$collect);
        $this->display();
    }

    /**
     * 评论列表
     * Function getComment
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     * @param  integer $id [description]
     * @param  integer $p  [description]
     * @return [type]      [description]
     */
    public function getComment($id=0,$p = 1){
        $data = array();
        $this->_code['msg'] = '没有数据';
        if($id){
            $map = array(
                'pic_id' => $id,
                'status' => 0,
            );
            $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
            $limit = intval($p)*$HOME_LIST_ROWS.','.$HOME_LIST_ROWS;
            $data = D('PicComment')->relation('user')->where($map)->limit($limit)->select();
            if($data){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '成功';
                $this->_code['data'] = $data;
            }
        }
        $this->ajaxReturn($this->_code);
    }

    /**
     * 评论
     * Function doComment
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     * @param  integer $id [description]
     * @param  integer $p  [description]
     * @return [type]      [description]
     */
    public function doComment($id=0,$content = ''){
        $this->_code['msg'] = '评论失败！';
        $post = file_get_contents("php://input");
        $post = json_decode($post,true);
        if(!defined(USER_ID)){
            $this->_code['msg'] = '请选登陆再评论！';
        }else{
            if(empty($post) || empty($post['content'])){
                $this->_code['msg'] = '评论内容不能为空！';
            }else{
                if(!isset($post['id'])){
                    $this->_code['msg'] = '参数错误！';
                }else{
                    $data['pic_id'] = intval($post['id']);
                    $data['content'] = trim(addslashes($post['content']));
                    $data['user_id'] = USER_ID;
                    $data['status'] = 1;
                    $PicComment = D("PicComment");
                    $res = $PicComment->create($data);
                    $res = $PicComment->add();
                    if($res){
                        $this->_code['code'] = 0;
                        $this->_code['msg'] = '评论成功，审核通过即可显示！';
                        $this->_code['data'] = $res;
                    }
                }
            }
        }
        $this->ajaxReturn($this->_code);
    }

    /**
     * 添加收藏
     * Function collect
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function collect($id=0){
        $this->_code['msg'] = '添加收藏失败！';
        if($id){
            $data['user_id'] = USER_ID;
            $data['pic_id'] = $id;
            $res = M('collect')->add($data);
            if($res){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '添加收藏成功！';
            }
        }else{
            $this->_code['msg'] = '参数错误！';
        }
        $this->ajaxReturn($this->_code);
    }

    /**
     * 删除收藏
     * Function uncollect
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     * @param  integer $id [description]
     * @return [type]      [description]
     */
    public function uncollect($id=0){
        $this->_code['msg'] = '删除收藏失败！';
        if($id){
            $map['user_id'] = USER_ID;
            $map['pic_id'] = $id;
            $res = M('collect')->where($map)->delete();
            if($res){
                $this->_code['code'] = 0;
                $this->_code['msg'] = '删除收藏成功！';
            }
        }else{
            $this->_code['msg'] = '参数错误！';
        }
        $this->ajaxReturn($this->_code);
    }

}