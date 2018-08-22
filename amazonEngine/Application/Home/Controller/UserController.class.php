<?php
/**
 * 用户管理类
 * 
 * @author Lee <a605333742@gmail.com>
 * @time 2016-04-26
 */
namespace Home\Controller;

class UserController extends AllowController{
    /*
     * 构造函数
     *
     * @return  #
     */
    public function _initialize(){       
        parent::_initialize();
        $this->db_user  =D('User');
        $this->im       =D('Im');
    }
    
    /*
     * 用户列表
     * 
     * @return #
     */
    public function userList(){
        $ids=$this->db_user->where(array("id"=>session("home.id")))->getField("friends_ids");
        if($ids){
            $where=array(
                'status'=>1,
                'id'    =>array("in",$ids)
            );
            $ord['section_id']  =desc;
            $userList   =$this->db_user->userList($where,$ord,5,1);
            $uList      =$userList['userList'];
            $pageList   =$userList['pageList'];
            //$enPage     =enPage($pageList);  
            $this->assign("pageList",$pageList);
            $this->assign("uList",$uList);
        }
        $this->display();
    }
    
    /*
     * 用户详情
     * 
     * @return #
     */
    public function info(){
       $where['status']     =1;        
        if(I("request.id",'','code')!=session("home.id")){
            $ids    =$this->db_user->where(array("id"=>session("home.id")))->getField("friends_ids");
            if($ids && strpos($ids,I("request.id",'','code'))){
                $this->assign('status',3);
            }else{
                $st =D("Friends")->where(array("my_id"=>session("home.id"),'you_id'=>I("request.id",'','code')))->find();
                if($st && $st['host']==I("request.id",'','code')){
                    $this->assign('status',2);
                }else{
                    $this->assign('status',1);
                }               
            }
            $where['id']    =I("request.id",'','code');
        }else{
            $where['id']    =session("home.id");
        }
        $userInfo       =$this->db_user->where($where)->find();
        $this->assign("userInfo",$userInfo);
        $this->display();
    }
    
    /*
     * 修改用户基本资料
     * 
     * @return #
     */
    public function editInfo(){
        if(I("request.tel")){
            $where['tel']   =I("request.tel");
        }
        if(I("request.email")){
            $where['email'] =I("request.email");
        }
        if(I("request.desc_")){
            if(session('lang')==1){
                $where['en_desc_']  =I("request.desc_");
            }else{
                $where['desc_'] =I("request.desc_");
            }            
        }
        $where['id']        =session("home.id");
        $code_id            =code(session("home.id"),1);
        if($this->db_user->edit($where)){
            redirect("info/id/".$code_id);
        }else{
           redirect("info/id/".$code_id);
        }
    }
    
    /*
     * 修改密码
     * 
     * @return @
     */
    public function checkPass(){
        $oldPass            =I("request.oldPass",'','md5');
        $where['password']  =I("request.newPass",'','md5');
        $where['id']        =session('home.id');
        $userInfo           =$this->db_user->find(session('home.id'));
        if($oldPass!==$userInfo['password']){
            echo 1;
        }
        if($this->db_user->save($where)){
            echo 2; 
        }else{
            echo 3;
        }
    }
    
    /*
     * 修改头像
     * 
     * @return #
     */
    public function editPhoto(){
        $id =code(session('home.id'),1);
        if($_FILES['big_photo']['name']){
            //编辑用户，如果头像更改，则删除原头像，减少空间占用
            $photo                      =$this->db_user->find(session('home.id'));
            $_SESSION['home']['image']  =$photo['image'];
            $config =array(
                'rootPath'  =>'./Application/Public/Upload/',                              //根目录
                'savePath'  =>'user/',                                            //保存路径
                'maxSize'   =>'902400',                                             //上传的文件大小限制 (0-不做限制)
                'exts'      =>array('jpg','png','gif','jpeg',"JPG","PNG","JPEG","GIF")    //允许上传的文件后缀
            );
            //附件被上传到路径：根目录/保存目录路径/创建日期目录
            $new_upload =new \Think\Upload($config);
            //uploadOne会返回已经上传的附件信息
            $upload     =$new_upload->uploadOne($_FILES['big_photo']);
            if(!$upload){
                 $this->error($new_upload->getError(),U("info"));
            }else{
                //拼装图片的路径名
                $upimg          =$upload['savepath'].$upload['savename'];
                $where['image'] =$upimg;
            }
            $where['id']    =session('home.id');
            if($this->db_user->save($where)){
                unlink('Application/Public/'.$_SESSION['home']['image']);
                $_SESSION["home"]['image']  =$where['image'];
               
                 redirect("info/id/".$id);
            }else{
                 redirect("info/id/".$id);
            }
        }else{
            redirect("info/id/".$id);
        }
    }
    
    /*
     * 留言
     * 
     * @return #
     */
    public function massage(){
        $where=array(
            "accept_user_id"=>I("request.accept_user_id"),
            "content"       =>I("request.content"),
            "add_time"      =>time(),
            'type'          =>3,
            'assign_user_id'=>session("home.id")
        );
        if($this->im->add($where)){
            $this->db_user->where(array("id"=>I("request.assign_user_id",'','code')))->setInc("im_num");
            $this->redirect("userList");
        }else{
            $this->redirect("userList");
        }
    }
    
    /*
     * 删除好友
     * 
     * @return #
     */
    public function delMyfried(){
        $myInfo =$this->db_user->find(session("home.id"));
        $myFrieds=  str_replace(I("request.uid"),"",$myInfo['friends_ids']);
        $where=array(
            "friends_ids"   =>$myFrieds,
            "id"            =>session("home.id")
        );
        if($this->db_user->save($where)){
            $this->redirect("userList");
        }else{
            $this->redirect("userList");
        }
    }
    
    /*
     * 加为好友
     * 
     * @return #
     */
    public function addFriend(){
       $where['accept_user_id'] =I("request.friend_id");
       $where['content']        =session("home.name")." 申请加你为好友";
       $where['type']           =2;
       $where['assign_user_id'] =session("home.id");
       $map['my_id']    =session("home.id");
       $map['you_id']   =I("request.friend_id");
       $map['host_id']  =I("request.friend_id");      
       if(D("Im")->add($where)){
           D("User")->where(array("id"=>I("request.friend_id")))->setInc("system_num");
           if(D("Friends")->add($map)){
               echo 1;
           }
       }else{
           echo 2;
       }
    }
}