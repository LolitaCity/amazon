<?php
/**
 * 常用工具函数，程序会自动加载
 * 
 * @author Lee  <a605333742@gmail.com>
 * @time    2016-07-20
 */

/*
 * 异位或加密解密
 * 
 * @param   $value  加密的字符
 * @param   $type   标志，true为加密，false为解密，默认为解密
 * 
 * $return  str
 */
function code($value,$type=''){
    $key    =md5(C('AUTO_LOGIN_KEY'));
    $md5str =md5(rand(1,100000));
    if($type){
        //加密
        return strtoupper(substr($md5str,0,6)).str_replace("=",'',base64_encode($value ^ $key)).strtoupper(substr($md5str,rand(0,31),1));
    }
    //解密
    $value	=base64_decode(substr(substr($value,6),0,strlen(substr($value,6))-1));
    return  $value^$key;
}

/*
 * 根据节点id获取节点名
 * 
 * @param   $node_id    节点id
 * 
 * @return  str $nodeName
 */
function getNodeName($node_id=''){
    if($node_id=="" ||$node_id==0){
        return "<div style='color:blue'>顶级节点</div>";
    }
    //if($nodeName=S("nodeName")){
    //    return $nodeName[$node_id];
    //}
    $node       =D("Node");
    $nodeList   =$node->where(array("status"=>1))->select(array('field' => 'id,name'));
    $data       =array();
    foreach($nodeList as $vo){
        $data[$vo['id']]=$vo['name'];
    }
    //S("nodeName",$data,3600*24);
    $nodeName   =$data[$node_id];    
    return $nodeName;
}

/*
 * 根据id查询用户角色
 * 
 * @param user_id   用户id
 * 
 * @return $rName
 */
function getAdminRole($user_id=''){
    if($user_id==''){
        return '';
    }
    $u_role =D("AdminRole");
    $role_id=$u_role->where(array("u_id"=>$user_id))->getField("role_id");
    $rName  =D("Role")->where(array("id"=>$role_id))->getField('name');
    return $rName;
}

/*
 * 获取管理员用户名
 * 
 * @param   $user_id 用户id
 * @param   $sing   获取用户名的类型（存在为获取用户名，不存在为获取昵称）
 * 
 * @return  $userName
 */
function getAdminname($user_id='',$sign=''){
    if($user_id==''){
        return "";
    }
    $user       =D("Admin");
    $userList   =$user->where(array("status"=>1))->select(array('field' => 'id,name,nickname'));
    $data       =array();
    foreach($userList as $vo){
        if($sign==''){
            $data[$vo['id']]    =$vo['name'];
        }else{
            $data[$vo['id']]    =$vo['nickname'];
        }
    }
    $userName   =$data[$user_id];
    return $userName;
}


/*
 * 根据id查询前台用户名
 * 
 * @param $user_id  用户的id
 * 
 * @return $userName
 */
function getUsername($user_id){
    if(!$user_id){
        return "匿名用户";
    }
    $data       =array();
    $userList   =D("User")->where(array("status"=>1))->select(array('field' => 'id,name'));
    foreach($userList as $vo){
        $data[$vo['id']]=$vo['name'];
    }
    $userName   =$data[$user_id];
    return $userName;
}
/*
 * 获取用户所属部门
 * 
 * @param   $user_id    用户id
 * 
 * @return  str secName
 */
function get_Admin_sec($user_id=''){
    if($user_id==''){
        return "";
    }
    $sec_id =D("Admin")->where(array("id"=>$user_id))->getField("section_id");
    $secName=getSection($sec_id);
    return $secName;
}

/*
 * 用户id转为入职日期
 * 
 * @param $user_id 用户id
 * 
 * @return  str $getJionDate
 */
function getJionDate($user_id=''){
    if($user_id==''){
        return "";
    }
    $userList   =D("Admin");
    $list       =$userList->select(array('field' => 'id,board_time'));
    $data       = array();
    foreach($list as $vo){
        $data[$vo['id']]=$vo['board_time'];
    }
    $username   =$data[$user_id];   
    return $username;
}

/*
 *根据用户id获取用户岗位
 * 
 * @param   $user_id    用户id
 * 
 * @return str  $PostionName岗位名称
 */
function getPostionName($user_id=''){
    if($user_id==''){
        return "";
    }
    $postionList=D("Admin")->where(array("status"=>1))->select(array('field' => 'id,position'));
    $data       =array();
    foreach($postionList as $vo){
        $data[$vo['id']]=$vo['position'];
    }
    $postionName   =$data[$user_id];
    return $postionName;
}

/*
 * 判断目录是否为空
 * 
 * @param	string	$ddir	指定目录
 * @param	bool	$path	删除指定目录还是删除指定目录下的所有子目录
 * 
 * @return  void
*/
function is_empty_dir($ddir){
    $d=opendir($ddir);
    $i=0;
    while($a=readdir($d)){
            $i++;
    }
    closedir($d);
    if($i>2){return false;}
    else {return true;}
}


