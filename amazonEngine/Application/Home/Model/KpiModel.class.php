<?php
/**
 * kpi绩效考核模型
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-05-23
 */
namespace Home\Model;
use Think\Model;

class KpiModel extends Model{
    /*
     * 绩效考核列表
     * 
     * @return array $kpi_page
     * @return bool FALSE
     */
    public function kpiList($where,$ord='',$p=15,$from=''){
        $kpi_page   =array();                           //需要返回的数据较多，定义一个容器
        $total      =$this ->where($where)-> count();   //统计总条数
        $per        =$p;                                //每页显示条数
        $page       =new \Component\Page($total,$per);  //实例化分页类对象
        //查询数据
        $_list=$this->where($where)->order($ord)->limit(substr( $page->limit,5))->select();
        foreach($_list as $k=>$v){            
            //被考核人
            $_list[$k]['accUser']       =$this->table('ft_user')->find($_list[$k]['accept_id']);
            $_list[$k]['accept_user']   =$_list[$k]['accUser']['name'];
            //被考核人的所属地区和部门
            $_list[$k]['area']          =$this->table('ft_area')->find($_list[$k]['area_id']);
            $_list[$k]['section']       =$this->table('ft_section')->find($_list[$k]['section_id']);
            $_list[$k]['area_section']  =$_list[$k]['area']['area_name'].' '.$_list[$k]['section']['sec_name'];
            //岗位
            $_list[$k]['position']      =$_list[$k]['accUser']['position'];
            //审核人
            $_list[$k]['assUser']       =$this->table('ft_user')->field("name")->find($_list[$k]['assign_id']);
            $_list[$k]['assign_user']   =$_list[$k]['assUser']['name'];
            unset($_list[$k]['assUser']);
            unset($_list[$k]['accUser']);
            unset($_list[$k]['area']);
            unset($_list[$k]['section']);
        }
        $kpi_page['kpiList']            =$_list;
         if($from){
            $display = array(3,4, 5, 6, 7);
            $pageList =$page -> fpage($display);        //获得页码列表
        }else{
            $pageList =$page -> fpage();
        }       
        $kpi_page['pageList']           =$pageList;     //页码列表，放到数组中，前台使用
        if($kpi_page){
            return $kpi_page;
        }else{
            return FALSE;
        } 
    }
    
    /*
     * 新增编辑kpi
     * 
     * @return #
     */
    public function editKpi($where){
        $g='';
        if($where['id']){
            if($this->save($where)){
                $g="修改";
                $date=date("Y:m:d H:i",$where['endtime']);
            }else{
                return FALSE;
            }
        }else{
            if($this->add($where)){
                $g="新增";
            }else{
                return FALSE;
            }
        }        
        $data=array(
            "user_id"   =>$where['log_name'],
            "content"   =>$g.'了考核人为 '.$where['accUser']."时间为".$date."的kpi",
            'add_time'  =>time()
        );
        if(M("Log")->add($data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 删除指定kpi考核
     * 
     * @return bool
     */
    public function del($where){
        if($this->save($where)){
            $data=array(
                'user_id'   =>$where['log_name'],
                'content'   =>"删除了用户名为 ".$where['accept_user']." 添加时间为 ".$where['addtime']." 的kpi考核",
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