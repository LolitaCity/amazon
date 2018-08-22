<?php
/**
 * 经销商最低限价管理
 * 
 * @author  Lee<a605333742@gmail.com>
 * @time    2017-03-31
 */
namespace Admin\Controller;

class MinimumPriceController extends CommonController{
    /*
     * 构造函数，继承上一个类的构造
     * 
     * @return #
     */
    public function _initialize(){
        parent::_initialize();
        $this->db_product   =D("Productlist");
        $this->db_currency  =D("Currency");
    }
    
    /*
     * 经销商列表
     * 
     * @return #
     */
    public function resellerList(){
        $this->index('Reseller');
    }
    
    /*
     * 产品列表
     * 
     * @return #
     */
    public function productList(){
        $this->index('productlist');
    }
    
    /*
     * 货币列表
     * 
     * @return #
     */
    public function currencyList(){
        $this->index('Currency');
    }
    
    /*
     * 最低限价列表
     * 
     * @return #
     */
    public function mPriceList(){
        $this->index('MinimumPrice');
    }
    
    /*
     * 需要抓取的URL列表
     * 
     * @return #
     */
    public function productUrlList(){
        $this->index('productUrlList');
    }
    
    /*
     * 经销商警告列表
     * 
     * @return
     */
    public function noticeList(){
        $this->index('Warning');
    }
    
    /*
     * show前置操作（包括查询相关列表）
     * 
     * @return #
     */
    public function _before_show(){
        if(code(I("request.sign"))==4){
            $productList    =$this->db_product->where(array("status"=>1))->select();
            $currencyList   =$this->db_currency->where(array("status"=>1))->select();
            $this->assign('productList',$productList);
            $this->assign('currencyList',$currencyList);
        }
    }
    
    /*
     * 编辑前限制（让没有name的字段加上name字段）
     * 
     * @return #
     */
    public function _before_edit(){        
        if(trim(strtolower(I("request.db")))=='minimumprice'){
            $_REQUEST['name']   =$_POST['product_name'];
        }
        if(trim(strtolower(I("request.db")))=='reseller'){
            $_REQUEST['name']   =$_POST['reseller_company_name'];
        }
        if(trim(strtolower(I("request.db")))=='producturllist'){
           
            $_REQUEST['name']   =$_POST['product_url'];
        }
    }
}