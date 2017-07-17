<?php

/**
 * 网站配置模型
 * Class ConfigModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model;

class ConfigModel extends Model{

    /**
     * 自动验证
     * @var array
     */
    protected $_validate = array(
        array('config_sign','require','请输入配置标识'), //默认情况下用正则进行验证
        array('config_name','require','请输入配置名称'), //默认情况下用正则进行验证
        array('config_value','require','请输入配置值'), //默认情况下用正则进行验证
        array('sort','number','排序请输入纯数字!'), //默认情况下用正则进行验证
    );

}