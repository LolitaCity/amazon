<?php
/**
 * 节点管理
 * 
 * @author  Lee<a605333742@gmail.com>
 * @time    2016-08-11
 */
namespace Admin\Controller;

class NodeController extends CommonController{
    /*
     * 构造函数
     * 
     * @return #
     */
    public function _initialize() {
        parent::_initialize();
        $this->db   =D("Node");
    }
    
    /*
     * index前置操作
     * 
     * @return #
     */
    public function _before_index(){
        $map['status']  =1;
        $map['level']   =0;
        $topNoList      =$this->db->where($map)->select();
        $this->assign("topNoList",$topNoList);
   }
   
   /*
    * 节点列表
    * 
    * @return #
    */
    public function index(){
        A("Common")->index('','path',TRUE);
    }
   
   /*
    * show前值操作
    * 
    * @return #
    */
   public function _before_show(){
       $this->_before_index();
   }
   
   /*
     * ajax查询控制器名称并返回控制器
     * 
     * @return array $controlName
     */
    public function ajaxControl(){
        $ajaxP_id   =I("request.pid","","int");
        $controlName=$this->db->find($ajaxP_id);
        $controll   =$controlName['controller'];
        echo $controll;
    }
    
    /*
     * 新增节点操作
     * 
     * @return #
     */
    public function edit(){
        $roleModel      =D("RoleNode");
        $roleModel->startTrans();
        if(!I("request.id")){     
            $addNode    =A("Common")->insert();
            if(!empty($addNode['name'])){
                $this->error($addNode['name']);
            }
            //将新增的节点权限赋值给超级用户
            $map['node_id'] =$this->db->where(array("status"=>1))->max('id');
            $map['role_id'] =1;
            //拼接路径
            $path           =$this->changPath($map['node_id'],I("request.p_id"));
            $addRoleNode    =$roleModel->add($map);
            if($addNode && $addRoleNode && $path){
                $roleModel->commit();
                session('nodeList_s',null);
                session('nodeList_t',null);
                $this->success("节点新增成功,请刷新页面");
            }else{
                $roleModel->rollback();
                $this->error("节点新增失败");
            }
        }else{
            if(A("Common")->update()['name']=="节点名已经存在!"){
                $this->error('节点名已经存在!');
            }
            if($this->changPath(I("request.id"),I("request.p_id"),$sign=1)){
                $roleModel->commit();
                session('nodeList_s',null);
                session('nodeList_t',null);
                $this->success("节点编辑成功，请刷新页面");
            }else{
                $roleModel->rollback();
                $this->error('节点编辑失败');
            }
        }
    }
    
    /*
     * 删除节点后置操作
     * 
     * @return #
     */
    public function _before_del(){ 
        session('nodeList_s',null);
        session('nodeList_t',null);
    }
    
    /*
     * 
     * 路径修改
     * 
     * @param int $id 节点id
     * @param int $p_id 父节点id
     * @param int $sing 判断是否为修改
     * 
     * @return 
     */
    public function changPath($id,$p_id='0',$sign=''){
        //$sign判断是否为修改，如果为修改，查询是否修改路径
        if($sign!=''|| $sign!=NULL||$sign!=0){
            $pid    =$this->db->where(array('id'=>$id))->getField("p_id");            
            if($pid==$p_id || $p_id==''){
                return TRUE;
            }
        }
        if($p_id=='' || $p_id==0){
            //顶级节点
            $map['path']    =$id.'_';
        }else{
            $pPath          =$this->db->field('path')->find($p_id);
            $map['path']    =$pPath['path'].$id.'_';
        }
        $map['id']         =$id;
        $nowPath           =$this->db->save($map);
        return $nowPath;
    }
}