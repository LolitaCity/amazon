<?php
/**
 * 后台首页集成
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-03-22
 */
namespace Admin\Controller;

class IndexController extends CommonController {
    /*
     * 构造函数
     *
     * @return  #
     */
    public function _initialize(){ 
        parent::_initialize(); 
        $_REQUEST ['listRows']  =100;
        $this->db   =D("Node");        
    }
    
    /*
     * 首页
     * 
     * @return #
     */
    public function index(){
        $oneNodeList=A("Public")->ckeckAuth();
        $twoNodeList=A("Public")->ckeckAuth(1);
        $this->assign("oneNodeList",$oneNodeList);
        $this->assign("twoNodeList",$twoNodeList);
        $this->display();
    }
}