<?php
/**
 * 判断登录类
 * 判断是否有session存在，存在则可以登录，否则跳转到登陆页面
 * 
 * @author Lee <a605333742@gmail.com>
 * @time 2016-04-19
 */
namespace Home\Controller;

class AllowController extends EmptyController{
    /*
     *构造函数
     * 用户输入URL的时候判断是否存在$_SESSION['home']['name']，存在则允许跳转到指定url，否则跳转到登录页面
     * 
     * @return # 
     */
    public function _initialize(){
        parent::_initialize();
        
    }
}
