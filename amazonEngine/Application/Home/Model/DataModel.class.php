<?php
/**
 * 资料库模型
 * 
 * @author  Lee <a605333742@gmail.com>
 * @time    2016-04-07
 */
namespace Home\Model;
use Think\Model;

class DataModel extends Model{
    /*
     * 资料列表
     * 
     * @return array  $data_page
     * @return bool   FALSE
     */
    public function dataList($where,$ord='',$p=13,$from=''){
        $data_page  =array();                               //需要返回的数据较多，定义一个容器
        $total      =$this ->where($where)-> count();       //统计总条数
        $per        =$p;                                    //每页显示条数
        $page       =new \Component\Page($total,$per);      //实例化分页类对象
        //查询数据
        $_list=$this->where($where)->order($ord)->limit(substr( $page->limit,5))->select(); 
        foreach($_list as $k=>$v){
            /*查询所属模块名*/
            $_list[$k]['tmodel']        =$this->table('ft_train_mod')->find($_list[$k]['t_id']);
            $_list[$k]['t_name']        =$_list[$k]['tmodel']['name'];
            /*end*/
            //上传人
            $_list[$k]['assign']        =$this->table("ft_user")->find($_list[$k]['assign_user_id']);
            $_list[$k]['assign_user']   =$_list[$k]['assign']['name'];                
            //修改人
            if($_list[$k]['change_user_id']){
                $changeName[$k]         =$this->table("ft_user")->field("name")->find($_list[$k]['change_user_id']);
                $_list[$k]['change_user']=$changeName[$k]['name'];
                unset( $changeName[$k]);
            }
            unset($_list[$k]['assign']);
            unset($_list[$k]['tmodel']);
        }
        $data_page['dataList']  =$_list;
        if($from){
            $display = array(3,4, 5, 6, 7);
            $pageList =$page -> fpage($display);    //获得页码列表
        }else{
            $pageList =$page -> fpage();
        }
        $data_page['pageList']  =$pageList;     //页码列表，放到数组中，前台使用
        if($data_page){
            return $data_page;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 编辑资料
     * 
     * @return bool
     */
    public function editData($where){
        $g='';
        if($where['id']){
            //说明为修改
            if($where['top_sign']){
                $lastTop        =$this->max("top");
                $where['top']   =$lastTop+1;
            }
            if($this->save($where)){
                $g='修改';
            }else{
                return FALSE;
            }
        }else{
            //为新增
            $where['add_time']      =time();
            $lastTop                =$this->max("top");
            $where['top']           =$lastTop+1;
            if($this->add($where)){
                $g="新增";
            }else{
                return FALSE;
            }
        }
        $data =array(
            'user_id'   =>$where['log_name'],
            'content'   =>$g."了名称为 ".$where['title']." 的资料",
            'add_time'  =>time()
        );
        if(M("Log")->add($data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    /*
     * 删除指定资料
     * 
     * @return bool
     */
    public function del($where){
        if($this->save($where)){
            $data =array(
                'user_id'   =>$where['log_name'],
                'content'   =>"删除了名为 ".$where['title']." 的资料",
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

