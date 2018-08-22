<?php
/*
 * 主页，用户基本信息，头像，任务，部门公告，公司公告，公司新闻
 * 
 * @author Lee <a605333742@gmail.com>
 * @time 2016-04-19  
 */
namespace Home\Controller;

class IndexController extends AllowController{
    /*
     * 构造函数
     *
     * @return  #
     */
    public function _initialize(){ 
        parent::_initialize();
    }
    /*
     * 主页，展示详细信息
     * 
     * @return #
     */
    public function index() {  
		$this->display();
    }
}
