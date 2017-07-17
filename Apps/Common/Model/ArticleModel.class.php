<?php

/**
 * 文章模型
 * Class ArticleModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Common\Model;
use Think\Model\RelationModel;

class ArticleModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'category' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'ArtCategory',
            'foreign_key' => 'category_id',
            'mapping_fields' => 'id,name'
        ),
        'comment' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'ArtComment',
            'foreign_key' => 'article_id',
            'mapping_fields' => 'id,content',
            'mapping_limit' => 10,
            'condition' => 'status = 0'//关联条件
        ),
        'static' => array(
            'mapping_type' => self::HAS_MANY,
            'class_name' => 'Static',
            'foreign_key' => 'article_id',
            'mapping_fields' => 'url,name',
            'mapping_limit' => 3,
            'mapping_order' => 'sort desc'
        ),
        'from' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'InfoFrom',
            'foreign_key' => 'from',
            'mapping_fields' => 'id,name,url',
            'as_fields' => 'id:from_id,name:from_name,url:from_url',
            'condition' => 'status = 0'//关联条件
        ),
    );

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('title','require','请输入标题'), //默认情况下用正则进行验证
        array('title','','标题已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('content','require','请输入内容'), //默认情况下用正则进行验证
        array('sort','number','排序请输入纯数字！'),
        array('view','number','查看次数请输入纯数字！'),
    );

    /**
     * 自动完成
     * @var array
     */
    protected $_auto = array (
        array('add_time',NOW_TIME),  // 新增的时候把status字段设置为1
        array('update_time',NOW_TIME,self::MODEL_BOTH), // 对update_time字段在更新的时候写入当前时间戳
    );

    /**
     * [$_code description]
     * @var array
     */
    public $_code = array(
        'code'=>1,
        'data'=>'',
        'msg'=>'',
    );

    /**
     * 首页文章推荐
     * Function getArticleIndex
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $category_id
     * @param bool $return
     * @return mixed
     */
    public function getArticleIndex($category_id = 0,$return = false){
        $map = array(
            'is_index' => 1,
            'is_pc' => 1,
            'status' => 0,
        );
        $category_id = $category_id ? $category_id : I('get.category_id',0,'intval');
        if($category_id){
            $map['category_id'] = $category_id;
        }
        $article_list = $this->where($map)->order('sort desc')->field('id,title,abstract,category_id,img')->limit(10)->select();
        if($article_list){
            $this->_code['code'] = 0;
            $this->_code['data'] = $article_list;
        }else{
            $this->_code['msg'] = '没有记录!';
        }
        return $article_list;
    }

    /**
     * 首页文章推荐
     * Function getArticleRecommend
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param int $category_id
     * @param bool $return
     * @return mixed
     */
    public function getArticleRecommend($category_id = 0,$return = false){
        $map = array(
            'is_recompend' => 1,
            'is_pc' => 1,
            'status' => 0,
        );
        $category_id = $category_id ? $category_id : I('get.category_id',0,'intval');
        if($category_id){
            $map['category_id'] = $category_id;
        }
        $article_list = $this->where($map)->order('sort desc')->field('id,title,abstract,category_id,img')->limit(10)->select();
        if($article_list){
            $this->_code['code'] = 0;
            $this->_code['data'] = $article_list;
        }else{
            $this->_code['msg'] = '没有记录!';
        }
        return $article_list;
    }

    /**
     * 根据条件获取数据
     * Function getArticle
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param array $map
     * @param bool $return
     * @param string $order_by
     * @return mixed
     */
    public function getArticle($map = array(),$limit = 0,$order_by = 'sort desc',$return = false){
        $article_list = $this->where($map)->order($order_by)->field('id,title,abstract,category_id,img,add_time')->limit($limit)->select();
        if($article_list){
            $this->_code['code'] = 0;
            $this->_code['data'] = $article_list;
        }else{
            $this->_code['msg'] = '没有记录!';
        }
        return $article_list;
    }

    /**
     * 本周热点
     * Function getWeekHotList
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param bool $return
     * @param int $category_id
     * @return array
     */
    public function getWeekHotList($category_id = 0,$return = false){
        $data = array();
        $time = NOW_TIME;
        $weekly = date('w');//当天是星期的第几天，0表示星期天
        $day_time = 86400;//一天的时间
        if($weekly == 0){//星期天，则前一个星期为前七天
            $time -= $day_time * 7;
        }
        else
        {
            $time -= $day_time * $weekly;
        }
        $map = array(
            'is_pc' => 1,
            'status' => 0,
            'add_time' => array('gt',$time)
        );
        if($category_id){
            $map['category_id'] = $category_id;
        }
        $data = $this->where($map)->order('view desc')->field('id,title,abstract,category_id,img')->limit(10)->select();
        if($data){
            $this->_code['code'] = 0;
            $this->_code['data'] = $data;
        }else{
            $this->_code['msg'] = '没有记录!';
        }
        return $data;
    }

    /**
     * 取文章分类ID
     * Function getArtCategoryByArticeId
     * User: edgeto
     * Date: 2016/07/05
     * Time: 17：20
     * @param  integer $article_id [description]
     * @return [type]              [description]
     */
    public function getArtCategoryByArticeId($article_id = 0){
        $data = '';
        if($article_id){
            $map = array(
                'id' => intval($article_id)
            );
            $res = $this->relation('category')->where($map)->find();
            if(!empty($res['category'])){
                $data = $res['category']['name'];
            }
        }
        return $data;
    }

}