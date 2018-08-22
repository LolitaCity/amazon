<?php
/**
 * 用户登录和退出管理
 * 
 * @author Lee <a605333742@gmail.com>
 * @time 2016-05-13
 */
namespace Admin\Controller;

class PublicController extends EmptyController{    
    /*
     * 验证登录信息
     * 
     * @return #
     */
    public function login(){
        if (!isset($_SESSION[C('USER_AUTH_KEY')])) {
            $this->display();
        } else {
            $this->redirect('Index/index');
        }
    }
    
    /*
     * 验证登录信息
     * 
     * @return #
     */
    public function checkLogin(){
        if(empty($_POST)){
            $this->redirect("login");
        }
        $map['name|tel|email']  =I("post.user_name","","trim");
        $map['status']          =1;
        $authInfo               =D("Admin")->where($map)->find();
        if(!$authInfo){
            $this->error("账号不存在或已禁用",U(C('USER_AUTH_GATEWAY')));
        }else{
            if($authInfo['password'] !=I('post.password',"",'md5')){
                $this->error("用户名或密码错误",U(C('USER_AUTH_GATEWAY')));
            }else{ 
                //show_bug($this->checkRole($authInfo['id']));exit;
                if(!$this->checkRole($authInfo['id'])){
                    $this->error("无登录权限");
                }
                //保存用户认证标记名称
                $_SESSION[C('USER_AUTH_KEY')]   =$authInfo['id'];
                $_SESSION['name']		=$authInfo['name'];
                $_SESSION['add_time']           =$authInfo['add_time'];
                $_SESSION['inc']                =$authInfo['inc']+1;
                $_SESSION['last_logintime']     =$authInfo['last_logintime'];   
                //session(array(C('USER_AUTH_KEY')=>$authInfo['id'],'expire'=>36000));
                //更新登录时间，登陆次数
                $user                   =M("Admin");
                $data['id']             =$authInfo['id'];
                $data['last_logintime'] =time();
                $user->where(array("id" =>$authInfo['id']))->setInc('inc');
                $user->save($data);
                self::addLogin();
                $this->redirect('Index/index'); 
            }                
        } 
    }
    
    /**
     * 修改自己的密码
     * 
     * @return #
     */
    public function changePwd(){
        if(!empty($_POST)){            
            $where=array(
                'id'            =>session("authId"),
                'password'      =>I("post.newPassword",'',"md5")
            );
            $userInfo   =D("Admin")->find(session("authId"));
            if($userInfo){
                if($userInfo['password']==I("post.oldPassword",'',"md5")){
                    if(D("Admin")->save($where)){
                        $this->success("密码修改成功");
                    }else{
                        $this->error("密码修改失败");
                    }
                }else{
                    $this->error("密码修改失败");
                }
            }            
        }else{
            $this->display();
        }
    }  
    
    /*
     * 退出系统
     * 
     * @return #
     */
    public function logout(){
       if (isset($_SESSION[C('USER_AUTH_KEY')])) {
            session(null);
            session_destroy();
            $this->redirect(C("USER_AUTH_GATEWAY"));
        } else {
            $this->error('已经登出！');
        }
    }
    
    /*
     * 验证码验证
     * 
     * @return #
     */
    function verifyImg(){
        //以下类Verify在之前并没有include引入
        //走自动加载Think.class.php  autoload()
        $config =array(
            'imageH'    =>24,       //验证码图片高度
            'imageW'    =>105,      //宽度
            'fontSize'  =>11,       //高度
            'useCurve'  =>false,    //曲线干扰
            'fontttf'   =>'8.ttf',  //验证码字体，不设置随机获取
            'length'    =>4,        //验证码位数
            'useCurve'  =>false,    // 是否画混淆曲线
            'useNoise'  =>false,    // 是否添加杂点
            'bg'        =>array(243, 251, 123),  // 背景颜色
            'codeSet'   =>'2345678',
            'fontSize'  =>15       //验证码字体大小(px)
        );
        $verify =new \Think\Verify($config);
        $verify -> entry();        
    }
    
