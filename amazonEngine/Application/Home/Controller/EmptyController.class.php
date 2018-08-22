<?php
/**
 * 空控制器、空方法，防止非法操作
 * 
 * @auth Lee <a605333742@gmail.com>
 * @time 2016-04-19
 */
namespace Home\Controller;
use Think\Controller;

class EmptyController extends Controller{
    /*
     * 构造函数
     * 
     * @return #
     */
    public function _initialize(){
        $webInfo=D("Web")->find();
        $this->assign("webInfo",$webInfo);
    }
    
    /*
     * 空控制器定义，防止非法操作
     * 
     * @return #
     */
    public function index(){
        echo "<img src=".IMG_URL."404.jpg>";
    }
    
    /*
     * 空方法定义，防止非法操作
     * 
     * @return #
     */
    public function _empty(){
       echo "<img src=".IMG_URL."404.jpg>";
    }
}

