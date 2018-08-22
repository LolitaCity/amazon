<?php
/**
 * 俱乐部管理模型
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-06-01
 */
namespace Home\Model;
use Think\Model;

class ClubGroupModel extends Model{
    /*
     * 俱乐部列表
     * 
     * @return  array   $clubGList
     * @return  bool
     */
    public function clubGroupList($where,$ord='',$p=15){
        $clubGList  =array();                           //需要返回的数据较多，定义一个容器
        $total      =$this ->where($where)-> count();   //统计总条数
        $per        =$p;                                //每页显示条数
        $page       =new \Component\Page($total,$per);  //实例化分页类对象
        //查询数据
        $_list=$this->where($where)->order($ord)->limit(substr( $page->limit,5))->select();
        foreach($_list as $k=>$v){            
            //添加人
            $_list[$k]['assUser']       =$this->table('ft_user')->find($_list[$k]['assign_user_id']);
            $_list[$k]['assign_user']   =$_list[$k]['assUser']['name'];
            unset($_list[$k]['assUser']);
        }
        $clubGList['clubGList']         =$_list;
        $pageList =$page -> fpage();                    //获得页码列表
        $clubGList['pageList']          =$pageList;     //页码列表，放到数组中，前台使用
        if($clubGList){
            return $clubGList;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 编辑俱乐部
     * 
     * @return  bool
     */
    public function editGClub($where){
        $g="";
        if($where['id']){
            //id存在为修改
            if($this->save($where)){
                $g='修改';
            }else{
                return FALSE;
            }
        }else{
            //为新增
            $where['add_time']  =time();
            if($this->add($where)){
                $g='新增';                
            }else{
                return FALSE;
            }
        }
        $data=array(
            "user_id"   =>$where['log_name'],
            "content"   =>$g."了名称为 ".$where['club_name']." 的俱乐部",
            "add_time"  =>time()
        );
        if(M("Log")->add($data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 删除指定俱乐部
     * 
     * @return  #
     */
    public function del($where){
        if($this->save($where)){
            $data=array(
                "user_id"   =>$where['log_name'],
                "content"   =>"删除了名称为 ".$where['c_name']." 的俱乐部",
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

