<?php
/**
 * 后台公共类
 * 
 * @author Ansion <1572309495@qq.com>
 * @since  2015-6-8
 */
namespace Component;
use Think\Controller;
//导入自定义工具类
use \Component\Filter;
use \Component\Tool;
//导入权限检查
use \Org\Util\Rbac;

class BackendCommon extends Controller
{
    public $db;
    public $adminInfo;

    /**
     * 构造函数
     *
     */
    public function _initialize() { //_initialize()等同于__construct(){parent::__construct();}	
        //强制页面最新-在配置文件也有配置，可不加，这里只是特别说明
        /*header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");*/		
        //权限检查
        /*if(!isset($_SESSION[C('USER_AUTH_KEY')])) {
                $publicloop = Tool::request('publicloop');
                if (!$publicloop) {
                        $this->redirect('Index/login',array('publicloop'=>1));
                }
        }*/        
    }
	
    /*
     * 上传图片文件
     * 
     * @param string    $formName   #表单名称
     * @param string    $beforDir   #上传目录-以id为目录分割的前半部分目录，用英文逗号隔开
     * @param string    $afterDir   #上传目录-以id为目录分割的后半部分目录
     * @param string    $fileName   #不含后缀的文件名
     * @param int       $id         #操作的记录id
     * @param json 	$thumbJson  #储存了要裁剪的json数组
     * @param int 	$type       #上传类型，1为编辑框，2为表单，默认为编辑框
     * 
     * @return string
     */
    public function uploadImg($formName = "filedata", $beforDir=NULL, $afterDir=NULL, $fileName=NULL, $id=NULL, $thumbJson=NULL, $type=1){        $this->curl = new Curl();
        //判断表单域名称
        if ($formName=='filedata'){
            $formNameTemp = Tool::request('formName', 'trim');
            if (!empty($formNameTemp)){
                $formName = $formNameTemp;
            }
        }
        //判断id来源
        if (empty($id)){
            $id = Tool::request('id');
            if (empty($id) && $type==1){
                //编辑框错误提示
                $data = array();
                $data['err'] = iconv("GB2312","UTF-8//IGNORE", '参数错误！');
                $data['msg'] = "";
                echo json_encode($data);
                exit;
            } elseif (empty($id)) {
                $this->error("上传参数错误！");
                exit;
            }
        }
        //判断前置目录
        if (empty($beforDir)){
            $beforDir = Tool::request('beforDir');
        }
        //判断后置目录
        if (empty($afterDir)){
            $afterDir = Tool::request('afterDir');
        }
        //判断文件名来源
        if (empty($fileName)){
            $fileName = Tool::request('fileName');
        }
        if (empty($fileName)){
            $fileName = date('YmdHis');
        }
        //判断是否存在裁剪json
        if (empty($thumbJson)){
            $thumbJson = Tool::request('thumbJson');
        }
        $this->curl->maxSize    = '1024000'; //允许单张图片大小的最大值(1m)
        $this->curl->allowTypes = array( //允许上传的文件的mime类型
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/x-png',
            'image/gif'
        );
        $this->curl->allowExts  = array( //允许上传图片的后缀
            'jpg',
            'jpeg',
            'gif',
            'png',
            'PNG',
            'JPEG',
            'GIF',
            'JPG'
        );
        	
        $this->curl->savePath           ="{$beforDir},{$id},{$afterDir}"; //保存图片的路径
        $this->curl->isAutoCreateYMDir  =TRUE; //是否在指定的文件夹内创建以年月命名的二级文件夹 true是创建 false就不创建
        $this->curl->saveRule           =""; //图片文件名的保存规则 留空则以YmdHis命名 可以传uniqid time这样不带参数的函数
        $this->curl->formFileName       = formName; //html中的上传文件的name值
        if (!empty($fileName)) {
            $this->curl->customName     =$fileName;//自定义文件名，不含文件扩展名
        }
        $imageArr                       =$this->curl->upload(); //返回的是数组 array('status'=>1, data=>'', 'info'=>'')
        //判断是否为编辑框上传
        if ($type==1){
            $retrunData = array();
            //编辑框上传
            if ($imageArr['status'] == 1) {
                $retrunData['err'] = '';
                $fileUrl = "/" . $imageArr['data']['imgDir'] . $imageArr['data']['imgName'];
                $fileUrl = Tool::imagesReplace($fileUrl);
                $retrunData['msg'] = $fileUrl;
            } else {
                //上传失败
                $retrunData['err'] = $imageArr['info'];
                $retrunData['msg'] = "";
            }
            echo json_encode($retrunData);
            exit;
        }else{
            //文本框上传
            if ($imageArr['status'] == 1) {
                $imgName = $imageArr['data']['imgName'];
            } else {
                $imgName = '';
            }
            return $imgName;
        }
    }
    /*
     * 上传非图片文件
     * 
     * @param string    $formName 	#表单名称
     * @param string    $beforDir 	#上传目录-以id为目录分割的前半部分目录，用英文逗号隔开
     * @param string    $afterDir 	#上传目录-以id为目录分割的后半部分目录
     * @param string    $fileName 	#不含后缀的文件名
     * @param int       $id 		#操作的记录id
     * @param string    $tableName      #表名称，默认为user表
     * @param int 	$type 		#上传类型，1为编辑框，2为表单，默认为编辑框
     * 
     * @return string
     */
    public function uploadFile($formName = "filedata", $beforDir=NULL, $afterDir=NULL, $fileName=NULL, $id=NULL, $type=1){
        $this->curl = new Curl();
        //判断表单域名称
        if ($formName=='filedata'){
            $formNameTemp = Tool::request('formName', 'trim');
            if (!empty($formNameTemp)){
                $formName = $formNameTemp;
            }
        }
        //判断id来源
        if (empty($id)){
            $id = Tool::request('id');
            if (empty($id) && $type==1){
                //编辑框错误提示
                $data = array();
                $data['err'] = iconv("GB2312","UTF-8//IGNORE", '参数错误！');
                $data['msg'] = "";
                echo json_encode($data);
                exit;
            } elseif (empty($id)) {
                $this->error("上传参数错误！");
                exit;
            }
        }
        //判断前置目录
        if (empty($beforDir)){
            $beforDir = Tool::request('beforDir');
        }
        //判断后置目录
        if (empty($afterDir)){
            $afterDir = Tool::request('afterDir');
        }
        //判断文件名来源
        if (empty($fileName)){
            $fileName = Tool::request('fileName');
        }
        if (empty($fileName)){
            $fileName = date('YmdHis');
        }

        $this->curl->maxSize    = '20971520'; //允许文件大小20M        
        $this->curl->allowTypes = array( //允许上传的文件的mime类型
            'application/pdf',
            'text/plain',
            'application/msword',
            'video/x-msvideo',
            'application/zip',
            'application/x-shockwave-flash',
            'video/x-msvideo'
        );
        $this->curl->allowExts  = array( //允许上传文件的后缀
            'pdf',
            'txt',
            'word',
            'avi',
            'zip',
            'rar',
            'swf',
        );        	
        $this->curl->savePath           = "{$beforDir},{$id},{$afterDir}"; //保存图片的路径
        $this->curl->isAutoCreateYMDir  = false; //是否在指定的文件夹内创建以年月命名的二级文件夹 true是创建 false就不创建
        $this->curl->saveRule           = ""; //图片文件名的保存规则 留空则以YmdHis命名 可以传uniqid time这样不带参数的函数
        $this->curl->formFileName       = $formName; //html中的上传文件的name值
        if (!empty($fileName)) {
            $this->curl->customName     = $fileName;//自定义文件名，不含文件扩展名
        }
        $dataArr                        = $this->curl->upload(); //返回的是数组 array('status'=>1, data=>'', 'info'=>'')
		/*array(3) {
		  ["status"] => string(1) "1"
		  ["info"] => string(18) "上传成功！"
		  ["data"] => array(2) {
			["imgDir"] => string(31) "dyfiles/devices/1/content/"
			["imgName"] => string(21) "20150630164234364.txt"
		  }
		}*/
		//判断是否为编辑框上传
        if ($type==1){
            $retrunData = array();
            //编辑框上传
            if ($dataArr['status'] == 1) {
                $retrunData['err'] = '';
                $fileUrl = "/" . $dataArr['data']['imgDir'] . $dataArr['data']['imgName'];
                $fileUrl = Tool::imagesReplace($fileUrl);
                $retrunData['msg'] = $fileUrl;
            } else {
                //上传失败
                $retrunData['err'] = $dataArr['info'];
                $retrunData['msg'] = "";
            }
            echo json_encode($retrunData);
            exit;
        } else {
            //文本框上传
            if ($dataArr['status'] == 1) {
                $filePath = $dataArr['data']['imgName'];
            } else {
                $filePath = '';
            }
            return $filePath;
        }
    }
    /*
     * 上传Base64位数据流
     * 
     * @param string    $imgData 	#Base64数据流
     * @param string    $beforDir 	#上传目录-以id为目录分割的前半部分目录，用英文逗号隔开
     * @param string    $afterDir 	#上传目录-以id为目录分割的后半部分目录
     * @param string    $fileName 	#不含后缀的文件名
     * @param int 	$id 		#操作的记录id
     * @param json 	$thumbJson 	#储存了要裁剪的json数组
     * @param string    $tableName 	#表名称，默认为user表
     *
     * @return json
     */
    public function uploadFileBase64($imgData=NULL, $beforDir=NULL, $afterDir=NULL, $fileName=NULL, $id=NULL, $thumbJson=NULL, $tableName="User")
    {
        //判断id来源
        if (empty($id)){
            $id = Tool::request('id');
        }
        //判断要操作的表名称
        if ($tableName=="User"){
            $tableNameTemp = Tool::request('tableName', 'trim');
            if (!empty($tableNameTemp)){
                $tableName = $tableNameTemp;
            }
        }
        if (empty($id)){
            $id = M($tableName)->order("id desc")->getField("id");
            if ($id){
                $id++;
            } else {
                $id = 1;
            }
        }
        //判断前置目录
        if (empty($beforDir)){
            $beforDir = Tool::request('beforDir');
        }
        //判断后置目录
        if (empty($afterDir)){
            $afterDir = Tool::request('afterDir');
        }
        //判断流来源
        if (empty($imgData)){
            $imgData = Tool::request('imgData');
        }
        //判断文件名来源
        if (empty($fileName)){
            $fileName = Tool::request('fileName');
        }
        if (empty($fileName)){
            $fileName = date('YmdHis');
        }
        //判断是否存在裁剪json
        if (empty($thumbJson)){
            $thumbJson = Tool::request('thumbJson');
        }
        //将base64解析成图片
        $tempArr = explode('base64,',$imgData);

        if (!in_array($tempArr[0], array('data:image/jpeg;', 'data:image/png;', 'data:image/pjpeg;', 'data:image/x-png;', 'data:image/gif;'))){
            $data['status'] = 0;
            $data['info']   = '格式不正确';
            echo json_encode($data);
            exit;
        }
        if (strpos("hd".$tempArr[0],"png")){
            $suffix = "png";
        } elseif (strpos("hd".$tempArr[0],"jpeg")){
            $suffix = "jpg";
        } elseif (strpos("hd".$tempArr[0],"gif")){
            $suffix = "gif";
        }
        //必须把空格替换掉(imgStr:Base64数据)
        $imgStr = str_replace(' ','',$tempArr[1]);
        $imgDir = "{$beforDir},{$id},{$afterDir}"; //保存文件的路径 多个以英文逗号隔开
        //组装文件名
        $imgName = $fileName.".".$suffix;
        $result = Tool::uploadBase64($imgStr, $imgDir, $imgName, $thumbJson);
        if ($result['status']==1){
            $data['status'] = 1;
            $data['info']   = $result["info"];
            $data['url']    = Tool::imagesReplace("/".$result['data']['imgName']);		
        } else {
            $data['status'] = 0;
            $data['info']   = $result["info"];
        }
        echo json_encode($data);
        exit;
    }
    /*
     * 删除远程文件
     * 
     * @param string $fileOrDirArray #要删除的文件或目录二维数组，格式："/dyimages/goods/$id",相对于files.booogo.com目录
     * 
     * @return string
     */
    public function deleteFile($fileOrDirArray)
    {
        if (empty($fileOrDirArray)){
            return false;
        }
        $this->curl = new Curl();
        $this->curl->unlinks = $fileOrDirArray;
        $result = $this->curl->unlink();
        return $result;
    }
    /*
     * DWZ成功回调刷新函数
     *
     * @param string 	$info		#消息提示
     * @param string    $forwardUrl	#刷新页面地址
     * @param string  	$navTabId
     * @param boolean  	$isClose	#是否关闭
     * @param string  	$rel
     *
     * @return array
     */
    public function dwzSuccess($info, $forwardUrl, $navTabId, $isClose=true, $rel=NULL){
        $data['status'] 		= 1;
        $data['info'] 			= $info;
        $data['navTabId'] 		= $navTabId;
        $data['rel'] 			= $rel;
        if ($isClose){
            $data['callbackType'] 	= "closeCurrent";
        }
        $data['forwardUrl'] 	= $forwardUrl;
        echo json_encode($data);
        exit;
    }
    /*
     * 系统日志记录方法
     * 
     * @param array $array #数组
     * 
     * @return string
     */
    public function addLog($array){
        if (empty($array)) {
            return false;
        }
        $array['addTime'] 	= time();
        $array['uId']		= $this->adminInfo['id'];
        $rs = M("LogBackend")->data($array)->add();
        if ($rs) {
            return true;
        } else {
            return false;
        }
    }
}
?>