/*
 * 递归删除目录
 * 
 * @param $dir 指定要删除的目录
 * 
 * @return #
 */
function deldir($dir) {
    $dh = opendir($dir);
    while ($file = readdir($dh)) {
        if ($file != "." && $file != "..") {
            $fullpath = $dir . "/" . $file;
            if (!is_dir($fullpath)) {
                unlink($fullpath);
            } else {
                deldir($fullpath);
            }
        }
    }
}

/*
 * 根据状态显示相应的图片
 * 
 * @return  #
 */
function getStatus($status, $imageShow = true){
    switch ($status) {
        case 0 :
           $showText = '删除';
            $showImg = '<IMG SRC="/amazonEngine/Application/Public/Admin/img/del.gif" style="height: 20px; width: 20px;" BORDER="0" ALT="删除">';
            break;
        case 1 :
            $showText = '正常';
            $showImg = '<IMG SRC="/Application/Public/Admin/img/ok.gif" style="height: 20px; width: 20px;" BORDER="0" ALT="正常">';
            break;
        case 2 : 
             $showText = '禁用';
            $showImg = '<IMG SRC="/Application/Public/Admin/img/locked.gif" style="height: 20px; width: 20px;" BORDER="0" ALT="禁用">';
        break;  
    }
    return ($imageShow === true) ? $showImg : $showText;
}

/*
 * 根据部门id查询部门
 * 
 * @param   $sec_id 部门id
 * 
 * @return  str $secName
 */
function getSection($sec_id=''){
    if($sec_id==''){
        return  "";
    }
    $section    =D("Section");
    $secList    =$section->where(array("status"=>1))->select(array("field"=>"id,name"));
    $data       =array();
    foreach ($secList as $vo){
        $data[$vo['id']]=$vo['name'];
    }
    $secName    =$data[$sec_id];
    return $secName;
}

/*
 * 根据group_id 和类型获取所属于哪个俱乐部或那个部门
 * 
 * @param   $group_id   所属id
 * @param   $type       类型
 * 
 * @return  srt $group
 */
function getGroup($group_id='',$type=''){
    if($type==1){
        $gClub  =D("ClubGroup")->where(array("status"=>1))->select(array("field"=>"id,name"));
        $data   =array();
        foreach($gClub as $vo){
            $data[$vo['id']]=$vo['name'];
        }
        $group  =$data[$group_id];
        return $group;
    }else if($type==2){
        $secList=D("Section")->where(array("status"=>1))->select(array("field"=>"id,name"));
        $data   =array();
        foreach($secList as $vo){
            $data[$vo['id']]=$vo['name'];
        }
        $group  =$data[$group_id];
        return $group;
    }else{
        return 'EVENT';
    }
}

/*
 * 根据id查出活动主题
 * 
 * @param   $club_id    活动id
 * 
 * @return  srt $theme
 */
function getClubTheme($club_id=''){
    if($club_id==''){
        return "";
    }
    $clubList   =D("Club")->where(array("status"=>1))->select(array("field"=>'id,theme'));
    $data       =array();
    foreach($clubList as $vo){
        $data[$vo['id']]=$vo['theme'];
    }
    $theme  =$data[$club_id];
    return $theme;
}

/*
 * 将考核期转为季度
 * 
 * @param   $time 考核时间
 * 
 * @return str  quarter
 */
function getQuarter($time=''){
    //return substr($time,5,2);
    if($time==''){
        return '';
    }
    if(substr($time,5,2)=='01'||substr($time,5,2)=='02'||substr($time,5,2)=='03'){
        return "第一季度考核";
    }
    if(substr($time,5,2)=='04'||substr($time,5,2)=='05'||substr($time,5,2)=='06'){
        return "第二季度考核";
    }
    if(substr($time,5,2)=='07'||substr($time,5,2)=='08'||substr($time,5,2)=='09'){
        return "第三季度考核";
    }
    if(substr($time,5,2)=='10'||substr($time,5,2)=='11'||substr($time,5,2)=='12'){
        return "第四季度考核";
    }
}

/*
 * 根据id查询资料模块
 * 
 * @param   $t_id   资料所属模块id
 * 
 * @return  str  $mod_name
 */
function getModname($t_id=''){
    if($t_id==''){
        return '';
    }
    $modList=D("TrainMod")->where(array("status"=>1))->select(array("file"=>"id,name"));
    $data   =array();
    foreach($modList as $vo){
        $data[$vo['id']]=$vo['name'];
    }
    $mod_name   =$data[$t_id];
    return $mod_name;
}

