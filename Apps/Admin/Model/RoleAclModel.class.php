<?php

/**
 * 角色对应权限
 * Class ResourceModel
 * Created by PhpStorm.
 * User: edgeto
 * Date: 2016/6/15
 * Time: 11:41
 */
namespace Admin\Model;
use Think\Model\RelationModel;

class RoleAclModel extends RelationModel{

    /**
     * 关联模型
     * @var array
     */
    protected $_link = array(
        // 一个field对应一个input
        'role_acl' => array(
            'mapping_type' => self::BELONGS_TO,
            'class_name' => 'Resource',
            'foreign_key' => 'resource_id',
            'mapping_fields' => 'id,controller,action,show_nav'
        )
    );

    public function getOneResourceByRoleAcl($role_acl_id = 0){
        $resource = U('index');
        if($role_acl_id){
            $map['role_id'] = $role_acl_id;
            $res = $this->relation('role_acl')->where($map)->find();
            if($res){
                $resource = $res['role_acl']['controller'].'/'.$res['role_acl']['action'];
            }
        }
        return $resource;
    }

}