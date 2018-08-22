<?php
/**
 * 权限认证分配
 * 
 * @author  Lee <a605333742@gmail.com>
 * @since   2015-11-1
 */
namespace Admin\Controller;
//引用分页类
use Think\Page;

class CommonController extends EmptyController{
    /*
     *构造函数
     * 
     * @return # 
     */
    public function _initialize(){
        //第一步：判断是否开启了权限认证；判断当前控制器是否不需要认证
        if(C("AUTH_ON") && !in_array(MODULE_NAME, explode(',', C('NOT_AUTH_MODULE')))){
            if(!I("post.authId")){
                if(!isset($_SESSION[C('USER_AUTH_KEY')])){
                    $this->redirect("Public/login");
                }
                //查询操作是否在所属组中，不在则没有权限
                if(CONTROLLER_NAME!='Index'){
                    $nodes  =A("Public")->ckeckAuth();
                    $data   =array();
                    foreach($nodes as $vo){
                        $data[] =$vo["controller"];
                    }
                    array_push($data,"Bak");
                    array_push($data,"Public");
                    if(!in_array(CONTROLLER_NAME,$data)){
                        $this->error("无权进行此操作");
                    }
                }
            }
        }
    }

    /*
     * 根据表单生成查询条件-进行列表过滤
     *
     * @param string $name 数据对象名称
     *
     * @return array
     */
    public function _search($name=''){
        //生成查询条件
        if (empty ($name)) {
            $name = CONTROLLER_NAME;
        }
        $model      =D( $name );        
        $map        =array ();
        $fieldArray =array();
        if ($model->getDbFields()){
            $fieldArray = $model->getDbFields();
        }
        if ($model->viewFields){
            foreach ( $model->viewFields as $key => $val ){
                $fieldArray =array_merge($fieldArray, $model->viewFields[$key]);
            }
            unset($fieldArray['_on']);
        }
        //show_bug($_REQUEST [$val]);exit;
        foreach ( $fieldArray as $key => $val ) {				
            if (isset ( $_REQUEST [$val] ) && $_REQUEST [$val] != ''){
                //特别指定一些字段进行模糊查询
                $likeArray = array(
                    'name',
                    'title',
                    'username',
                    'nickname',
                    'remark',
                    'content',
                    'desc_',
                    'controller',
                    'action',
                    'result',
                    'add_time',
                    'email',
                    'reseller_company_name',
                    'reseller_amazon_name',
                    'reseller_contact_name',
                    'reseller_contact_email',
                    'reseller_name',
                    'product_name',
                    'product_url'
                );
                if (in_array($val, $likeArray)){
                    //模糊查询
                    $map [$val] = array('like', '%'.trim($_REQUEST [$val]).'%');
                } else {
                    //精确查询
                    $map [$val] = $_REQUEST [$val];
                }
            }
        }
        return $map;
    }
    
