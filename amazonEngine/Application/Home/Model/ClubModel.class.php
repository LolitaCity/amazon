<?php
/**
 * 论坛模型
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-04-05
 */
namespace Home\Model;
use Think\Model;

class ClubModel extends Model{
    /*
     * 论坛列表
     * 
     * @param   $where  查询条件    
     * @param   $ord    排序
     * @param   $p      每页显示数量
     * @param   $from   来源，空表示为后台，有值表示为前台
     * 
     * @return array $club_page
     * @return bool  FALSES
     */
    public function clubList($where,$ord='',$p=15,$from=''){         
        $club_page  =array();                           //需要返回的数据较多，定义一个容器
        $total      =$this ->where($where)-> count();   //统计总条数
        $per        =$p;                                //每页显示条数
        $page       =new \Component\Page($total,$per);  //实例化分页类对象
        //查询数据
        $_list=$this->where($where)->order($ord)->limit(substr( $page->limit,5))->select();
        foreach($_list as $k=>$v){
            if($where['type']==1){                
                $_list[$k]['group_info']=$this->table('ft_club_group')->find($_list[$k]['group_id']);
                $_list[$k]['gname']     =$_list[$k]['group_info']['club_name'];
            }elseif ($where['type']==2){
                $_list[$k]['group_info']=$this->table("ft_section")->find($_list[$k]['group_id']);
                $_list[$k]['gname']     =$_list[$k]['group_info']['sec_name'];
            }else{
                $_list[$k]['gname']     ="EVENTS";
            }
            $_list[$k]['assign']        =$this->table("ft_user")->where($where_1)->find($_list[$k]['assign_user_id']);
            $_list[$k]['assign_user']   =$_list[$k]['assign']['name'];
            $_list[$k]['assign_photo']  =$_list[$k]['assign']['small_photo'];
            $_list[$k]['change']        =$this->table("ft_user")->where($where_1)->find($_list[$k]['assign_user_id']);
            $_list[$k]['change_user']   =$_list[$k]['change']['name'];
            unset($_list[$k]['group_info']);
            unset($_list[$k]['assign']);
            unset($_list[$k]['change']);
        }
        $club_page['clubList']  =$_list;
        if($from){
            $display = array(3,4, 5, 6, 7);
            $pageList =$page -> fpage($display);    //获得页码列表
        }else{
            $pageList =$page -> fpage();
        }
        
        $club_page['pageList']  =$pageList;         //页码列表，放到数组中，前台使用
        if($club_page){
            return $club_page;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 添加、编辑活动
     * 
     * @return bool;
     */
    public function editClub($where){
        $g='';
        if($where['id']){
            $where['change_time']   =time();
            if($this->save($where)){
                $g='修改';
            }else{
                return FALSE;
            }
        }else{
            $where['old_content']   =$where['content'];
            $where['add_time']      =time();
            if($this->add($where)){
                $g="新增";
            }else{
                return FALSE;
            }
        }
        $data=array(
            'user_id'   =>$where["log_name"],
            'content'   =>$g."了名为 ".$where['theme']." 的活动",
            'add_time'  =>time() 
        );
        if(M("Log")->add($data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 删除指定的活动
     * 
     * @return bool
     */
    public function del($where){
        if($this->save($where)){
            $data =array(
                'user_id'   =>$where['log_name'],
                'content'   =>'删除了名为 '.$where['theme']." 的活动",
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
