<?php
/**
 * 图片
 * Class PicController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace News\Controller;

class PicController extends BaseController {

    /**
     * [_initialize description]
     * @return [type] [description]
     */
    protected function _initialize(){
        // Pic 30 重定向
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: ".C('DEFAULT_URL.PIC').__SELF__);
    }

}