/*
 * 根据问题id查询问题名称
 * 
 * @param   $exa_id 为题id
 * 
 * @return str  $question
 */
function getQuestion($exa_id=''){
    if($exa_id==''){
        return '';
    }
    $exaList=D("Examen")->where(array("status"=>1))->select(array("file"=>"id,question"));
    $data   =array();
    foreach($exaList as $vo){
        $data[$vo['id']]=$vo['question'];
    }
    $question   =$data[$exa_id];
    return $question;
}

/*
 * 根据答案id获取答案名称
 * 
 * @param   $ans_id 答案id
 * 
 * @return  str $answer
 */
function getAnswer($ans_id=''){
    if($ans_id==''){
        return '';
    }
    $ansList=D("AnswerList")->where(array("status"=>1))->select(array('file'=>"id,answer"));
    $data   =array();
    foreach($ansList as $vo){
        $data[$vo['id']]=$vo['answer'];
    }
    $answer =$data[$ans_id];
    return $answer;
}

/*
 * 外部新闻链接
 * 
 * @param   $id 外部新闻id
 * 
 * @return
 */
function getUrl($id=''){    
    $id     =code($id);
    if(empty($id)){
        return "";
    }    
    $urlList=D("Notice");
    $list   =$urlList->select(array('field' => 'id,content'));
    $data   =array();
    foreach($list as $vo){
        $data[$vo['id']]=$vo['content'];
    }
    $url    =getText($data[$id],1);
    return $url;
}

/*
 * 获取纯文本内容
 * 
 * @param   $text   需要过滤的变量
 * 
 * @return  str $alltext;  
 */
function getText($text='',$type=''){
    if (empty($text)) {
            return '';
        }        
    $text = preg_replace("/<\/?[^>]+>/i", "", $text); 
    if(mb_strlen($text,'utf8')>60 && $type==''){
        //return substr($text,0,30)."......";
        return mbSubstr($text,60,0,"……");
    }else{
        return $text;
    }
}

/*
 * 截取中文字符串
 *
 * @param  string $str          要截取的中文字符串
 * @param  int $len             要截取的中文字符串长度
 * @param  int $startpos        起始位置
 * @param  string $after        截取后添加的内容，没有截取不添加
 * @param  string $encoding     字符串编码
 * @return string               截取后的字符串
 */
function mbSubstr( $str, $len, $startpos = 0 , $after = '...', $encoding = 'utf-8' ){
   $newStr = mb_substr( $str, $startpos, $len, $encoding );
   if( $newStr !== $str ){
       return $newStr . $after;
   }else{
       return $newStr;
   }
}

/*
 * 根据用户id拼接出的字符串统计人数
 * 
 * @return  int $num
 */
function getNum($club_id=''){
    if(empty($club_id)){
        return "";
    }
    $where['is_show']   =1;
    $club   =D("Club")->where($where)->select(array("field"=>"id,activity_ids"));
    $data   =array();
    foreach($club as $vo){
        $data[$vo['id']]=count(explode(",",$vo['activity_ids']))-1;
    }
    $num=$data[$club_id];
    return $num;
}

/*
 * 根据总分获取等级最终等级
 * 
 * @param   total_score 总分
 * 
 * @return  final_level
 */
function getFinalLevel($total_score){
    if($total_score==''){
        return '';
    }else if($total_score>=100){
        $final_level='A+';
    }else if($total_score>=95 && $total_score<100){
        $final_level='A';
    }else if($total_score>=80 && $total_score<95){
        $final_level='B';
    }else if($total_score>=70 && $total_score<80){
        $final_level='C';
    }else if($total_score>=60 && $total_score<70){
        $final_level='D';
    }else if($total_score<60){
        $final_level='E';
    }
    return $final_level;
}

/*
 * 根据用户id获取头像
 * 
 * @param   int $u_id
 * 
 * @return  str imageUrl;
 */
function getPhoto($u_id=''){
    if($u_id==''){
        return '';
    }
    $userList   =D("Admin")->where(array('status'=>1))->select(array("filed"=>"id,image"));
    $data       =array();
    foreach($userList as $vo){
        $data[$vo['id']]=$vo['image'];
    }
    $imageUrl   =$data[$u_id];
    return $imageUrl;
}

/*
 * 根据分数计算考核系数
 * 
 * @param   $total_score 总分
 * 
 * @return  srt $coefficient
 */
function coefficient($total_score=''){
    if($total_score==''){
        return '';
    }
    if($total_score==100){
        return 1.5;
    }else if($total_score<100 && $total_score>=95){
        return 1.2;
    }else if($total_score>=80 && $total_score<95){
        return 1;
    }else if($total_score>=70 && $total_score<80){
        return 0.8;
    }else if($total_score>=60 && $total_score<70){
        return 0.6;
    }else if($total_score<60){
        return 0;
    }
}





