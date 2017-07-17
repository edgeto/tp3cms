<?php
/**
 * 文章页
 * Class ArticleController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace News\Controller;

class ArticleController extends BaseController {

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
    	$this->display();
    }

    /**
     * 焦点资讯
     * Function hotPoint
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function hotPoint(){
        $category_id = 1;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('id desc,sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 桌桌有娱
     * Function ent
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function ent(){
        $category_id = 2;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 有温度
     * Function wendu
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function wendu(){
        $category_id = 3;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 巨头
     * Function tycoon
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function tycoon(){
        $category_id = 6;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 人物
     * Function character
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function character(){
        $category_id = 7;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 电商
     * Function dianshang
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function dianshang(){
        $category_id = 8;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 创投
     * Function chuangye
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function chuangye(){
        $category_id = 9;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 创投
     * Function hardware
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function hardware(){
        $category_id = 10;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 互联网+
     * Function network
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function network(){
        $category_id = 11;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * p2p
     * Function p2p
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function p2p(){
        $category_id = 12;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 前沿技术 
     * Function tech
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function tech(){
        $category_id = 13;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
        $title = $category_title.'-'.$this->get('title');
        $this->assign('title',$title);
        $this->display('category');
    }

    /**
     * 游戏 
     * Function games
     * User: edgeto
     * Date: 2016/08/09
     * Time: 13:00
     */
    public function games(){
        $category_id = 14;
        $map = array(
            'category_id' => $category_id,
            'status' => 0
        );
        // 主体列表
        $Article = D('Article');
        $count = $Article->where($map)->count();// 查询满足要求的总记录数
        $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
        $Page = new \Think\Page($count,$HOME_LIST_ROWS );// 实例化分页类 
        $show = $Page->show();// 分页显示输出
         // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->where($map)->order('sort desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex(1)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList(1));
        $category_title = M('art_category')->where("id={$category_id}")->getField('name');
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
        $Article = D('Article');
        $map = array(
            'status' => 0,
            'id' => intval($id)
        );
        if(I('get.from') == 'test'){
            $map = array(
                'id' => intval($id)
            );
        }
        $data = $Article->relation('from')->where($map)->find();
        if(empty($data)){
            $this->error('内容不存在!');exit;
        }
        $this->assign('data',$data);
        $title = $data['title'].'-'.C('main_title');
        $category_title = M('art_category')->where("id={$data['category_id']}")->getField('name');
        if($category_title){
            $title = $data['title'].'-'.$category_title.'-'.C('main_title');
        }
        $this->assign('title',$title);
        if(!empty($data['keyword'])){
            $this->assign('keywords',$data['keyword']);
        }
        if(!empty($data['desc'])){
            $this->assign('description',$data['desc']);
        }
        $category_id = $data['category_id'];
        // 相关推荐
        $c_map = array(
            'status' => 0,
            'category_id' => $category_id
        );
        $about = $Article->getArticle($c_map,5);
        $this->assign('about',$about);
        // 本周推荐
        $this->assign('week_list',$Article->getArticleIndex($category_id)); 
        // 本周热点
        $this->assign('hot_list',$Article->getWeekHotList($category_id));
        // 更新浏览数
        $Article->where("id={$id}")->setInc('view',1);
        // 收藏
        $co_map = array(
            'user_id' =>USER_ID,
            'article_id'=> $id
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
    public function getComment($id=0,$p = 0){
        $data = array();
        $this->_code['msg'] = '没有数据';
        if($id){
            $map = array(
                'article_id' => $id,
                'status' => 0,
            );
            $HOME_LIST_ROWS = C('HOME_LIST_ROWS');
            $p = $p >=0 ? intval($p) : 0;
            $limit = intval($p)*$HOME_LIST_ROWS.','.$HOME_LIST_ROWS;
            $data = D('ArtComment')->relation('user')->where($map)->limit($limit)->select();
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
                    $data['article_id'] = intval($post['id']);
                    $data['content'] = trim(addslashes($post['content']));
                    $data['user_id'] = USER_ID;
                    $data['status'] = 1;
                    $ArtComment = D("ArtComment");
                    $res = $ArtComment->create($data);
                    $res = $ArtComment->add();
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
            $data['article_id'] = $id;
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
            $map['article_id'] = $id;
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