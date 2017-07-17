<?php
/**
 * 后台首页
 * Class IndexController
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/4/12
 * Time: 15:52
 */
namespace Admin\Controller;

class IndexController extends BaseController {

    public function index(){
        $gd = '不支持';
        if (function_exists('gd_info')) {
            $gd = gd_info();
            $gd = $gd['GD Version'];
        }

        $hostport = $_SERVER['SERVER_NAME']."($_SERVER[SERVER_ADDR]:$_SERVER[SERVER_PORT])";
        $mysql = function_exists('mysql_close') ? mysql_get_client_info(): '不支持';
        $info = array(
            'system' => get_system(),
            'hostport' => $hostport,
            'server' => $_SERVER['SERVER_SOFTWARE'],
            'php_env' => php_sapi_name(),
            'app_dir' => WEB_ROOT,
            'mysql' => $mysql,
            'gd' => $gd,
            'upload_size' => ini_get('upload_max_filesize'),
            'exec_time' => ini_get('max_execution_time') . '秒',
            'disk_free' => round((@disk_free_space(".")/(1024 * 1024)),2).'M',
            'server_time' => date("Y-n-j H:i:s"),
            'beijing_time' => gmdate("Y-n-j H:i:s", time() + 8 * 3600),
            'reg_gbl' => get_cfg_var("register_globals") == '1'? 'ON' : 'OFF',
            'quotes_gpc' => (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO',
            'quotes_runtime' => (1===get_magic_quotes_runtime()) ?'YES' : 'NO',
            'fopen' => ini_get('allow_url_fopen') ? '支持' : '不支持'
        );
        $this->assign('info', $info);
        $this->display();
    }

    /**
     * 修改密码
     * Function edit
     * User: edgeto
     * Date: 2016/06/16
     * Time: 16:00
     */
    public function password(){
        $id = is_admin();
        $Admin = D('Admin');
        $where = array();
        $where['id'] = intval($id);
        $res = $Admin->relation(true)->where($where)->find();
        if(!$res){
            $this->error('参数有误！');exit;
        }
        /*if($res['role']['is_super']){
            $this->error('您没有权限执行该操作！');exit;
        }*/
        if(IS_POST){
            $data = $Admin->create();
            if(!$data){
                $this->error($Admin->getError());exit;
            }
            $res = $Admin->where($where)->save($data);
            if($res){
                $this->success('修改成功！');exit;
            }
        }
        $this->assign('data',$res);
        $this->display();
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