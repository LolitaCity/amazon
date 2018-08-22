<?php
/**
 * 即时通讯管理模型
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-05-30
 */
namespace Home\Model;
use Think\Model;

class ImModel extends Model{
    /*
     * 即时通讯会话列表
     * 
     * @return array $im_page
     * @return bool
     */
    public function imList($where,$ord='',$p=15,$from=''){
        $im_page    =array();                           //需要返回的数据较多，定义一个容器
        $total      =$this ->where($where)-> count();   //统计总条数
        $per        =$p;                                //每页显示条数
        $page       =new \Component\Page($total,$per);  //实例化分页类对象
        //查询数据
        $_list=$this->where($where)->order($ord)->limit(substr( $page->limit,5))->select();
        foreach($_list as $k=>$v){           
            //会话人
            $_list[$k]['assign']        =$this->table("ft_user")->find($_list[$k]['assign_user_id']);
            $_list[$k]['assign_user']   =$_list[$k]['assign']['name'];                
            //接收人
            $_list[$k]['accept']        =$this->table("ft_user")->find($_list[$k]['accept_user_id']);
            $_list[$k]['accept_user']   =$_list[$k]['accept']['name']; 
            unset($_list[$k]['assign']);
            unset($_list[$k]['accept']);
        }
        $im_page['imList']  =$_list;
        if($from){
            $display = array(3,4, 5, 6, 7);
            $pageList =$page -> fpage($display);        //获得页码列表
        }else{
            $pageList =$page -> fpage();
        }
        $im_page['pageList']=$pageList;         //页码列表，放到数组中，前台使用
        if($im_page){
            return $im_page;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 删除指定会话 
     * 
     * @return bool
     */
    public function del($where){
        if($this->save($where)){
            $data=array(
                'user_id'   =>$where['log_name'],
                'content'   =>"删除了id为 ".$where['id']." 的会话",
                'add_time'  =>time()
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

