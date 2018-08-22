<?php
/**
 * 节点管理模型
 * 
 * @author  Lee<a605333742@gmail.com>
 * @time    2016-08-12
 */
namespace Admin\Model;
use Think\Model;

class NodeModel extends Model{
    //一次性获得全部验证错误
    protected $patchValidate    =   true;
    //自动验证
    protected $_validate        =   array(
        array('name','require','节点名必须填写'),
        array('name','is_exist','节点名已经存在!',1,'callback',1), // 在新增的时候验证name字段是否唯一
    );
    function is_exist($name){
        $map['status']  =1;
        $map['name']    =$name;
        if($this->where($map)->find()){
            return FALSE;
        }else{
            return TRUE;
        }
    }
}