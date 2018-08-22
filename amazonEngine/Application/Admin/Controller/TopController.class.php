<?php
/**
 * 顶部图片管理
 * 
 * @author  Lee<a605333742@gmail.com>
 * @time    2016-08-09
 */
namespace Admin\Controller; 

class TopController extends CommonController{
    /*
     * 构造函数
     * 
     * @return #
     */
    public function _initialize() {
        parent::_initialize();
    }
    
    /*
     *图片列表
     * 
     * @return #
     */
    public function index(){
        A("Common")->index("","sort_");
    }
}