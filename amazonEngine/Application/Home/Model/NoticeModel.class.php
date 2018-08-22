<?php
/**
 * 公告模型
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-03-22
 */
namespace Home\Model;
use Think\Model;

class NoticeModel extends Model{
    /*
     * 公告列表
     * 
     * @return array $notice_page;
     * @return bool FSLSE
     */
    public function showList($where,$ord='',$p=15,$from=''){
        $notice_page=array();                          //需要返回的数据太多，定义一个空数组做容器
        $total      =$this ->where($where)-> count();  //统计总条数
        $per        =$p;                               //每页显示条数        
        $page       =new \Component\Page($total,$per); //实例化分页类对象
        //查询数据
        $_list      =$this->where($where)->order($ord)->limit(substr( $page->limit,5))->select();
        if($from){
            $display = array(3,4, 5, 6, 7);
            $pageList =$page -> fpage($display);        //获得页码列表
        }else{
            $pageList =$page -> fpage();
        }
        
        $notice_page['pageList']    =$pageList;        //页码列表，放到数组中，前台使用
        /*根据地区部门编码转换为地区和部门*/
        foreach($_list as $k=>$v){
            $_list[$k]['section']       =D("Section")->find($_list[$k]['section_id']);
            $_list[$k]['area_section']  =$_list[$k]['section']['name'];
            $_list[$k]['user']          =D("User")->find($_list[$k]['user_id']);
            $_list[$k]['username']      =$_list[$k]['user']['name'];
            unset($_list[$k]['user']);
            //unset($_list[$k]['area']);
            unset($_list[$k]['section']);
        }        
        $notice_page['noticeList']      =$_list;          //公告列表，放到数组中，前台使用
        if($notice_page){
            return $notice_page;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 编辑公告，包括新增和修改
     * 
     * @return bool 
     */
    public function editNotice($where){
        $g=''; 
        if($where['id']){ 
            /*判断新增还是修改,id存在为修改*/
            if($where['is_top']){
                $lastTop        =$this->max("top");
                $where['top']   =$lastTop+1;
            }
            if($this->save($where)){
                $g='修改';
            }else{
                return FALSE;
            }
        }else{
           /*判断新增还是修改,id不存在为新增*/
            $where['add_time']      =time();            
            $where['old_content']   =$where['content'];
            $lastTop                =$this->max("top");
            $where['top']           =$lastTop+1;
            if($this->add($where)){
                $g="新增";
            }else{
                return FALSE;
            }            
        }        
        $data=array(
            'user_id'   =>$where['log_name'],
            'content'   =>$g.'了名称为 '.$where['title'].' 的公告',
            'add_time'  =>time()
        );
        if(M("Log")->add($data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 删除指定公告    
     * 说明：从程序的角度，任何数据都不允许物理删除，所谓的删除，只是不可见，在此我们采用is_show=0 为删除
     * 
     * @return bool
     */
    public function del($where){
        if($this->save($where)){
            $data1=array(
                "user_id"   =>$where['log_name'],
                "content"   =>"删除了标题为 ".$where['title']." 的公告",
                "add_time"  =>time()
            );
            if($this->table("ft_log")->add($data1)){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}
