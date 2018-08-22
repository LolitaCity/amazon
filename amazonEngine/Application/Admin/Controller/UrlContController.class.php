<?php
/**
 * url列表管理
 * 
 * @author  Lee<a605333742@gmail.com>
 * @time    2017-03-30
 */
namespace Admin\Controller;

class UrlContController extends CommonController{   
    /*
     * 构造函数
     * 
     * @return #
     */
    public function _initialize() {
        parent::_initialize();
        $this->db   =D("urls");
    }
    
    /*
     * 需要抓取的url列表
     * 
     * @return #
     */
    public function index(){
        A("Common")->index('Urls');
    }
}
