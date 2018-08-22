<?php
/**
 * 空控制器、空方法，防止非法操作
 * 
 * @auth Lee <a605333742@gmail.com>
 * @time 2016-05-13
 */
namespace Admin\Controller;
use Think\Controller;

class EmptyController extends Controller{
    /*
     * 空控制器定义，防止非法操作
     * 
     * @return #
     */
    public function _Index(){
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