    /*
     * 根据表单生成查询条件-进行列表过滤
     *
     * @param object 	$model 		数据对象
     * @param array 	$map 		过滤条
     * @param string 	$sortBy 	排序
     * @param boolean 	$asc 		是否正序
     * @param string 	$countPk 	主键
     * @param string 	$field 		提取字段
     *
     * @return array    #
     */
    public function _list($model, $map, $sortBy='',$asc =TRUE, $countPk="id", $field="*") {
        //验证传参的有效性
        if (empty($asc)){
            $asc = false;
        }
        if (isset($model->pk)) {
            $countPk = $model->pk;
        }
        if (empty($countPk)){
            $countPk = "id";
        }
        //排序字段 默认为主键名
        $orderSign  =TRUE;
        if (!empty ( $_REQUEST ['_order'] )) {
            $order  =$_REQUEST ['_order'];
        } else {
            if(!empty($sortBy)){
                $order  =$sortBy;
                if(is_array($sortBy)){
                    $orderSign  =FALSE;
                }
            }else{
                $order  =$countPk;
            }
        }
        //排序方式默认按照倒序排列
        //接受 sort参数 0 表示倒序 非0都 表示正序
        if($orderSign){
            if (!empty ( $_REQUEST ['_sort'] )) {
                $sort = $_REQUEST ['_sort'] == 'asc' ? 'asc' : 'desc'; //zhanghuihua@msn.com
            }else{
                $sort = $asc? 'asc' : 'desc';
            }
        }
        //取得满足条件的记录数
        $count = $model->where ( $map )->count ();
        if ($count > 0) {
            //创建分页对象            
            if($_REQUEST['numPerPage']){
                $_REQUEST ['listRows']=I("request.numPerPage");
            }
            if (! empty ( $_REQUEST ['listRows'] )) {
                    $listRows   =$_REQUEST ['listRows'];
            } else {
                    $listRows   =20;
            }
            $p = new Page ( $count, $listRows );
            //分页查询数据
            if($orderSign){
                $voList = $model->where($map)->group($countPk)->field($field)->order("`" .$order ."` ". $sort)->limit($p->firstRow . ',' . $p->listRows)->select ( ); 
            }else{
                $voList = $model->where($map)->group($countPk)->field($field)->order($order)->limit($p->firstRow . ',' . $p->listRows)->select ( ); 
            }
            //return $model->getLastSql();
            //show_bug($model->getLastSql());
            //show_bug($voList);exit;
            //exit;
            //分页跳转的时候保证查询条件
            foreach ( $map as $key => $val ) {
                if (! is_array ( $val )) {
                    $p->parameter .= "$key=" . urlencode ( $val ) . "&";
                }
            }
            //分页显示
            $page = $p->show ();
            //列表排序显示
            $sortImg = $sort; //排序图标
            $sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
            $sort = $sort == 'desc' ? 1 : 0; //排序方式
            //模板赋值显示
            $this->assign ( 'list', $voList );
            $this->assign ( 'sort', $sort );
            $this->assign ( 'order', $order );
            $this->assign ( 'sortImg', $sortImg );
            $this->assign ( 'sortType', $sortAlt );
            $this->assign ( "page", $page );
        }
        //zhanghuihua@msn.com
        $this->assign ( 'totalCount', $count );
        $this->assign ( 'numPerPage', $p->listRows );
        $this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);
        
    }  
    
    /*
     * 显示首页也
     * 
     * @return #
     */
    public function index($db='',$sort='id',$sortBy=FALSE){
        $map = $this->_search($db);
        if (method_exists($this, '_filter')) {
            $this->_filter($map);
        }
        $map['status']  =array("neq",0);
        if($db==""){
            $model      =D(CONTROLLER_NAME);
        }else{
            $model      =D($db);  
        }
        if (!empty ($model)) {
            $this->_list($model, $map,$sort,$sortBy);
        }
        $this->display(ACTION_NAME);
        return;
    }
    
    /*
     * 显示新增编辑页面
     * 
     * @return #
     */
    public function show(){
        if(I("request.sign")){
            $this->assign("_sign",I("request.sign",'','code'));
        }
        if(I("request.id")){
            if(!I("request.db")){
                $model  =D(CONTROLLER_NAME);
            }else{
                $model  =D(I("request.db",'','code'));  
            }
            $id = $_REQUEST [$model->getPk()];
            if(!is_numeric($id)){
                $id=code($id);
            }
            $vo = $model->getById($id);
            $this->assign('vo', $vo);
        }
        $this->display();
    }    
    
    /*
     * 编辑数据
     * 
     * @return # 
     */
    public function edit(){
        if(I("request.id")){
            if($this->update()){
                $this->success("修改成功");
            }else{
                if(!empty($this->update()['name'])){
                    $this->error($this->update()['name']);
                }else{
                    $this->error("修改失败");
                }
            }
        }else{
            if($this->insert()=="true"){
                $this->success("新增成功");
            }else{
                if(!empty($this->insert()['name'])){
                    $this->error($this->insert()['name']);
                }else{
                    $this->error("新增失败");
                }
            }
        }
    }    
    
    /*
     * 新增数据
     * 
     * @return #
     */
    function insert(){ 
        if(!I("request.db")){
            $model  =D(CONTROLLER_NAME);
        }else{ 
            $model  =D(I("request.db"));
        }  
        $field  =$model->getDbFields();
        if(in_array("sort_",$field) &&($_POST['sort_']==''|| $_POST['sort_']==0)){
            $top            =$model->where(array("status"=>1))->max("sort_");
            $_POST['sort_'] =$top+1;
        }
        if (false === $model->create()) {
            return $model->getError();
        }        
        //保存当前数据对象
        $list = $model->add();
        if ($list !== false) {
            $dbName =I("request.db")?I("request.db"):CONTROLLER_NAME;
            if($this->addLog("新增",I("request.name"),$dbName)){
                return TRUE;
            }else{
                return FALSE;
            }
        } else {
            return FALSE;
        }
    }
    
    /*
     * 更新数据
     * 
     * $return #
     */
    function update(){
        if(!I("request.db")){
            $model  =D(CONTROLLER_NAME);
        }else{
            $model  =D(I("request.db"));  
        }
        $dbName =I("request.db")?I("request.db"):CONTROLLER_NAME;        
        $model->startTrans();
        //判断是否有图片存在，标签图片是否更改
        $imageIcon  =$model->getById(I("request.id"));
        $condition  =array("id"=>I("request.id"));
        
        if(!$this->invaImages($model, $condition, $dbName)){
            $model->rollback();
            return FALSE;
        }
        if (false === $model->create()) {
            return $model->getError();
        }        
        // 更新数据
        //var_dump($model->create());exit;
        $list = $model->save();
        if (false !== $list) {            
            if($this->addLog("修改",I("request.name"),$dbName)){
                unlink('Application/Public/Upload/'.session("oldImg"));
                if(session("oldSmallImg")){
                    unlink('Application/Public/Upload/'.session("oldSmallImg"));
                }
                $model->commit();
                return TRUE;
            }else{
                $model->rollback();
                return FALSE;
            }
        } else {
            $model->rollback();
            return FALSE;
        }
    }
    
    /*
     * 新增日志信息
     * 
     * @return #
     */
    public function addLog($g='',$name='',$db=''){        
        if(is_array($name)){
            $name   =implode(",",$name);
        }
        $data       =array(
            'user_id'   =>session("authId"),
            'db'        =>$db,
            'content'   =>$g." 了 ".$name
        );
        if(M("Log")->add($data)){
            return TRUE;
        }else{
            return FALSE;
        }
    }  
    
    /*
     * 删除数据
     * 
     * @return #
     */
    public function del(){
        //删除指定记录
        if(!I("request.db")){
            $model  =D(CONTROLLER_NAME);
        }else{
            $model  =D(I("request.db","","code"));  
        }
        $model->startTrans();
        if (!empty ($model)) {
            $pk = $model->getPk();
            $id =$_REQUEST [$pk];
            if(!is_array($id)){                
                $id = explode(",",code($id));
            }           
            if (isset ($id)) {
                $condition  =array($pk => array('in', implode(',', $id)));
                $list       =$model->where($condition)->setField('status',0);
                if($list==FALSE){
                    $model->rollback();
                }
                $logName    =$this->getLogName($id,$model);
                if($logName==FALSE){
                    $model->rollback();
                }
                $dbName     =I("request.db","","code")?I("request.db","","code"):CONTROLLER_NAME;
                if($this->invaImages($model,$condition,$dbName)==FALSE){
                    $model->rollback();
                    $this->error("参数错误，删除失败");
                }              
                if ($list !== false && $this->addLog("删除",$logName,$dbName)) {
                    $model->commit();
                    $this->success('删除成功！');
                } else {
                    $model->rollback();
                    $this->error('删除失败！');
                }
            }else{
                $model->rollback();
                $this->error('非法操作');
            }
        }
    }
    
    /*
     * 彻底删除记录（一般情况下不要使用）
     * 
     * @return bool
     */
    public function delete(){
        //删除指定记录
        if(!I("request.db")){
            $model  =D(CONTROLLER_NAME);
        }else{
            $model  =D(I("request.db","","code"));  
        }
        if (!empty ($model)) {
            $pk     =$model->getPk();
            $id     =$_REQUEST [$pk];
            if(!is_array($id)){                
                $id = explode(",",code($id));
            }
            if (isset ($id)) {
                $condition  =array($pk => array('in', implode(',', $id)));
                if (false !== $model->where($condition)->delete()) {
                    $dbName =I("request.db","","code")?I("request.db","","code"):CONTROLLER_NAME;
                    if($dbName=="Invaimg"){
                        //如果操作的是清理图片表，那么就要执行清理
                        $imgList=$model->where($condition)->select();
                        $imgPath=array();
                        foreach($imgList as $vo){
                            $imgPath[]  =$vo['imagepath'];
                        }
                        if($imgPath!=''){
                            for($i=0;$i<=count($imgPath);$i++){
                                unlink('Application/Public/Upload/'.$imgPath[$i]);
                            }
                        }
                    }
                    $this->success('删除成功！');
                } else {
                    $this->error('删除失败！');
                }
            }else{
                $this->error('非法操作');
            }
        }
    }
    
    /*
     * 图片上传
     * 
     * @return #
     */
    public function upImg(){
        if(!empty($_FILES['images']['name'])){
            if(I("request.ajax")==1){
                $rootPath   ='title/';
            }else if(I("request.ajax")==2){
                $rootPath   ='top/';
            }else if(I("request.ajax")==3){
                $rootPath   ='section/';
            }else if(I("request.ajax")==4){
                $rootPath   ='user/';
            }else if(I("request.ajax")==5){
                $rootPath   ='club/';
            }else if(I("request.ajax")==6){
                $rootPath   ='data/';
            }else if(I("request.ajax")==7){
                $rootPath   ='mod/';
            }else if(I("request.ajax")==8){
                $rootPath   ="club_img/";
            }else if(I("request.ajax")==9){
                $rootPath   ="answer/";
            }else{
                $rootPath   ='common/';
            }
            $config =array(
                'rootPath'  =>'./Application/Public/Upload/',       //根目录
                'savePath'  =>$rootPath,                            //保存路径
                'maxSize'   =>'0',                                  //上传的文件大小限制 (0-不做限制)
                'exts'      =>array('ico','jpg','png','gif','jpeg','doc','docx','xls','xlsx','pdf','txt','ppt','pptx') //允许上传的文件后缀
            );
            //附件被上传到路径：根目录/保存目录路径/创建日期目录
            $new_upload =new \Think\Upload($config);
            //uploadOne会返回已经上传的附件信息
            $upload     =$new_upload->uploadOne($_FILES['images']);
            if(!$upload){
                echo $new_upload->getError();
            }else{                 
                $exts   =array("jpg","jpeg","png","gif","JPG","JPEG",'PNG','GIF');
                if(in_array(pathinfo($upload['savename'], PATHINFO_EXTENSION),$exts)){
                    //把已经上传好的图片制作缩略图Image.class.php
                    $image  =new \Think\Image();
                    //open();打开图像资源，通过路径名找到图像
                    $srcimg =$new_upload->rootPath.$upload['savepath'].$upload['savename'];
                    $image->open($srcimg);
                    //制作80*80 的缩略图                             
                    $s          =$image->thumb(100,100);  //按照比例缩小
                    $smallimg   =$upload['savepath']."small_".$upload['savename'];
                    $image->save($new_upload->rootPath.$smallimg); 
                    echo ($upload['savepath'].$upload['savename']);
                }
            }           
        }
    }
    
    /*
     * 更改状态
     * 
     * @return #
     */
    public function changeStatus(){ 
        if(!I("request.db")){
            $model  =D(CONTROLLER_NAME);
        }else{
            $model  =D(I("request.db","","code"));  
        }
        if (!empty ($model)) {
            $pk = $model->getPk();
            $id =$_REQUEST [$pk];
            if(!is_array($id)){                
                $id = explode(",",code($id));
            } 
            if (isset ($id)) {
                $condition  =array($pk => array('in', implode(',', $id)));
                $list       =$model->where($condition)->setField('status',(int)I("request.status","","code"));
               
               
                $dbName =I("request.db","","code")?I("request.db","","code"):CONTROLLER_NAME;
                $logName=$this->getLogName($id,$model);
                if ($list !== false && $this->addLog("修改",$logName,$dbName)) {
                    $this->success('状态修改成功！');
                } else {
                    $this->error('状态修改失败！');
                }
            } else {
                $this->error('非法操作');
            }
        }
    }
    
    /*
     * 将模糊的字段名转换为id字符串列
     * 
     * @return str  $ids
     */
    public function getIds($field='',$name='name',$db='',$val='id'){        
        $map[$name]     =array('like',"%".trim($field)."%");
        $map['status']  =array('neq',0);
        $db             =D($db);
        $arr            =$db->where($map)->select();
        $arrIds         =array();
        foreach($arr as $vo){
            $arrIds[]   =$vo[$val];             
        }            
        $ids=array("in",implode(',', $arrIds));
        return $ids;
    } 
    
    /*
     * 获取被删除数据的名称
     * 
     * @param   $id  array()    被删除的数据id
     * @param   $db             被删除的数据所在的数据库
     * 
     * @return $logName 
     */
    public function getLogName($id,$db){
        $delNames   =$db->where(array("id"=>array("IN",implode(',',$id))))->select(array("field"=>'name'));
        $arrNames   =array();
        foreach($delNames as $vo){
            $arrNames[] =$vo['name'];
        }
        $logName    =implode(',', $arrNames);
        return $logName;
    }
    
    /*
     * 修改和删除数据时，如果有图片，则把图片存在过期图片表中
     * 
     * @return #
     */
    public function invaImages($model,$condition,$dbName){
        $imaPath    =array();
        $smallImg   =array();
        //查询被删除的数据中省份存在图片，如果存在图片，则把图片路径提出来，以便将来清理
        $delList=$model->where($condition)->select();
        foreach($delList as $vo){
            if($vo['image']!=''){
                $imaPath[]  =$vo['image'];
            }else if($vo['img']!=''){
                $imaPath[]  =$vo['img'];
            }else if($vo['icon']!=''){
                $imaPath[]  =$vo['icon'];
            }else if($vo['photo']!=''){
                $imaPath[]  =$vo['photo'];
            }else if($vo['logo']!=''){
                $imaPath[]  =$vo['logo'];
            }
            if($vo['small_image']!=''){
                $smallImg[] =$vo['small_image'];
            }else if($vo['small_img']!=''){
                $smallImg[] =$vo['small_img'];
            }else if($vo['small_photo']!=''){
                $smallImg[] =$vo['small_photo'];
            }
        }                
        $imaPath    =array_merge($imaPath,$smallImg);
        if($imaPath[0]!=NULL || $imaPath[0]!=''){
            $invaImg    =D("Invaimg");
            for($i=0;$i<count($imaPath);$i++){
                $map["db_name"]     =$dbName;
                $map['imagepath']   =$imaPath[$i];
                if(!$invaImg->add($map)){
                    return FALSE;
                    break;
                }
            }
            return TRUE;
        }
        return TRUE;
    }
}