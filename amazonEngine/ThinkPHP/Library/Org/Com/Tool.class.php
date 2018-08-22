<?php
/**
 * 常用工具类
 */
namespace Org\Com;

class Tool{
    /*
     * tool类构造函数
     */
    public function _initialize(){}
    
    /*
     * 获取指定数组打乱
     *
     * @param array  $array 要处理的数组
     * @param string $num   返回数量
     *
     * @return array 处理后的数组
     */
    public static function getRandArray($array, $num){
        $arrcount = count($array);
        if (!$arrcount) {
            return '';
        }
        if ($arrcount < $num) {
            $num = $arrcount;
        }
        $keyarray = array_keys($array);
        shuffle($keyarray);

        for ($i = 0; $i < $num; $i ++) {
            if ($num == 1) {
                $newarray [$i] = $array [$keyarray[0]];
            } else {
                $newarray [$i] = $array [$keyarray [$i]];
            }
        }
        return $newarray;
    }
    
    /*
     * 异步获取数据
     *
     * @param string $url 数据源链接
     *
     * @return string html代码
     */
    public static function ajaxProcess($url){
        echo '<script type="text/javascript" src="http://js1.fuyuesoft.com/js/jquery-1.8.3.min.js"></script>';
        echo '<script type="text/javascript">';
        echo '$.ajax({
                url:"'.$url.'",
                success : function(data){/*alert(data)*/}
            });
        ';
        echo '</script>';
    }
    
    /*
     * 进行json编码
     *
     * @param array $array 要编码的数组
     *
     * @return json 编码后的数据
     */
    public static function jsonEncode($array){
        return json_encode($array);
    }

    /*
     * 解码json字符串
     *
     * @param json $json 要解码的json数据
     *
     * @return array 解码后的数据
     */
    public static function jsonDecode($json){
        return json_decode($json, true);
    }
	
    /*
     * 去掉html标签
     *
     * @param string $str 要处理内容
     *
     * @return string 处理后内容	备注：用PHP自带方法string strip_tags(string str);也可去掉
     */
    public static function html2text($str){
        $str = strip_tags($str);
        $str = trim($str);
        $str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU", "", $str);
        $alltext = "";
        $start = 1;
        for ($i=0; $i<strlen($str); $i++) {
            if ($start==0 && $str[$i]==">") {
                $start = 1;
            } elseif ($start==1) {
                if ($str[$i]=="<") {
                    $start = 0;
                    $alltext .= " ";
                } elseif (ord($str[$i])>31) {
                    $alltext .= $str[$i];
                }
            }
        }
        $alltext = str_replace(" ", "", $alltext);
        $alltext = preg_replace("/&([^;&]*)(;|&)/", "", $alltext);
        $alltext = preg_replace("/[ ]+/s", " ", $alltext);
        return $alltext;
    }
    
    /*
     * 去掉html中的a标签
     *
     * @param string $str 要处理内容
     *
     * @return string 处理后内容
     */
    public static function delLink($str){
        return preg_replace('/\>\><a.+?>*<\/a>/','',$str);
    }

    /*
     * 代替file_get_contents
     * 
     * @param string $strUrl 获取的url内容
     *
     * @return string 所指向链接的内容
     */
    public static function url_get_contents($strUrl){
        $strUrl = self::httpBuildUrl($strUrl);
        $ch = curl_init($strUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_REFERER']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        //curl_setopt($ch, CURLOPT_PROXY, "192.168.11.45:80");218调试 改为自己的ip即可
        $response = curl_exec($ch);
        if (curl_errno($ch) != 0) {
            return false;
        }
        curl_close($ch);
        return $response;
    }
	
    /*
     * 对于请求的url添加一个cookies的参数
     * 
     * @param string $strUrl 获取的url内容
     *
     * @return string 所指向链接的内容
     */
    public static function httpBuildUrl($strUrl){
        $parseUrlArr   = parse_url($strUrl);
        //检测域名
        if (strpos($parseUrlArr['host'], "fuyuesoft.com") == false) {
            return $strUrl;
        }
        $queryArr      = explode("&", $parseUrlArr['query']);
        $queryBuildArr = array();
        foreach ($queryArr as $key => $value) {
            $valueArr                          = explode("=", $value);
            $queryBuildArr[trim($valueArr[0])] = trim($valueArr[1]);
        }
        $queryBuildArr['userSelectCity'] = $_COOKIE["userSelectCity"];
        $queryBuildArr['errorUrl'] = $_SERVER['REQUEST_URI'];
        $queryBuildStr             = http_build_query($queryBuildArr);
        $strUrl = $parseUrlArr['scheme']."://".$parseUrlArr['host'].$parseUrlArr['path']."?".$queryBuildStr;
        return $strUrl;
    }
	
    /*
     * 判断空值(数字0不为空)
     *
     * @param string $str 字符串
     *
     * @return string
     */
    public static function isEmpty ($str){
        if (!empty($str)) {
            return false;
        } else {
            if ($str===0) {
                return false;
            } else {
                return true;
            }
        }
    }
	
    /*
     * 获取当前页面url
     *
     * @param boolean $urlEncode 是否需要进行url编码
     *
     * @return string
     */
    public static function getCurrentURL($urlEncode = false){
        $currentURL = '';
        $currentURL = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on') ? 'https' : 'http';
        $currentURL .= '://';
        if ($_SERVER["SERVER_PORT"] != "80") {
            $currentURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
        } else {
            $currentURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
        }
        if ($urlEncode) {
            $currentURL = urlencode($currentURL);
        }
        return $currentURL;
    }
	
    /*
     * 年周数,格式：201312
     *
     * @param string $time 时间 
     *
     * @return string
     */
    public static function getWeekNum($time){
        $weekNum = strftime("%Y%V", $time);
        return $weekNum;
    } 
    
    /*
     * 格式化时间和日期(时间戳转为时间)
     * 
     * @return string
     */
    function toDate($time, $format = 'Y-m-d H:i:s'){
        if (empty ( $time )) {
            return '';
        }
        $format = str_replace ( '#', ':', $format );
        return date ($format, $time );
    }
    
    /*
     * 将时间转为时间戳
     * 
     * @return str $strtoTime
     */
    public function strTime($time){
        $strtoTime  =strtotime($time);
        return $strtoTime;
    }
	
    /*
     * 处理时间
     * 规则说明：<60分钟显示分钟,<24小时显示小时,<7天显示天,其他显示月日
     *
     * @param int $time unixstamp时间 
     * @param int $type 类型,控制>7天的显示格式
     *
     * @return string
     */
    public static function processTime($time, $type=0){
        $timeString = time()-$time;
        if ($timeString>60*60) {
            if ($timeString>24*60*60) {
                if ($timeString>7*24*60*60) {
                    switch ($type) {
                    case 0:
                        return date('Y-m-d H:i', $time);
                        break;
                    case 1:
                        //咨询首页使用
                        return date('m-d H:i', $time);
                        break;
                    }
                } else {
                    return ceil($timeString/(24*60*60)).'天前';
                }  
            } else {
                return ceil($timeString/(60*60)).'小时前';
            }
            
        } else {
            if ($timeString/60 < 1) {
                return "1分钟前";
            } else {
                return ceil($timeString/60).'分钟前';
            }
        }
    }
	
    /*
     * 集成信息数组并加密为cookie
     *
     * @param string $name      cookie名称
     * @param array  $params    信息数组
     * @param int    $cacheTime 缓存时间
     *
     * @return # 
     */
    public static function setArrayCookie($name, $params, $cacheTime=86400){
        $params 	= self::jsonEncode($params);
        $cookieStr 	= self::authcode($params, 'ENCODE', 'fe01356c504a07d4');
        $path = C( 'COOKIE_PATH' );
        setcookie($name, $cookieStr, time()+$cacheTime, $path );
    }
	
    /*
     * 解密集成信息数组cookie
     *
     * @param string $name cookie名称
     *
     * @return array 
     */
    public static function getArrayCookie($name){
        if (!empty($_COOKIE[$name])) {
            $info = self::authcode($_COOKIE[$name], 'DECODE', 'fe01356c504a07d4');
            if (!empty($info)) {
                $arrayCookie = self::jsonDecode($info);
                return $arrayCookie;
            }
            return '';
        } else {
            return false;
        }
    }
	
    /*
     * 集成信息数组并加密为session
     *
     * @param string $name      session名称
     * @param array  $params    信息数组
     *
     * @return # 
     */
    public static function setArraySession($name, $params){
        $params = self::jsonEncode($params);
		$_SESSION[$name] = $params;
    }
	
    /*
     * 解密集成信息数组session
     *
     * @param string $name session名称
     *
     * @return array 
     */
    public static function getArraySession($name){
        $tempVar = $_SESSION[$name];
        if (!empty($tempVar)) {
            $arraySession = self::jsonDecode($tempVar);
            return $arraySession;
        } else {
            return false;
        }
    }
    /*
     * 加密与解码函数
     *
     * @param string $string    加密的字符
     * @param string $operation 加密或解密
     * @param string $key       key值
     *
     * @return 加密或解密数据
     */
    public static function authcode($string, $operation, $key = ''){
        $key = md5($key ? $key : md5('fe01356c504a07d4'.$_SERVER['HTTP_USER_AGENT']));
        $key_length = strlen($key);

        $string = $operation == 'DECODE' ? base64_decode($string) : substr(md5($string.$key), 0, 8).$string;
        $string_length = strlen($string);

        $rndkey = $box = array();
        $result = '';
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($key[$i % $key_length]);
            $box[$i] = $i;
        }
        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }
        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }
        if ($operation == 'DECODE') {
            if (substr($result, 0, 8) == substr(md5(substr($result, 8).$key), 0, 8)) {
                return substr($result, 8);
            } else {
                return '';
            }
        } else {
            return str_replace('=', '', base64_encode($result));
        }
    }
    
    /*
     * 检测浏览器的cookies是否禁用
     * 
     * @return #
     */
    public function checkCookies(){
        setcookie('checkCookies', '1', time()+30, '/', '.fuyuesoft.com');
        if (!isset($_COOKIE['checkcookies'])) {
            $vars = base64_encode("tip=系统检测到浏览器禁用了cookies 请手动开启浏览器的cookies功能");
            header("Location:http://web.fuyuesoft.com/?a=error&vars={$vars}");
            exit;
        }
        unset($_COOKIE['checkCookies']);
    }
	    
    /*
     * 使用curl的post方式获取数据
     *
     * @param String  $url     请求地址
     * @param Array   $data    请求参数
     * @param Integer $timeout 超时时间（秒）
     *
     * @return mix
     */
    public static function file_post_contents($url, $data = array(), $timeout = 5)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);  //设置访问路径
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // 将结果缓冲，不立刻输出
        curl_setopt($ch, CURLOPT_POST, 1);   //是否为post方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //	post 数据

        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
		
    }
    /*
     * 使用curl获取数据
     *
     * @param String  	$url     	请求地址
     * @param boolean    $isHttps 	是否https请求
     * @param String  	$method  	请求类型
     * @param Array   	$data    	请求参数
     * @param Integer 	$timeout 	超时时间（秒）
     *
     * @return mix
     */
    public static function getHttpContent($url,$isHttps = false,$method = 'GET', $data = array(),$timeout = 5){  
        $data = '';  
        if (!empty($url)) {  
            try {  
                $ch = curl_init();  
                curl_setopt($ch, CURLOPT_URL, $url); //设置访问路径 
                curl_setopt($ch, CURLOPT_HEADER, false);  
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 将结果缓冲，不立刻输出
                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); //30秒超时  
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
                //如果是https请求，不验证证书和
                if($isHttps){
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);// https请求 不验证证书和hosts
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                }
                //如果是post请求
                if (strtoupper($method) == 'POST') {  
                    $curlPost = is_array($postData) ? http_build_query($postData) : $postData;  
                    curl_setopt($ch, CURLOPT_POST, 1);  //是否为post方式
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);  //post 数据
                }  
                $data = curl_exec($ch);  
                curl_close($ch);  
            } catch (Exception $e) {  
                $data = null;  
            }  
        }  
        return $data;  
    } 
    
    /*
     * 获取GET、POST的参数，并可选择是否进行过滤
     * 
     * @param string $variable 变量名
     * @param array  $filter   指定过滤规则:int,css,js,tag,text,mysql,char,trim（注意顺序）
     * @param string $default  指定为空时的默认值
     * @param string $type     指定选择GET/POST,空时优先选择POST
     * 
     * @return #
     */
    static public function request($variable, $filter=array(), $default='', $type=''){
        if ($type=='POST') {
            $value = isset($_POST[$variable])?$_POST[$variable]:null;
        } elseif ($type=='GET') {
            $value = isset($_GET[$variable])?$_GET[$variable]:null;
        } elseif ($type=='') {
            $value = isset($_POST[$variable])?$_POST[$variable]:(isset($_GET[$variable])?$_GET[$variable]:null);
        }
        //默认值处理,字符串0和数字0都转为数字0
        if ($value==='0'||$value===0) {
            $value = 0;
        } elseif (empty($value)) {
            if ($default==='0'||$default===0) {
                $value = 0;
            } elseif ($default===null) {
                $value = null;
            } elseif ($default===array()) {
                $value = array();
            } else {
                $value = $default;
            }
        }
        //如果是ASCII编码不用过滤直接返回
        if (mb_detect_encoding($value)=="ASCII"){
                return $value;
        }
        //过滤数据并返回
        $value = Filter::clean($value, $filter);
        return $value;
    }

    
    /*
     * 获取内容里的图片
     *
     * @param string  $text        #内容
     * @param boolean $isStripHost #是否去掉图片链接里的域名
     *
     * @return array #数组  
     */  
    public static function getTextImgUrl($text, $isStripHost = true){
        $text = stripslashes($text);//去掉反斜杠
        
        $matches = array();
        preg_match_all("/<img.*\>/isU", $text, $matches);
        $matches = $matches[0];
        
        $imglist = array();
        if (is_array($matches) && !empty($matches)) {             
            $pattern2 = "#src=('|\")(.*)('|\")#isU";//正则表达式
            foreach ($matches as $key=>$value) {
                $imgarr = array();
                preg_match_all($pattern2, $value, $imgarr);
                
                $url = $imgarr[2][0];
                if (isset($url) && !empty($url)) {
                    $urlArr = array();
                    if (!empty($isStripHost)) {
                        $url = str_replace("\\", "/", $url);
                        $urlArr = split("/", $url);
                        unset($urlArr[0], $urlArr[1], $urlArr[2]);
                        $url = implode("/", $urlArr);
                    }
                    $imglist[] = $url;
                }
                
            }
            $imglist = array_values($imglist);
        }
        return $imglist;
    }
	
    /*
     * 获取客户端ip
     *
     * @return #
     */
    public static function getip(){
        $ip = false;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }

        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = false;
            }

            for ($i = 0; $i<count($ips); $i++) {
                if (!eregi("^(10|172\.16|127\.0|192\.168)\.", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }
	
    /*
     * 此函数完成带汉字的字符串取串
     *
     * @param string $inputstr  输入的字符串
     *
     * @param string $mylen 长度
     *
     * @param string $laterStr 如果没有超出总长度，结尾附加字符串，默认为…(省略号)
     *
     * @return #
     */
    public static function substr_cn($inputstr,$mylen, $laterStr='…'){                                                                                                                                        
        $len=strlen($inputstr);
        $content='';
        $count=0;
        for($i=0;$i<$len;$i++){
           if(ord(substr($inputstr,$i,1))>127){
                $content.=substr($inputstr,$i,2);
                $i++;
           }else{
                $content.=substr($inputstr,$i,1);
           }
           if(++$count==$mylen){
                break;
           }
        }
        if ($len > $mylen) {
                return $content.$laterStr;
        }
        return $content;
    }
	
    /*
     * 根据ip获取ip归属地区信息
     *
     * @param string $ip  输入的ip
     *
     * @return #
     */
    public static function ip2Area($ip='58.63.88.59'){
        $ip2 = self::getip();
        if ($ip2=='127.0.0.1') {
            $ip = '14.147.145.12'; //广州
        }else{
            $ip = $ip2;
        }
        $url = "http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
        $data = file_get_contents($url);
        $obj = json_decode($data);
        $code = $obj->code;
        if ($code != 0) {
            //没有查到默认返回广州地区的信息
            return array(
                'id' => '440100',
                'province' => '广东',
                'city' => '广州',
                'area' => '华南',
            );
        }
        $obj2 = $obj->data;
        $province = $obj2->region;
        $city = $obj2->city;
        $country = $obj2->county;
        $area = $obj2->area;
        $isp = $obj2->isp;

        $county_id = $obj2->county_id;
        $city_id = $obj2->city_id;
        $province_id = $obj2->province_id;
        if ($county_id>0) {
                $areaid = $county_id;
        } elseif($city_id>0) {
                $areaid = $city_id;
        } else {
                $areaid = $province_id;
        }
        $province = str_replace('省', "", $province);
        $province = str_replace('市', "", $province);
        $city = str_replace("市", "", $city);
        $country = str_replace("县", "", $country);
        $country = str_replace("区", "", $country);

        $areaInfo['id'] 		= $areaid;
        $areaInfo['province'] 	= $province;
        $areaInfo['city'] 		= $city;
        $areaInfo['country'] 	= $country;
        $areaInfo['area'] 		= $area;
        $areaInfo['ip'] 		= $ip;
        $areaInfo['isp'] 		= $isp;

        return $areaInfo;
    }
    
    /*
     * 是否是wap移动设备访问
     *
     * @param #
     *
     * @return boolean
     */
    public static function isWap(){
        if(isset($_SERVER['HTTP_VIA'])) return TRUE;
        if(isset($_SERVER['HTTP_X_NOKIA_CONNECTION_MODE'])) return TRUE;
        if(isset($_SERVER['HTTP_X_UP_CALLING_LINE_ID'])) return TRUE;
        if(strpos(strtoupper($_SERVER['HTTP_ACCEPT']), 'VND.WAP.WML') > 0) return TRUE;
        $http_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? trim($_SERVER['HTTP_USER_AGENT']) : '';
        if($http_user_agent == '') return TRUE;
        $mobile_os = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
        $mobile_token = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');
        $flag_os = $flag_token = FALSE;
        foreach($mobile_os as $val){
            if(strpos($http_user_agent, $val) > 0){ $flag_os = TRUE; break; }
        }
        foreach($mobile_token as $val){
            if(strpos($http_user_agent, $val) > 0){ $flag_token = TRUE; break; }
        }
        if($flag_os || $flag_token) return TRUE;
        return FALSE;
    }
	
    /*
     * 获取客户端天气预报信息
     *
     * @return #
     */
    public static function getWeather(){
    //获取城市
    $areaInfo = self::ip2Area();
        //调用第三方天气API（百度）
        $url  = "http://api.map.baidu.com/telematics/v3/weather?location={$areaInfo[city]}&output=json&ak=A72e372de05e63c8740b2622d0ed8ab1";
        $data = self::file_get_contents_for_array($url);
        //转码
        $data = self::array_iconv("gbk", "utf-8", $data);
        return $data['results'][0]['weather_data'];
    }
    
    /*
     * 改写file_get_contents使其真正抓到内容，解决超时抓不到内容的问题
     *
     * @param string $url   #请求的URL
     *
     * @param string $limit 尝试次数
     *
     * @return string #
     */
    public static function file_get_contents_for_array($url, $limit = 3){
        $str = @file_get_contents($url);
        $data = json_decode($str, true);
        if (empty($data)){
            for ($i=$limit-1;$i>0;$i--){
                self::file_get_contents_for_array($url,$i);
            }
        }
        $data = self::array_iconv('utf-8','gbk',$data);
        return $data;
    }
    
    /*
     * 数组批量转码
     *
     * @param string $in_charset  输入的编码
     *
     * @param string $out_charset 输出的编码
     *
     * @param string $arr         数组
     *
     * @return array #
     */
    public static function array_iconv($in_charset,$out_charset,$arr){  
        return eval('return '.iconv($in_charset,$out_charset,var_export($arr,true).';'));
    }
	
    /* 
     * 判断是否来访并验证域名授权
     *
     * @return string
     */
    public static function verRefererURL()
    {
        $url = $_SERVER['HTTP_REFERER'];	//获取完整的来路URL
        if (empty($url)) {
            return 0;
        }
        $str   = str_replace("http://","",$url);  	//去掉http://
        $str   = str_replace("https://","",$url);  	//去掉https://
        $strdomain = explode("/",$str);           	// 以“/”分开成数组
        $domain    = $strdomain[0];              	//取第一个“/”以前的字符
        return $domain;
    }
    
    /*
    * 字符串中提取数字
    *
    * @param string $str   #字符串
    *
    * @return string #
    */
    public static function findNum($str=''){
        $str=trim($str);
        if(empty($str)){return '';}
        $result='';
        for($i=0;$i<strlen($str);$i++){
                if(is_numeric($str[$i])){
                    $result.=$str[$i];
                }
        }
        return $result;
    }
    
    /*
     * 截取字符串指定范围内容
     *
     * @param string $str   		#字符串
     *
     * @param string $start_str         #开始字符串
     *
     * @param string $end_str   	#结束字符串
     *
     * @return string #
     */
    public static function getStr($str, $start_str, $end_str){ 
        $start_pos = strpos($str,$start_str)+strlen($start_str);  
        $end_pos = strpos($str,$end_str);  
        $c_str_l = $end_pos - $start_pos;  
        $content = substr($str,$start_pos,$c_str_l);  
        return $content;
    }
	
    /*
     * 计算两个坐标之间的距离(米)
     * @param float $fP1Lat 起点(纬度)
     * @param float $fP1Lon 起点(经度)
     * @param float $fP2Lat 终点(纬度)
     * @param float $fP2Lon 终点(经度)
     * @return int
     */
    public static function distanceBetween($fP1Lat, $fP1Lon, $fP2Lat, $fP2Lon){
        $fEARTH_RADIUS = 6378137;
        //角度换算成弧度
        $fRadLon1 = deg2rad($fP1Lon);
        $fRadLon2 = deg2rad($fP2Lon);
        $fRadLat1 = deg2rad($fP1Lat);
        $fRadLat2 = deg2rad($fP2Lat);
        //计算经纬度的差值
        $fD1 = abs($fRadLat1 - $fRadLat2);
        $fD2 = abs($fRadLon1 - $fRadLon2);
        //距离计算
        $fP = pow(sin($fD1/2), 2) +
            cos($fRadLat1) * cos($fRadLat2) * pow(sin($fD2/2), 2);
        return intval($fEARTH_RADIUS * 2 * asin(sqrt($fP)) + 0.5);
    } 
    
    /*
     * 百度坐标系转换成标准GPS坐系
     *
     * @param float  $longitude   		#经度
     *
     * @param float  $latitude  		#纬度
     *
     * @return string #转换后的标准GPS值
     */
    public static function BD09LLtoWGS84($longitude, $latitude){
        $x = $longitude;
        $y = $latitude;
        $Baidu_Server = "http://api.map.baidu.com/ag/coord/convert?from=0&to=4&x={$x}&y={$y}";
        $result = @file_get_contents($Baidu_Server);
        $json = json_decode($result);
        if($json->error == 0){
            $bx = base64_decode($json->x);
            $by = base64_decode($json->y);
            $GPS_x = 2 * $x - $bx;
            $GPS_y = 2 * $y - $by;
            return $GPS_x.','.$GPS_y;//经度,纬度
        } else { 
            return $longitude.','.$latitude;
        }
    }
    
    /*
     * 根据经纬度获取地址--请求接口参考：http://developer.baidu.com/map/index.php?title=webapi/guide/webservice-geocoding
     *
     * @param float  $longitude   		#经度
     *
     * @param float  $latitude  		#纬度
     *
     * @return string 转换后的标准GPS值:
     */
    public static function reGeocoding($longitude, $latitude){
        $url = "http://api.map.baidu.com/geocoder/v2/?ak=pCVWSzGCBcUtHRW3tbHYSrcI&location={$latitude},{$longitude}&output=json&pois=0";
        $result = @file_get_contents($url);
        $data = json_decode($result, true);
        if ($data){
            return $data;
        } else {
            return false;
        }
    }
	
    /*
     * 二维数组去重
     *
     * @param array $arr   		#需要去重的二位数组
     *
     * @param string $key  		#指定需要去重的键
     *
     * @return array
     */
    public static function getUniqueArray($arr, $key){ 
        $rAr=array(); 
        for($i=0;$i<count($arr);$i++) 
        { 
            if(!isset($rAr[$arr[$i][$key]])) 
            { 
                $rAr[$arr[$i][$key]]=$arr[$i]; 
            } 
        } 
        return array_values($rAr);
    }
    
    /*
     * 将地址编码转成地址数组
     *
     * @param string $areaCode	#地区编码
     *
     * @param string $type		#返回类型，1为字符串，2为数组
     *
     * @return array
     */
    public static function areaCode2address($areaCode=440100, $type=1)
    {
        if (empty($areaCode)) {
            return false;
        }
        $areaInfo = M("Area")->find($areaCode);
        if ($type==2) {
            return $areaInfo;
        } else {
            return trim($areaInfo['province']." ".$areaInfo['city']." ".$areaInfo['country']);
        }
    }
    
    /*
     * 列出所有文件夹所有文件
     *
     * @param string $dir		#目录
     *
     * @param array $suffixArr	#要显示的文件后缀数组
     *
     * @return array
     */
    public static function showFileList($dir, $suffixArr){
        $array = array();
        foreach($suffixArr as $k=>$v)
        {
            $pattern = $dir.'*.'.$v;
            $all = glob($pattern);
            $array = array_merge($array,$all);
        }
        return $array;
        /*
        输出格式-------------------------------------------------------------------------------
        array(1) {
          [0] => string(61) "/opt/webroot/booogo.com/files.booogo.com/dyimages/default.png"
        }
        ---------------------------------------------------------------------------------------
        */
    }
    
    /*
     * 列出所有文件夹所有文件-可按照大小/时间/名称排序
     *
     * @param string $dir           #目录
     * @param array 	$suffixArr  #要显示的文件后缀数组
     * @param string $order         #排序字段，TIME，NAME，SIZE
     * @param string $sx            #排序类型，DESC，ASC
     *
     * @return array
     */
    public static function showFileListOrder($dir, $suffixArr, $order='TIME', $sx="DESC"){
        $dh = @opendir($dir);             //打开目录，返回一个目录流
        $return = array();
        $i = 0;
        while($file = @readdir($dh)){     //循环读取目录下的文件
            if($file!='.' and $file!='..'){
                $path = $dir.'/'.$file;     //设置目录，用于含有子目录的情况
                $path = str_replace('//', '/', $path);
                if(is_dir($path)){

                } elseif(is_file($path)){
                    if (!empty($suffixArr)){
                        for($i=0;$i<count($suffixArr);$i++){
                            if (strpos("hd".$path, $suffixArr[$i])){
                                $filesize[] =  round((filesize($path)/1024),2);//获取文件大小
                                $filename[] = $path;//获取文件名称                     
                                $filetime[] = date("Y-m-d H:i:s",filemtime($path));//获取文件最近修改日期    
                                $return[] =  $file;
                            }
                        }
                    }else{
                        $filesize[] =  round((filesize($path)/1024),2);//获取文件大小
                        $filename[] = $path;//获取文件名称                     
                        $filetime[] = date("Y-m-d H:i:s",filemtime($path));//获取文件最近修改日期    
                        $return[] =  $file;
                    }
                }
            }
        }
        if (count($filetime)>1 && $filetime[0]==$filetime[1]) {
            //当有多个图片不能按照时间排序，则按大小排序
            $order 	= "SIZE";
            $sx		= "ASC";
        }
        @closedir($dh);             //关闭目录流
        if ($order=="TIME"){
            if ($sx=="DESC"){
                array_multisort($filetime,SORT_DESC,SORT_STRING, $return);//按时间排序-倒序
            } else {
                array_multisort($filetime,SORT_ASC,SORT_STRING, $return);//按时间排序
            }
        } elseif ($order=="SIZE"){
            if ($sx=="DESC"){
                array_multisort($filesize,SORT_DESC,SORT_NUMERIC, $return);//按大小排序-倒序
            } else {
                array_multisort($filesize,SORT_ASC,SORT_NUMERIC, $return);//按大小排序
            }
        } else {
            if ($sx=="DESC"){
                array_multisort($filename,SORT_DESC,SORT_STRING, $return);//按名字排序-倒序
            } else {
                array_multisort($filename,SORT_ASC,SORT_STRING, $return);//按名字排序
            }
        }
        //返回文件
        return $return;
    }
	
    /*
     * 远程上传base64数据流文件
     *
     * @param string 	$imgStr		#base64字符串
     * @param string		$imgDir		#存放图片的路径 多级目录以逗号隔开
     * @param string		$imgName	#文件名含后缀
     * @param json 	 	$thumbJson 	#储存了要裁剪的json数组
     *
     * @return array
     */
    public static function uploadBase64($imgStr, $imgDir, $imgName, $thumbJson=NULL){
        //文件服务器host和port
        include "/opt/webroot/booogo.com/main.booogo.com/Public/Config/serviceConfig.php";
        $CURL_HOST = $curlConfig[0][0];
        $CURL_PORT = $curlConfig[0][1];
        $CURL_DOMAIN = $curlConfig[0][2];
        $accessAuthKey = '95f24f4c82da92e69cccc16a71068b45';
        //初始化要传的参数
        $sendData = array();
        //客户端与服务器通信的安全密钥
        $sendData['accessAuthKey'] 	= $accessAuthKey;
        $sendData['imgStr'] 		= $imgStr;
        $sendData['imgDir'] 		= $imgDir;
        $sendData['imgName'] 		= $imgName;
        $sendData['thumbJson'] 		= $thumbJson;
        //接收端调用方法
        $method = "uploadBase64";
        $ch             = curl_init();
        $tmp_user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        if (!empty($CURL_HOST)){
            $url = 'http://' . $CURL_HOST . ':' . $CURL_PORT . '/?m=Home&c=FileService&a='.$method;
        } else {
            $url = 'http://' . $CURL_DOMAIN . ':' . $CURL_PORT . '/?m=Home&c=FileService&a='.$method;
        }
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $tmp_user_agent);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $sendData);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        $data = curl_exec($ch);
        //调试模式
        /*if (1==1) {
        if(curl_errno($ch)){
                //error
                dump(curl_error($ch));
        }
        dump($data);
        dump($sendData);
        $reslut = curl_getinfo($ch);
        dump($reslut);
        }*/
        curl_close($ch);
        $info = array();
        if($data){
            $info = json_decode($data, true);
        }
        return $info;
    }
	
    /*
     * 随机生成指定长度字符串函数
     *
     * @param int $length	#长度
     * @param int $type		#生成类型，1为文本，2为密码
     *
     * @return string
     */
    public static function randStr($length=6, $type=1){
        if ($type==1){
            //文本
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        } else {
            //密码
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
        }
        $str = '';
        for ( $i = 0; $i < $length; $i++ ){
            // 这里提供两种字符获取方式
            // 第一种是使用substr 截取$chars中的任意一位字符；
            // 第二种是取字符数组$chars 的任意元素
            // $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
            $str .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $str;
    }
	
    /*
     * 判断是echo还是return数据
     * @param type 			$type 		#数据类型，1为bool，2为json，3为字符串
     * @param string/array              $value 		#要操作的数据值
     * @param bool 			$isRetrun 	#是否强制为return,默认要判断
     *
     * @return  #
     */
    public static function judgmentReturnOrEcho($type=1, $value='false', $isRetrun=false){
        if ($type==1){
            //处理bool类型
            if (IS_AJAX){
                if ($isRetrun){
                    if ($value=="false") {
                        $value = "";
                    }
                    return (bool)$value;
                }
                echo (string)$value;
                exit;
            } else {
                if ($value=="false") {
                    $value = "";
                }
                return (bool)$value;
            }
        }elseif ($type==2){
            //处理json类型
            if (IS_AJAX){
                if ($isRetrun){
                    return $value;
                }
                echo json_encode($value);
                exit;
            } else {
                return $value;
            }
        } else {
            //处理string类型
            if (IS_AJAX){
                    //对强制返回的直接return
                    if ($isRetrun){
                        return $value;
                    }
                    //特殊处理字符串非 true或false的情况
                    if (!in_array((string)$value, array('true', 'false'))){
                        if (intval($value) > 0){
                            $value = "true";
                        } else {
                            $value = "false";
                        }
                    }
                    echo $value;
                    exit;
            } else {
                    return $value;
            }
        }
    }
	
    /* 
     * 功能：计算两个日期相差 年 月 日 时 
     * @param date   $startDate  #起始日期    时间戳 
     * @param date   $endDate    #截止日期日期 时间戳
     * 
     * @return array(年，月，日，时,分)  
     */
    public static function getDateTime($startDate, $endDate){ 
        if($startDate > $endDate){
            $differ    =  $startDate;
            $startDate =  $endDate;
            $endDate   =  $differ;
        }
        $common  = $endDate-$startDate;                  //相差值
        $year    = floor($common/86400/360);               //整数年
        if (!empty($year)) {
                $common = $common - (86400*360)*$year;
        }
        $month   = floor($common/86400/30);			     //整数月
        if (!empty($month)) {
            $common = $common - (86400*30)*$month;
        }
        $day     = floor($common/86400);                   //总的天数
        if (!empty($day)) {
                $common = $common - 86400*$day;
        }
        $time    = floor($common/3600);                    //总的时
        if (!empty($day)) {
                $common = $common - 3600*$time;
        }
        $minute  = floor($common/60);                      //总的分钟
        return array('y'=>$year,'m'=>$month,'d'=>$day,'h'=>$time,'i'=>$minute);
    }    
       
    /*
     * 随机生成主键id函数，用于需要上传图片的表，防止ID冲突
     *
     * @param string $table	#操作的表名，不含前缀
     *
     * @return array  #
     */
    public function randPrimaryKeyId($table){
        $id = mt_rand(1,99999999999);
        if (M($table)->find($id)) {
            $this->randId($table);
        } else {
            return $id;
        }
    }
    
    /*
     * 无限级分类中获取一个分类下的所有分类的ID,包括查找的父ID
     *
     * @param  Array   $categoryArray 原始分类数据
     * @param  int     $id            分类父ID
     * @param  int     $depth         查找深度,默认为5层载入所有子分类，当然可以控制查找几层，找不到数据就不会查找
     * @param  int     $level         父ID的分类级数，默认为空，自动从数据中查找
     * @return array                  父分类下的所有子分类的ID
     */
    public static function getAllSubCategoriesID( $categoryArray, $id, $depth = 5, $level = null ){
        //查找LEVEL
        if( ! $level ){
            foreach( $categoryArray as $v ){
                if( $id == $v['id'] ){
                    $level = $v['level'];
                }
            }
        }
        //没找到LEVEL？数据有问题
        if( ! $level ){
            return false;
        }
        //开始查找
        $result = array( $id );
        $lookup = array( $id );
        for( $i = $level; $i < $depth ; $i ++ ){
            $r  =  self::getAllSubCategoriesIDFind( $categoryArray, $lookup, $i + 1 );
            if( $r ){
                //找到数据就合并
                $result = array_merge( $result, $r );
            }else{
                //没有数据退出
                return $result;
            }
        }
        return $result;
    }
    
    /*
     * 查找分类下某一深度的分类ID--属于上面的获取分类ID函数
     *
     * @param  Array  &$categoryArray 原始分类数据
     * @param  array  &$lookup        查找的ID数组
     * @param  int    $level          深度
     * @return array                  查找到的数组
     */
    public static function getAllSubCategoriesIDFind( &$categoryArray, &$lookup, $level ){
        $result = array();
        foreach( $categoryArray as $k => $v ){
            if( $level == $v['level']){
                if( in_array( $v['pid'], $lookup ) ){
                    $result[] = $v['id'];
                }
                //删除循环过的不在需要的数据，减少下次循环查询次数
                unset( $categoryArray[$k] );
            }
        }
        $lookup = $result;
        return $result;
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
    public static function mbSubstr( $str, $len, $startpos = 0 , $after = '...', $encoding = 'utf-8' ){
        $newStr = mb_substr( $str, $startpos, $len, $encoding );
        if( $newStr !== $str ){
            return $newStr . $after;
        }else{
            return $newStr;
        }
    }
    
    /*
     * 获取二维码
     * @param   $data  二维码包含的文字内容
     * @param   $path  保存二维码的位置，默认不保存false
     * @param   $level 二维码编码纠错级别：L、M、Q、H
     * @param   $size  二维码点的大小
     * @return int     返回二维码
     */
    public static function getQrCode($data,$path=false,$level='L',$size=4){
        Vendor('phpQrCode.phpqrcode');
        $QRcode = new \QRcode ();
        $fileName = $path;
        if($path){
        // 生成的文件名
            $fileName = $path.$size.'.png';	
        }
        $QRcode->png($data, $fileName, $level, $size);
    }
	
    /*
     * 
     * 参数数组转换为url参数
     *
     * @param array $urlData
     */
    public function ToUrlParams($urlData){
        $url = "";
        foreach ($urlData as $k => $v)
        {
            $url .= $k . "=" . $v . "&";
        }
        $url = trim($url, "&");
        return $url;
    }
    
    /*
     * 上传图片
     * 
     * @param   $file       上传的文件
     * @param   $rootPath   根目录
     * @param   $savePath   保存路径
     * @param   $maxSize    上传文件大小
     * @param   $sign       标志，是否制作缩略图，默认为是
     * @param   $smallW     缩略图宽
     * @param   $smallH     缩略图高
     * 
     * @return array    $imgInfo
     */
    public function upLoadImg($file='',$rootPath='./Application/public/',$savePath='upload/',$maxSize='902400',$sign=1,$smallW=80,$smallH=80){
        if(empty($file)){
            return FALSE;
        }
        if($rootPath){
            if(substr($rootPath,-1,1)=="/"){
                $rootPath=  substr($rootPath,0,-1);
            }
        }else{
            return FALSE;
        }
        if($savePath){
            if(substr($savePath,-1,1)=="/"){
                $savePath=  substr($savePath,0,-1);
            }
        }else{
            return FALSE;
        }
        $config =array(
            'rootPath'  =>$rootPath .'/',
            'savePath'  =>$savePath.'/',
            'maxSize'   =>$maxSize,
            'exts'      =>array('jpg','png','gif','jpeg',"JPG","PNG","JPEG","GIF")    //允许上传的文件后缀
        );
        $new_upload =new \Think\Upload($config);
        $upload     =$new_upload->uploadOne($file);
        if(!$upload){
            return $new_upload->getError();
            exit;
        }else{
            $upImg  =$upload['savepath'].$upload['savename'];
            $imgInfo['big_path']=$upImg;
            if($sign==1){
                //把已经上传好的图片制作缩略图Image.class.php
                $smallImg   =new \Think\Image();
                //open();打开图像资源，通过路径名找到图像
                $srcimg     =$new_upload->rootPath.$upImg;
                $smallImg->open($srcimg);
                $smallImg->thumb($smallW,$smallH);
                $smallImage =$upload['savepath']."small_".$upload['savename'];
                $smallImg->save($new_upload->rootPath.$smallImage);
                $imgInfo['small_upImg']=$smallImg;
            }
            return $imgInfo;
        }
    }
    
    /*
     * 删除文件
     * 
     * @param   $delPath    要删除的文件路径
     * @param   $delFile    要删除的文件名
     * 
     * @return bool
     */
    public function delFile($delPath='',$delFile=''){
        if($delPath){
            if(substr($delPath,-1,1)=="/"){
                $delPath=substr($delPath,0,-1);
            }
        }else{
            return FALSE;
        }
        $del=  unlink($delPath.'/'.$delFile);
        if($del){
            function is_empty_dir($delPath){    
                $H = @opendir($delPath); 
                $i=0;    
                while($_file=readdir($H)){    
                    $i++;    
                }    
                closedir($H);    
                if($i>2){ 
                    return TRUE; 
                }else{ 
                    unlink($delPath);  //true
                    return TRUE;
                } 
            }
        }else{
            return FALSE;
        }
    }
    
    /*
     * ajax验证字段的唯一性
     * 
     * @param   $field      需要验证的字段名称
     * @param   $fieldName  被验证的字段  
     * @param   $db         所属数据库
     * 
     * @return Bool
     */
    public function fieldUnique($field,$fieldName,$db,$id=''){
        if(!empty($id)){
            $where['id']    !=$id;
        }else{
            unset($id);
        }
        $is_unique          ='';
        $where["$field"]    =$fieldName;
        $where['is_show']   =1;        
        $unique =D("$db")->where($where)->find();
        if($unique){
            return TRUE;     //字段存在，不唯一，请更换
        }else{
            return FALSE;
        }
    }
    
    /*
     * 格式化金额，用于显示
     *
     * @param  double   $money 格式化前的金额  如：88999.5
     * 
     * @return string   格式化之后的金额  如  88,999.50
     */
    public function formatMoney( $money ){
        $money = (string)$money;
        //先查找小数点，没有就在后面加.00
        $decimalPointPos =  strpos( $money, '.' );
        if( ! $decimalPointPos ){
            //没有小数点
            $money = $money . '.00';
        }else if( $decimalPointPos == ( strlen( $money ) -2 ) ){
            //小数点后面只有一位的情况 
            $money = $money . '0';
        }
        $len = strlen( $money );
        $newMoney = "";
        //开始每3个加一个','
        $startSeparate = false;
        $startSeparateCount = 0;
        for( $i = $len - 1; $i >= 0; $i -- ){
            $newMoney = $money[$i] . $newMoney;
            if( $startSeparate ){
                ++ $startSeparateCount;
                if( $startSeparateCount % 3 == 0 && $i != 0  ){
                    $newMoney = ',' . $newMoney;
                }
            }
            if( '.' == $money[$i] ){
                    $startSeparate = true;
            }
        }
        return $newMoney;
    }
}
