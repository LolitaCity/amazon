<?php
/**
 * 登录退出类
 * 控制用户的登录和退出
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-04-19
 */
namespace Home\Controller;

class ManagerController extends EmptyController{
    /*
     * 构造函数
     * 
     * @return # 
     */
    public function _initialize() {
        parent::_initialize();
        $this->db_user  =D("User");
    }
    
    /*
     * 验证登录信息
     * 
     * @return #
     */
    public function login(){
        if(I("post.username")!=''&& I("post.password")!=""){            
            $username   =I("post.username","","trim");
            $password   =I("post.password","","trim");
            $map['name|email|tel|nickname'] =$username;
            $map['status']                  =1;
            $userinfo   =$this->db_user->where($map)->find();
            if($userinfo){
                if($userinfo['password']    !=md5($password)){
                   $this->error("用户名或密码错误",U('login'));
                }else{
                    unset($userinfo['password']);
                    $_SESSION["home"]           =$userinfo;
                    //$_SESSION["home"]['photo']  =PUBLIC_URL.'Upload/'.$userinfo['image'];
                    $this->redirect('Index/index');
                }
            }else{
                $this->error("用户不存在","login");
            }
        }else{
            $this->display();
        }        
    }
    
    /*
     * 退出系统
     * 
     * @return #
     */
    public function logout(){       
        unset($_SESSION["home"]['name']);        
        $this -> redirect("login");
    }    
}

