<?php
/**
 * 员工活动回复模型
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-04-05
 */
namespace Home\Model;
use Think\Model;

class ReplyModel extends Model{
    /*
     * 员工回复列表
     * 
     * @return array    $reply_page
     * @return bool     FALSE
     */
    public  function replyList($where,$ord='',$p=15){           
        $reply_page =array();                         //需要返回的数据较多，定义一个容器
        $total      =$this ->where($where)-> count(); //统计总条数
        $per        =$p;                              //每页显示条数
        $page       =new \Component\Page($total,$per);//实例化分页类对象
        //查询数据
        $_list=$this->where($where)->order($ord)->limit(substr( $page->limit,5))->select();  
        foreach ($_list as $k=>$v){
            /*查询回复主题*/
            $_list[$k]['club']  =$this->table("ft_club")->where($where_1)->field("theme")->find($_list[$k]['club_id']);
            $_list[$k]['theme'] =$_list[$k]['club']['theme'];
            /*end*/
            /*查询回复人*/
            $_list[$k]['userIgn']       =$this->table("ft_user")->where($where_1)->field("name")->find($_list[$k]['assign_user_id']);
            $_list[$k]['assign_user']   =$_list[$k]['userIgn']['name'];
            /*end*/
            if($_list[$k]['accept_user_id']){                    
                /*查询接收人*/
                $_list[$k]['userEpt']       =$this->table("ft_user")->where($where_1)->field("name")->find($_list[$k]['accept_user_id']);
                $_list[$k]['accept_user']   =$_list[$k]['userEpt']['name'];
                /*end*/
            }else{
                $_list[$k]['accept_user']   ='';
            }
            /*查询修改人*/
            if($_list[$k]['change_user_id']){  
                $_list[$k]['userNge']       =$this->table("ft_user")->where($where_1)->field("name")->find($_list[$k]['change_user_id']);
                $_list[$k]['change_user']   =$_list[$k]['userNge']['name'];
            }else{
                $_list[$k]['change_user']   ='';
            }
            /*end*/
            unset($_list[$k]['club']);
            unset($_list[$k]['userIgn']);
            unset($_list[$k]['userEpt']);
            unset($_list[$k]['userNge']);
        }
        $reply_page['replyList']=$_list;
        $pageList =$page -> fpage();               //获得页码列表
        $reply_page['pageList'] =$pageList;        //页码列表，放到数组中，前台使用
        if($reply_page){
            return $reply_page;
        }else{
            return FALSE;
        }         
    }
    
    /*
     * 新增、编辑回复
     * 
     * @return bool
     */
    public function editReply($where){
        $g="";
        if($where['id']){           
            //id存在说明为修改
            if($this->save($where)){
                $g="修改";
            }else{
                return FALSE;
            }
        }else{
            //id不存在为新增           
            $where['add_time']      =time();
            if($this->add($where)){
                $g="新增";
            }else{
                return FALSE;
            }
        }
        $data =array(
            'user_id'   =>$where['log_name'],
            'content'   =>$g."了主题id为 ".$where['club_id']." 的回复",
             'add_time' =>time()
        );
         if(M("Log")->add($data)){
             return TRUE;
         }else{
             return FALSE;
         }
    }
    
    /*
     * 删除指定回复
     * 
     * @return #
     */
    public function del($where){
        if($this->save($where)){
            $data =array(
                "user_id"   =>$where['log_name'],
                'content'   =>"删除了id为 ".$where['id']." 的回复",
                "add_time"  =>time()
            );
            if(M("Log")->add($data)){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}

