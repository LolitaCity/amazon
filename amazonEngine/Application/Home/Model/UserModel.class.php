<?php
/**
 * 用户模型
 *
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-03-21
 */
namespace Home\Model;
use Think\Model;

class UserModel extends Model{   
    /*
     * 验证登录信息
     * 
     * @return array $userinfo
     * @return bool  FALSE
     */
    public function checkLogin($username,$password,$level_id=''){
        $user['username|email|tel|nickname']=$username;
        $user['is_show']                    =1;
        $user['level_id']                   =array("elt",$level_id);
        $userinfo =$this->where($user)->find();         
        if($userinfo){
            if($userinfo['password']    !=md5($password)){
               return FALSE;
            }else{
                $where['id']            =$userinfo['id'];
                //登录次数累加                
                $this->where($where)->setInc('inc');
                //组装权限表条件
                $level['is_show']       =1;
                $level['id']            =$userinfo['level_id'];
                $new_levelInfo          =new \Model\LevelModel();
                $levelInfo              =$new_levelInfo->levelList($level);                
                $userinfo['level_name'] =$levelInfo[0]['level_name'];
                $userinfo['level_id']   =$levelInfo[0]['id'];
                $data=array(
                    'id'        =>$userinfo['id'],
                    'login_time'=>time()
                );                
                //记录当前登录时间，作为下次登录时的上次登录时间显示 
                if($this->save($data)){
                    return $userinfo;
                }else{
                    return FALSE;
                }
            }
        }else{
            return FALSE;
        }                
    }
    
    /*
     * 查询用户列表
     * 
     * @return array $user_page 带分页的数组
     * @return array $userInfo  不带分页的数组
     * @return bool  FALSES;
     */
    public function userList($where,$ord='',$p=10,$from=''){        
        $user_page  =array();                                   //需要返回的数据较多，定义一个容器
        $total      =$this ->where($where)->count();            //统计总条数
        $per        =$p;                                        //每页显示条数
        $page       =new \Component\Page($total,$per);          //实例化分页类对象
        //查询数据
        $_list=$this->where($where)->order($ord)->limit(substr( $page->limit,5))->select();            
        /*根据地区部门编码转换为地区和部门*/
        foreach($_list as $k=>$v){ 
            $_list[$k]['section']       =$this->table('ft_section')->find($_list[$k]['section_id']);
            $_list[$k]['area_section']  =$_list[$k]['section']['sec_name'];            
            //查询朋友圈
            if(!empty($_list[$k]['friends_ids'])){                  
                $where1[$k]['id']       =array("in",$_list[$k]['friends_ids']);
                $where1[$k]['status']   =1;
                $_list[$k]['friends']   =$this->where($where1)->field("name")->select();                              
            }
            //unset($_list[$k]['area']);
            unset($_list[$k]['section']);
        }            
        $user_page['userList']  =$_list;
         if($from){
            $display = array(3,4, 5, 6, 7);
            $pageList               =$page -> fpage($display);  //获得页码列表
        }else{
            $pageList               =$page -> fpage();          //获得页码列表
        }        
        $user_page['pageList']  =$pageList;                 //页码列表，放到数组中，模板使用
        if($user_page){
            return $user_page;
        }else{
            return FALSE;
        } 
    }
    
    /*
     * 新增、修改用户
     * 
     * @return bool
     */
    public function edit($where){
        $g='';
        if($where['id']){
            //id存在为修改
            if($this->save($where)){
                $g="修改";
            }else{
                return FALSE;
            }
        }else{
            //否则为新增
            $where['add_time']  =time();
            if($this->add($where)){
                $g="新增";
            }else{
                return FALSE;
            }
        }
        $data=array(
            'user_id'   =>$where['log_name'],
            'content'   =>$g.'了名为 '.$where['name'].' 的用户',
            'add_time'  =>time()
        );
        if(M("Log")->add($data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 删除指定用户
     * 
     * @return bool
     */
    public function del($where){
        if($this->save($where)){
            $data=array(
                "user_id"   =>$where['log_name'],
                'content'   =>"删除了名为 ".$where['name']." 的用户",
                "add_time"  =>time()
            );
            if(M("Log")->add($data)){
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }
    
    /*
     * 更新上次登录时间
     * 
     * @return bool 
     */
    public function upLastTime($id){
        $data=$this->find($id);
        $where=array(
            'id'                =>$id,
            'last_logintime'    =>$data['login_time']
        );
        if($this->save($where)){
            return TRUE;
        }
    }
}