    /*
     * 同步验证验证码
     * 
     * @return bool
     */
    function ajaxCode(){
        $verify=new \Think\Verify();
        if(!$verify ->check(I("post.captcha","","trim"))){
            echo 1;
        }
    }
    
    /*
     * 检查用户角色,角色有的权限
     * 
     * @param   $u_id 用户id，根据id查询角色
     * @param   $role_id    角色id，直接查询角色
     * 
     * @return  
     */
    public function checkRole($u_id='',$role_id=''){
        if($role_id==''){
            $role_id    =M("AdminRole")->where(array("u_id"=>$u_id))->getField("role_id");
        }
        $roleInfo       =M("Role")->find($role_id);
        if(!$roleInfo['status'] || $roleInfo['status']==2){
            return FALSE;
        }
        $node_ids   =M("RoleNode")->where(array("role_id"=>$roleInfo['id']))->field("node_id")->select();
        return $node_ids;
    }    
    
    /*
     * 权限检查
     * 
     * @param   $level  层级，存在表示查询二级目录
     * @param   $role_id角色id，查询该角色的权限    
     * 
     * @return #
     */
    public function ckeckAuth($level='',$role_id=''){ 
        if($level && session('nodeList_s')){
            return session('nodeList_s');
        }else if(($level=='' || $level==0) && session('nodeList_t')){
            return session("nodeList_t");
        }
        $nodes  =$this->checkRole(session("authId"),$role_id);        
        foreach($nodes as $vo){
            $node[]     =$vo['node_id'];
        }
        $map['id']      =array("in",implode(",",$node));
        $map['status']  =1;
        if($level==1){
            $map['level']   =$level;            //次级节点
        }else if($level==2){
            $map['level']   =array("lt",10);    //所有权限下的节点
        }else{
            $map['level']   =0;                 //顶级节点
        }
        $nodeList   =M("Node")->where($map)->order("sort_ asc")->select(); 
        if($level){
            session('nodeList_s',$nodeList);
        }else{
            session("nodeList_t",$nodeList);
        }
        return $nodeList;
    }
    
    /*
     * 系统信息
     * 
     * @return #
     */
    public function systemInfo(){
        $info = array(
            '操作系统'=>PHP_OS,
            '运行环境'=>$_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式'=>php_sapi_name(),
            'ThinkPHP版本'=>THINK_VERSION.' [ <a href="http://thinkphp.cn" target="_blank">查看最新版本</a> ]',
            '上传附件限制'=>ini_get('upload_max_filesize'),
            '执行时间限制'=>ini_get('max_execution_time').'秒',
            '服务器时间'=>date("Y年n月j日 H:i:s"),
            '北京时间'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
            '服务器域名/IP'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
            '剩余空间'=>round((@disk_free_space(".")/(1024*1024)),2).'M',
            'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
            'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
            'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
            );
        $this->assign('info',$info);
        $this->display();
    }
    
    /*
     * 添加登录日志信息
     * 
     * @return #
     */
    public function addLogin(){
        $obj            =new \Component\Fantem();
        $ipInfo         =$obj->ip2Area();
        $map['user_id'] =session("authId");
        $map['type']    =1;
        $map['ip']      =$obj->getIp();
        $map['addr']    =$ipInfo['province'].$ipInfo['city'];        
        D("Log")->add($map);
    }
    
    /*
     * 清除缓存
     * 
     * @return #
     */
    public function delCache(){
        deldir(CACHE_PATH);
        if(is_empty_dir(CACHE_PATH.'Admin') && is_empty_dir(CACHE_PATH.'Home')){
            A("Common")->addLog("清理","缓存");
            session('nodeList_s',null);
            session('nodeList_t',null);
            $this->success("缓存清理成功");
        }else{
            $this->error("缓存清理失败");
        }
    }
}

