<?php
/**
 * 网站管理
 * 
 * @author  Lee<a605333742@gmail.com>
 * @time    2016-08-30
 */
namespace Admin\Controller;

class WebController extends CommonController{
    /*
     * 构造函数
     * 
     * @return #
     */
    public function _initialize() {
        parent::_initialize();
        $this->db_web   =D("Web");
        $this->db_node  =D("Node");
        $this->db_role  =D("Role");
        $this->db_banner=D("Banner");
    }
    
    /*
     * 网站消息
     * 
     * @return #
     */
    public function info(){
        $webInfo    =$this->db_web->where(array("status"=>1))->find();
        $this->assign("webInfo",$webInfo);
        $this->display();
    }
    
    /*
     * 编辑前置操作
     * 
     * @return #
     */
    public function _before_edit(){               
        if(I("request.image")){
            $smallName              =substr(I("request.image"),strrpos(I("request.image"),'/')+1);
            $_POST['small_image']   =str_replace($smallName,'small_'.$smallName, I("request.image"));            
        }else if(I("request.img")){
            $smallName              =substr(I("request.img"),strrpos(I("request.img"),'/')+1);
            $_POST['small_img']     =str_replace($smallName,'small_'.$smallName, I("request.img"));
        }else if(I("request.photo")){
            $smallName              =substr(I("request.photo"),strrpos(I("request.photo"),'/')+1);
            $_POST['small_photo']   =str_replace($smallName,'small_'.$smallName, I("request.photo"));
        }
    }
    
    /*
     * 角色列表
     * 
     * @return #
     */
    public function role(){
        $_REQUEST['id'] =array("neq",1);
        $this->index('Role');
    }
    
    /*
     * 权限列表
     * 
     * @return #
     */
    public function nodeList(){
        $map['status']  =1;
        $map['level']   =0;
        
        $map1['status'] =1;
        $map1['level']  =1;
        $noeNodeList    =$this->db_node->where($map)->select();          //顶级节点
        $twoNodeList    =$this->db_node->where($map1)->select();         //次级节点
        $roleList       =A("Public")->checkRole('',I("request.role_id",'','code')); 
        foreach($roleList as $vo){
            $roles[]=$vo['node_id'];
        }
        $this->assign("oneList",$noeNodeList);
        $this->assign("twoList",$twoNodeList);
        $this->assign("rList",$roles);
        $this->display();
    }
    
    /*
     * 授权
     * 
     * @return #
     */
    public function addAuth(){
        if(!I("request.role_id") && !I("request.nodeId")){
            $this->error("授权失败");
        }
        D("RoleNode")->where(array("role_id"=>I("request.role_id")))->delete();       
        foreach(I("request.nodeId") as $vo){
            $map['role_id'] =I("request.role_id");
            $map['node_id'] =$vo;
            if(!D("RoleNode")->add($map)){
               $this->error("授权失败"); 
            }
        }
        session('nodeList_s',null);
        session('nodeList_t',null);
        $this->success("授权成功");        
    }
    
    /*
     * 删除前置操作
     * @自己所在组不允许删除
     * 
     * @return #
     */
    public function _before_del(){
        if(I("request.sign",'','code')==1){
            if(in_array(1,code(I("request.id")))){
                $this->error("不允许删除超级管理员组");
                exit;
            }
            $role   =$this->db_role->where(array('id'=>array('in',I("request.id"))))->Field('name')->select();
            foreach($role as $vo){
                if($vo['name']==getAdminRole(session("authId"))){
                    $this->error("不允许删除自己所在组");
                    exit;
                }
            }
        }
    }
    
    /*
     * 访问列表
     * 
     * @return #
     */
    public function visit(){
        if(I("request.add_time_y")){
            $_REQUEST['add_time']   =I("request.add_time_y");
        }else if(I("request.add_time_ym")){
            $_REQUEST['add_time']   =I("request.add_time_ym");
        }else if(I("request.add_time_ymd")){
            $_REQUEST['add_time']   =I("request.add_time_ym");
        }
        $this->index("Visit");
    }
    
    /*
     * banner
     * 
     * @return #
     */
    public function banner(){
        $this->index("Banner",'sort_',TRUE);
    }
}