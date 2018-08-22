<?php
/**
 * 部门模型
 * 显示部门信息，编辑指定部门信息，新增部门，删除指定部门
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    3016-03-23
 */
namespace Home\Model;
use Think\Model;

class SectionModel extends Model{
    /*
     * 显示所有部门信息
     * 
     * @return array $section_list
     * @return bool  FALSE
     */
    public function sectionList($where,$ord='',$p=9,$from=''){ 
        $sec_page   =array();                                //需要返回的数据较多，定义一个容器
        $total      =$this ->where($where)-> count();        //统计总条数
        $per        =$p;                                     //每页显示条数
        $page       =new \Component\Page($total,$per);       //实例化分页类对象        
        //查询数据
        $_list=$this->where($where)->order($ord)->limit(substr( $page->limit,5))->select();
        $sec_page['secList']    =$_list;
        if($from){
            $display = array(3,4, 5, 6, 7);
            $pageList =$page -> fpage($display);        //获得页码列表
        }else{
            $display = array(0, 1, 2, 3, 4, 5, 6, 7);
            $pageList =$page -> fpage($display);
        }
        $sec_page['pageList']   =$pageList;                  //页码列表，放到数组中，前台使用
        if($sec_page){
            return $sec_page;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 新增、修改地区
     * 
     * @return bool
     */
    public function sectionEdit($where){
        $g='';
        if($where['id']){
            /*有id说明为修改*/
            if($this->save($where)){
                $g='修改';
            }else{
            return FALSE;
            }
        }else{
            /*没有id，说明为新增*/
            $where['add_time'] =time();
            if($this->add($where)){
                $g="新增";
            }else{
                return FALSE;
            }
        }
        $data=array(
            'user_id'   =>$where['log_name'],
            'content'   =>$g.'名称为 '.$where['sec_name']." 的区域",
            'add_time'  =>time()
        );
        if(M("Log")->add($data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
     
    /*
     * 删除指定部门
     * 
     * @return bool
     */
    public function del($where){
        if($this->save($where)){
            $data=array(
                'user_id'   =>$where['log_name'],
                'content'   =>"删除了名为 ".$where['sec_name'].' 的区域',
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
