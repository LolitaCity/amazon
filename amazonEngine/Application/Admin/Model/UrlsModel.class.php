<?php
/**
 * 节点管理模型
 * 
 * @author  Lee<a605333742@gmail.com>
 * @time    2016-08-12
 */
namespace Admin\Model;
use Think\Model;

class UrlsModel extends Model{
    //一次性获得全部验证错误
    protected $patchValidate    =   true;
    //自动验证
    protected $_validate        =   array(
        array('url_content','require','url必须填写'),
        array('url_content','is_exist','url已经存在!',1,'callback',1), // 在新增的时候验证name字段是否唯一
    );
    function is_exist($url_content){
        $map['status']  =1;
        $map['url_content']    =$url_content;
        if($this->where($map)->find()){
            return FALSE;
        }else{
            return TRUE;
        }
    }
}