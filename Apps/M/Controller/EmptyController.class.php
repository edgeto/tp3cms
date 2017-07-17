<?php
/**
 * 空控制器
 * Class EmptyController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace M\Controller;

class EmptyController extends BaseController {

    /**
     * 空方法
     * Function index
     * User: edgeto
     * Date: 2016/06/20
     * Time: 11:00
     */
    public function index(){
        $this->error('没有此控制器!');
    }

    /**
     * 空方法
     * Function _empty
     * User: edgeto
     * Date: 2016/06/20
     * Time: 11:00
     */
    public function _empty(){
        $this->error('没有此方法!');
    }

}