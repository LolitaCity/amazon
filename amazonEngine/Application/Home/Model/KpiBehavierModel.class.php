<?php
/**
 * kpi绩效考核之关联行为考核管理
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-05-23
 */
namespace Home\Model;
use Think\Model;

class KpiBehavierModel extends Model{
    /*
     * kpi绩效考核之关联行为考核列表
     * 
     * @return array $kBehavierList
     * @return bool FALSE
     */
    public function kBehavier($where,$ord){
        $kBehavierList  =$this->where($where)->order($ord)->select();
        if($kBehavierList){
            return $kBehavierList;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 编辑kpi绩效考核之关联任务考核
     * 
     * @return bool 
     */
    public function editKBehavier($where){
        if($where['id']){ 
            /*判断新增还是修改,id存在为修改*/            
            if($this->save($where)){
                return TRUE;
            }else{
                return FALSE;
            }
        }else{
           /*判断新增还是修改,id不存在为新增*/
            if($this->add($where)){
                return TRUE;
            }else{
                return FALSE;
            }            
        } 
    }
    
    /*
     * 删除指定任务考核
     * 
     * @return bool
     */
    public function del($where){
        if($this->save($where)){
            $data =array(
                'user_id'   =>$where['log_name'],
                'content'   =>"删除了id为 ".$where['id']." 的行为考核",
                'add_time'  =>time() 
            );
            if(M("Log")->add($data)){
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }
}
