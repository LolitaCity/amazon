<?php
/**
 * 管理员用户
 * 
 * @author  Lee<a605333742@gmail.com>
 * 
 * @time    2016-11-02
 */
namespace Admin\Model;
use Think\Model;

class AdminModel extends Model{
    
    //一次性获得全部验证错误
    protected $patchValidate=true;
    //实现表单项目验证
    //通过重写父类属性_validate实现表单验证
    protected $_validate    =array(
        array('name','require','用户名必须填写'),
        array('name','is_exist','该用户名已存在！','1','callback'),
        array('password','require','密码必须填写'),
        //可以为同一个项目设置多个验证
        array('rnewPassword','require','确认密码必须填写'),
        //与密码的值得是一致的
        array('rnewPassword','password','两次密码的信息必须一致',0,'confirm'),
        //邮箱验证
        array('email','email','邮箱格式不正确',2),
        //验证手机
        //都是数字的、长度5-10位、 首位不为0
        //正则验证  /^[1-9]\d{4,9}$/
        array('tel',"/^1[34578]\d{9}/",'手机格式不正确',2),
        
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