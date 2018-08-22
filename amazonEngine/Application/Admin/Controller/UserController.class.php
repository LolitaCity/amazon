<?php
/**
 * 用户管理
 * 
 * @author  Lee<a605333742@gmail.com>
 * @time    2016-08-09
 */
namespace Admin\Controller; 

class UserController extends CommonController{
    /*
     * 构造函数
     * 
     * @return #
     */
    public function _initialize() {
        parent::_initialize();
        $this->db_role  =D("Role");
        $this->db_admin =D("Admin");
        $this->db_a_r   =D("AdminRole");
    }    
    
   
    
    /*
     * 后台管理员列表
     * 
     * @return #
     */
    public function adminIndex(){
        $_REQUEST['id'] =array("neq",1);
        $this->index("Admin");
    }
    
    /*
     * show前置操作
     * 
     * @return #
     */
    public function _before_show(){
        $map['status']  =1;
        if(session('authId')==1){
            $roleList   =D("Role")->where($map)->select();
        }else{
            $map['id']  =array('gt',2);
            $roleList   =D("Role")->where($map)->select();
        }
        $this->assign("rlist",$roleList);
    }
    
    /*
     * 编辑用户，包括新增和修改
     * 
     * @return  #
     */
    public function edit(){
        $map['role_id'] =I("request.role_id");
        $this->db_admin->startTrans();
        if(I("request.id")){
            //说明为修改
            $map['u_id']=I("request.id");
            $oldRole    =$this->db_a_r->where(array("u_id"=>I("request.id")))->getField("role_id");
            if($map['role_id']!=$oldRole){
                //如果查询出来的role_id和传过来的role_id不同，则说明有修改过角色                
                if($this->db_a_r->where(array("u_id"=>I("request.id")))->save($map)==FALSE){
                    $this->db_admin->rollback();
                    $this->error("参数错误，用户编辑失败");
                }
            }
            if(I("request.password")){
                unset($_POST['password']);
            }else{
                $_POST['password']  =md5(trim(I("request.newPassword")));
            }
            if(!$this->db_admin->create()){
                $this->db_admin->rollback();
                $this->error($this->db_admin->getError());
            }
            if(!$this->db_admin->save()){
                $this->db_admin->rollback();
                $this->error("参数错误，用户编辑失败");
            }
            $this->db_admin->commit();
            $this->success("用户编辑成功");
        }else{
            $map['u_id']=($this->db_admin->max('id'))+1;            
            if(!$this->db_a_r->add($map)){
                $this->db_admin->rollback();
                $this->error("参数错误，用户新增失败");                
            }
            $_POST['password']      =md5(trim(I("request.password")));
            $_POST['rnewPassword']  =md5(trim(I("request.rnewPassword")));
            if(!$this->db_admin->create()){
                $this->db_admin->rollback();
                $this->error($this->db_admin->getError());
            }
            if(!$this->db_admin->add()){
                $this->db_admin->rollback();
                $this->error("参数错误，用户新增失败");
            }
            $this->db_admin->commit();
            $this->success("用户新增成功");
        }
    }
    
    /*
     * 删除用户前置操作
     * 
     * @return @
     */
    public function _before_del(){
        $del_id =I("request.id");
        if(!is_array($del_id)){
            $del_id =explode(",",code($del_id));
        }
        if(in_array("1",$del_id) || in_array(session("authId"),$del_id)){
            $this->error("不能删除超级管理员或者自己");
            exit;
        }
    }
}