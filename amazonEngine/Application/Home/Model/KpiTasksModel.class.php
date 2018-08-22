<?php
/**
 * kpi绩效考核之关联任务考核管理
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-05-23
 */
namespace Home\Model;
use Think\Model;

class KpiTasksModel extends Model{
    /*
     * kpi绩效考核之关联任务考核列表
     * 
     * @return array $kTList
     * @return bool FALSE
     */
    public function kpiTaskList($where,$ord='id'){
        $kTList=$this->where($where)->order($ord)->select();
        if($kTList){
            return $kTList;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 编辑kpi绩效考核之关联任务考核
     * 
     * @return bool 
     */
    public function editKTasks($where){
        $g='';
        if($where['id']){ 
            /*判断新增还是修改,id存在为修改*/            
            if($this->save($where)){
                $g='修改';
            }else{
                return FALSE;
            }
        }else{
           /*判断新增还是修改,id不存在为新增*/
            if($this->add($where)){
                $g="新增";
            }else{
                return FALSE;
            }            
        }        
        $data=array(
            'user_id'   =>$where['log_name'],
            'content'   =>$g.'了名为 '.$where['subjects'].' 的任务考核',
            'add_time'  =>time()
        );
        if(M("Log")->add($data)){
            return TRUE;
        }else{
            return FALSE;
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
                'content'   =>"删除了名为 ".$where['subjects']." 的任务考核",
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