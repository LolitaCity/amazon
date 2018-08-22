<?php
/**
 * 系统信息管理
 * 
 * @author  Lee<a605333742@gmail.com>
 * @time    2016-08-13
 */
namespace Admin\Controller;

class SystemController extends CommonController{
    /*
     * 构造函数
     * 
     * @return #
     */
    public function _initialize() {
        parent::_initialize();
    }
    
    /*
     * 操作日志记录列表
     * 
     * @return #
     */
    public function log(){
        if(I("request.user_id")){
            $_REQUEST['user_id']=A("Common")->getIds(I("request.user_id"),"name","Admin");
        }
        $_REQUEST['type']   ='0';
        $this->index("Log");       
    }
    
    /*
     * 登录日志列表
     * 
     * @return #
     */
    public function loginLog(){
        if(I("request.user_id")){
            $_REQUEST['user_id']=A("Common")->getIds(I("request.user_id"),"name","Admin");
        }
        $_REQUEST['type']   =1;
        $this->index("Log");
    }
    
    /*
     * 过期图片列表
     * 
     * @return #
     */
    public function invalidImg(){
        $this->index("Invaimg");
    }
    
    /*
     * 清理日志和过期图片时，判断其删除日期，日期在3个月以内的日志和过过期图片不允许清理
     * 
     * @return #
     */
    public function _before_delete(){
        if(!I("request.db")){
            $model  =D(CONTROLLER_NAME);
        }else{
            $model  =D(I("request.db","","code"));  
        }
        if (!empty ($model)) {
            $pk = $model->getPk();
            $id = $_REQUEST [$pk];
            if(!is_array($id)){                
                $id = explode(",",code($id));
            }
            if (isset($id)) {
                $condition  =array($pk => array('in', implode(',', $id)));
                $deleteList =$model->where($condition)->select(array("field"=>"add_time"));                
                $add_time   =array();                
                foreach($deleteList as $vo){
                    $add_time[] =$vo['add_time'];
                }
                if(!empty($add_time[0])){
                    foreach($add_time as $v){
                        if(((time()-strtotime($v))/3600/24/30)<3){
                            $this->error("不允许清理三个月以内的数据");
                            break;
                        }
                    }
                }                
            }
        }
    }
}

