<?php
/**
 * 用于过滤html js php代码 代码copy于Tool::html2text Tool::paramsFilter
 *
 */ 
namespace Org\Com;

class Filter{
    /*
     * 去掉js代码
     *
     * @param string $text #内容
     *
     * @return #
     */
    public static function stripJs($text){
        if (empty($text)) {
            return '';
        }
        $text= htmlspecialchars_decode($text); 
        $text = preg_replace("@<script(.*?)</script>@is", "", $text);
        return $text;
    }
    
    /*
     * 去掉css代码
     *
     * @param string $text #内容
     *
     * @return #
     */
    public static function stripCss($text){
        if (empty($text)) {
            return '';
        }
        $text = preg_replace("'<style[^>]*?>.*?</style>'si", "", $text);
        return $text;
    }
    
    /*
     * 去掉js、css、html代码
     *
     * @param string $text #内容
     *
     * @return #
     */
    public static function stripHtml($text){
        if (empty($text)) {
            return '';
        }        
        $text = preg_replace("/<\/?[^>]+>/i", "", $text);
        return $text;
    }
    
    /*
     * html代码实体化
     *
     * @param string $text #内容
     *
     * @return #
     */
    public static function htmlSpec($text){
        //排除数字的情况
        if (empty($text) || !is_string($text) ) {
            return '';
        }        
        $text = htmlspecialchars($text);
        return $text;
    }
    
    /*
     * html代码实体化
     *
     * @param string $text #内容
     *
     * @return #
     */
    public static function stripTags($text)
    {
        if (empty($text)) {
            return '';
        }
        
        $text = strip_tags($text);
        return $text;
    }

    /*
     * 获取内容中的纯文本
     *
     * @param string $text #内容
     *
     * @return #
     */
    public static function html2text($text){
        if (empty($text)) {
            return '';
        }        
        $text = strip_tags($text);
        $text = trim($text);
        $text = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU", "", $text);
        $alltext = "";
        $start = 1;
        for ($i=0; $i<strlen($text); $i++) {
            if ($start==0 && $text[$i]==">") {
                $start = 1;
            } elseif ($start==1) {
                if ($text[$i]=="<") {
                    $start = 0;
                    $alltext .= " ";
                } elseif (ord($text[$i])>31) {
                    $alltext .= $text[$i];
                }
            }
        }
        $alltext = str_replace(" ", "", $alltext);
        $alltext = preg_replace("/&([^;&]*)(;|&)/", "", $alltext);
        $alltext = preg_replace("/[ ]+/s", " ", $alltext);
        return $alltext;
    }
    
    /*
     * 获取纯文本内容
     *
     * @param string $htmlSpec # true:html实体化 false:不实体化
     * @param string $flag     #1:mysql_real_escape_string 2:mysql_escape_string
     *
     * @return #
     */
    public static function paramsFilter($htmlSpec=true, $flag=1)
    {
        //不推荐使用$_REQUEST (因为至始至终只能过滤一个数组)
        //在PHP的配置文件里面设置的 php.ini variables_order = "EGPCS"
        //这个EGPCS就是说明用$_REQUEST数组获取内容的优先级，
        //其字母的含义分别代表为：E代表$_ENV，G代表$_GET，P代表$_POST，C代表$_COOKIE，S代表$_SESSION。
        //后面出现的数据会覆盖前面写入的数据，其默认的数据写入方式就是EGPCS，
        //!!!!!!所以POST包含的数据将覆盖GET中使用相同关键字的数据。
        //解决多维数组的情况
        if (!empty($_GET)) {
            foreach ($_GET as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::stripCss($value);
                $value = self::stripJs($value);
                $value = self::stripHtml($value);
                if ($htmlSpec) {
                    $value = self::htmlSpec($value);
                }
                $value = self::mysql_filter($value, $flag);
                $_GET[$key] = $value;
            }
        }        
        if (!empty($_POST)) {
            foreach ($_POST as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::stripCss($value);
                $value = self::stripJs($value);
                $value = self::stripHtml($value);
                if ($htmlSpec) {
                    $value = self::htmlSpec($value);
                }
                $value = self::mysql_filter($value, $flag);
                $_POST[$key] = $value;
            }
        }        
        if (!empty($_COOKIE)) {
            foreach ($_COOKIE as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::stripCss($value);
                $value = self::stripJs($value);
                $value = self::stripHtml($value);
                if ($htmlSpec) {
                    $value = self::htmlSpec($value);
                }
                $value = self::mysql_filter($value, $flag);
                $_COOKIE[$key] = $value;
            }
        }
        if (!empty($_SESSION)) {
            foreach ($_SESSION as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::stripCss($value);
                $value = self::stripJs($value);
                $value = self::stripHtml($value);
                if ($htmlSpec) {
                    $value = self::htmlSpec($value);
                }
                $value = self::mysql_filter($value, $flag);
                $_SESSION[$key] = $value;
            }
        }
        if (!empty($_REQUEST)) {
            foreach ($_REQUEST as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::stripCss($value);
                $value = self::stripJs($value);
                $value = self::stripHtml($value);
                if ($htmlSpec) {
                    $value = self::htmlSpec($value);
                }
                $value = self::mysql_filter($value, $flag);
                $_REQUEST[$key] = $value;
            }
        }
        if (!empty($_SERVER)) {
            foreach ($_SERVER as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::stripCss($value);
                $value = self::stripJs($value);
                $value = self::stripHtml($value);
                if ($htmlSpec) {
                    $value = self::htmlSpec($value);
                }
                $value = self::mysql_filter($value, $flag);
                $_SERVER[$key] = $value;
            }
        }
        if (!empty($GLOBALS)) {
            foreach ($GLOBALS as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::stripCss($value);
                $value = self::stripJs($value);
                $value = self::stripHtml($value);
                if ($htmlSpec) {
                    $value = self::htmlSpec($value);
                }
                $value = self::mysql_filter($value, $flag);
                $GLOBALS[$key] = $value;
            }
        }
        if (!empty($_ENV)) {
            foreach ($_ENV as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::stripCss($value);
                $value = self::stripJs($value);
                $value = self::stripHtml($value);
                if ($htmlSpec) {
                    $value = self::htmlSpec($value);
                }
                $value = self::mysql_filter($value, $flag);
                $_ENV[$key] = $value;
            }
        }
        if (!empty($_FILES)) {
            foreach ($_FILES as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::stripCss($value);
                $value = self::stripJs($value);
                $value = self::stripHtml($value);
                if ($htmlSpec) {
                    $value = self::htmlSpec($value);
                }
                $value = self::mysql_filter($value, $flag);
                $_FILES[$key] = $value;
            }
        }
    }

    /*
     * get post参数Mysql过滤
     *
     * @param string $flag #1:mysql_real_escape_string 2:mysql_escape_string
     *
     * @return #
     */
    public static function paramsMysqlFilter($flag=1){
        //不推荐使用$_REQUEST (因为至始至终只能过滤一个数组)
        //在PHP的配置文件里面设置的 php.ini variables_order = "EGPCS"
        //这个EGPCS就是说明用$_REQUEST数组获取内容的优先级，
        //其字母的含义分别代表为：E代表$_ENV，G代表$_GET，P代表$_POST，C代表$_COOKIE，S代表$_SESSION。
        //后面出现的数据会覆盖前面写入的数据，其默认的数据写入方式就是EGPCS，
        //!!!!!!所以POST包含的数据将覆盖GET中使用相同关键字的数据。
        //解决多维数组的情况
        if (!empty($_GET)) {
            foreach ($_GET as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::mysql_filter($value, $flag);
                $_GET[$key] = $value;
            }
        }
        
        if (!empty($_POST)) {
            foreach ($_POST as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::mysql_filter($value, $flag);
                $_POST[$key] = $value;
            }
        }
        
        if (!empty($_REQUEST)) {
            foreach ($_REQUEST as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::mysql_filter($value, $flag);
                $_REQUEST[$key] = $value;
            }
        }
        
        if (!empty($_COOKIE)) {
            foreach ($_COOKIE as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::mysql_filter($value, $flag);
                $_COOKIE[$key] = $value;
            }
        }
        
        if (!empty($_SESSION)) {
            foreach ($_SESSION as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::mysql_filter($value, $flag);
                $_SESSION[$key] = $value;
            }
        }
        
        if (!empty($_SERVER)) {
            foreach ($_SERVER as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::mysql_filter($value, $flag);
                $_SERVER[$key] = $value;
            }
        }
        
        if (!empty($GLOBALS)) {
            foreach ($GLOBALS as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::mysql_filter($value, $flag);
                $GLOBALS[$key] = $value;
            }
        }
        
        if (!empty($_ENV)) {
            foreach ($_ENV as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::mysql_filter($value, $flag);
                $_ENV[$key] = $value;
            }
        }
        
        if (!empty($_FILES)) {
            foreach ($_FILES as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::mysql_filter($value, $flag);
                $_FILES[$key] = $value;
            }
        }
    }
    
    /*
     * get post参数实体化
     *
     * @return #
     */
    public static function paramsHtmlSpecFilter()
    {
        //不推荐使用$_REQUEST (因为至始至终只能过滤一个数组)
        //在PHP的配置文件里面设置的 php.ini variables_order = "EGPCS"
        //这个EGPCS就是说明用$_REQUEST数组获取内容的优先级，
        //其字母的含义分别代表为：E代表$_ENV，G代表$_GET，P代表$_POST，C代表$_COOKIE，S代表$_SESSION。
        //后面出现的数据会覆盖前面写入的数据，其默认的数据写入方式就是EGPCS，
        //!!!!!!所以POST包含的数据将覆盖GET中使用相同关键字的数据。
        //解决多维数组的情况
        if (!empty($_GET)) {
            foreach ($_GET as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::htmlSpec($value);
                $_GET[$key] = $value;
            }
        }
        
        if (!empty($_POST)) {
            foreach ($_POST as $key=>$value) {
                if (is_array($value)) {
                    continue;
                }
                $value = self::htmlSpec($value);
                $_POST[$key] = $value;
            }
        }
    }
    
    /*
     * 过滤mysql注入
     * 
     * @param string $variable #变量名
     * @param string $flag     #1:mysql_real_escape_string 2:mysql_escape_string
     * 
     * @return #
     */
    static public function mysql_filter($variable, $flag=1)
    {
        if (get_magic_quotes_gpc()) {
            $variable = stripslashes($variable);
        }
        // 判断是否为纯数字
        if (!is_numeric($variable)) {
            if ($flag == 1) {
                $variable_result = mysql_real_escape_string($variable);
            } elseif ($flag == 2) {
                $variable_result = mysql_escape_string($variable);
            }
            if ($variable_result == false ) {
                return $variable;
            } else {
                $variable = $variable_result;
            }
        }
        return $variable;
    }

    /*
     * 过滤数据
     * $rules为过滤规则，包括：
     * 　　类型指定：int string(转为gbk编码) array
     * 　　编码指定：utf8
     * 　　html过滤：trim text(纯文本) nobr(去除换行) noslash(去除斜线)
     * 　　默认过滤：css js tag mysql char(实体化) de_char(反实体化) nl2br(还原换行)
     * 　　集成调用：
     * 　　    default: css+js+tag+char(默认加载，不需重复调用这些过滤内容)
     * 　　    editor: js+nobr(仅对编辑器使用)
     * 　　    output: char+nl2br(已用新安全机制存到数据库的数据输出时单独调用)
     * 
     * @param mixed $data 要过滤的数据，兼容数组
     * @param array $rule 过滤规则数组
     * 
     * @return mixed
     */
    static public function clean($data, $rule=array())
    {
        if (empty($data)) {
            return $data;
        }
        if (!empty($rule)&&!is_array($rule)) {
            $rule = array($rule);
        }
        //不指定编辑器时，默认过滤:css、js、tag、mysql、char
        if (!in_array('editor', $rule)&&!in_array('default', $rule)&&!in_array('output', $rule)) {
            //default插入开头
            array_unshift($rule, 'default');
        }
        //不指定类型，默认为字符串string
        if (!in_array('int', $rule)&&!in_array('array', $rule)&&!in_array('output', $rule)) {
            array_unshift($rule, 'string');
        }
        //不指定编码，默认为utf-8
        if (!in_array('gbk', $rule)) {
            array_unshift($rule, 'utf8');
        }
        //默认去除左右空格
        if (!in_array('trim', $rule)) {
            array_unshift($rule, 'trim');
        }
        //指定不trim
        if (in_array('notrim', $rule)) {
            foreach ($rule as $key => $value) {
                if ($value=='trim') {
                    unset($rule[$key]);
                }
            }
        }
        //循环过滤
        foreach ($rule as $k => $val) {
            switch ($val) {
            case 'int':
                //指定为int类型，注意范围过大不能使用
                $data = self::int($data);
                break;
			case 'float':
                //指定为float类型
                $data = self::float($data);
                break;
            case 'string':
                $data = self::string($data);
                break;
            case 'array':
                $data = !is_array($data)?array($data):$data;
                break;
            case 'gbk':
                $data = self::gbk($data);
                break;
            case 'utf8':
                $data = self::utf8($data);
                break;
            case 'css':
                //过滤css
                self::css($data);
                break;
            case 'js':
                //过滤js
                self::js($data);
                break;
            case 'tag':
                //过滤html标签
                self::tags($data);
                break;
            case 'trim':
                //过滤左右空格
                self::trim_string($data);
                break;
            case 'text':
                //先反实体化，再获取纯文本
                self::de_char($data);
                self::text($data);
                break;
            case 'nl2br':
                //换行符显示
                self::nl2br_string($data);
                break;
            case 'nobr':
                //去除换行符
                self::nobr($data);
                break;
            case 'noslash':
                //去除斜线
                self::noslash($data);
                break;
            case 'mysql':
                //mysql_real_string_escape过滤
                self::mysql($data);               
                break;
            case 'char':
                //htmlspecialchars实体化
                self::char($data);
                break;
            case 'de_char':
                //htmlspecialchars_decode反实体化
                self::de_char($data);
                break;
            case 'default':
                //默认集成过滤
                self::css($data);
                self::js($data);
                self::tags($data);
                self::char($data);
                break;
            case 'editor':
                self::js($data);
                self::nobr2($data);
                break;
            case 'output':
                //实体化+显示换行，输出数据时调用
                self::char($data);
                self::nl2br_string($data);
                break;
            }
        }
        return $data;
    }

    /*
     * int过滤
     * 单独调用:filter::int($data),前台业务建议使用clean调用: filter::clean($value, 'int')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function int(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::int($val);
                } else {
                    if (!is_array($val)&&!is_object($val)) {
                        //超过int最大范围不做处理
                        $data[$k] = ($val<2147483647)?intval($val):$val;
                    } 
                }
            }
        } elseif (!is_array($data)&&!is_object($data)) {
            $data = ($data<2147483647)?intval($data):$data;
        }
        return $data;
    }
	
    /*
     * float过滤
     * 单独调用:filter::float($data),前台业务建议使用clean调用: filter::clean($value, 'float')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function float(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::float($val);
                } else {
                    if (!is_array($val)&&!is_object($val)) {
                        //超过int最大范围不做处理
                        $data[$k] = floatval($val);
                    } 
                }
            }
        } elseif (!is_array($data)&&!is_object($data)) {
            $data = floatval($data);
        }
        return $data;
    }

    /*
     * string过滤
     * 单独调用:filter::string($data),前台业务建议使用clean调用: filter::clean($value, 'string')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function string(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::string($val);
                } else {
                    //null值不转为空字符串''
                    if (!is_array($val)&&!is_object($val)&&!is_null($val)) {
                        $data[$k] = strval($val);
                    } 
                }
            }
        } elseif (!is_array($data)&&!is_object($data)&&!is_null($data)) {
            //null值不转为空字符串''
            $data = strval($data);
        }
        return $data;
    }

    /*
     * gbk过滤
     * 单独调用:filter::gbk($data),前台业务建议使用clean调用: filter::clean($value, 'gbk')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function gbk(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::gbk($val);
                } else {
                    if (!is_array($val)&&!is_object($val)&&!is_null($val)) {
                        $data[$k] = Tool::turnEncoding($val);
                    } 
                }
            }
        } elseif (!is_array($data)&&!is_object($data)&&!is_null($data)) {
            $data = Tool::turnEncoding($data);
        }
        return $data;
    }

    /*
     * utf8过滤
     * 单独调用:filter::utf8($data),前台业务建议使用clean调用: filter::clean($value, 'utf8')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function utf8(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::utf8($val);
                } else {
                    if (!is_array($val)&&!is_object($val)&&!is_null($val)) {
                        $data[$k] = Tool::turnEncoding($val, 'utf8');
                    } 
                }
            }
        } elseif (!is_array($data)&&!is_object($data)&&!is_null($data)) {
            $data = Tool::turnEncoding($data, 'utf8');
        }
        return $data;
    }

    /*
     * css过滤
     * 单独调用:filter::css($data),前台业务建议使用clean调用: filter::clean($value, 'css')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function css(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::css($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = preg_replace("'<style[^>]*?>.*?</style>'si", "", $val);
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = preg_replace("'<style[^>]*?>.*?</style>'si", "", $data);
        }
        return $data;
    }

    /*
     * js过滤
     * 单独调用:filter::js($data),前台业务建议使用clean调用: filter::clean($value, 'js')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function js(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::js($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = preg_replace("@<script(.*?)</script>@is", "", $val);
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = preg_replace("@<script(.*?)</script>@is", "", $data);
        }
        return $data;
    }

    /*
     * html标签过滤
     * 单独调用:filter::tags($data),前台业务建议使用clean调用: filter::clean($value, 'tag')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function tags(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::tags($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = strip_tags($val);
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = strip_tags($data);
        }
        return $data;
    }

    /*
     * 全过滤(包括换行)，以获得纯文本
     * 单独调用:filter::text($data),前台业务建议使用clean调用: filter::clean($value, 'text')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function text(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::text($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = self::html2text($val);
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = self::html2text($data);
        }
        return $data;
    }

    /*
     * 数据库过滤，数据需插入数据库时用来防止mysql注入
     * 单独调用:filter::mysql($data),前台业务建议使用clean调用: filter::clean($value, 'mysql')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function mysql(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::mysql($val);
                } else {
                    if (is_string($val)) {
                        // 去除斜杠
                        if (get_magic_quotes_gpc()) {
                            $val = stripslashes($val);
                        }
                        $tmp = mysql_real_escape_string($val);
                        if ($tmp!==false) {
                            $data[$k] = $tmp;
                        }
                    } 
                }
            }
        } elseif (is_string($data)) {
            if (get_magic_quotes_gpc()) {
                $data = stripslashes($data);
            }
            $tmp = mysql_real_escape_string($data);
            if ($tmp!==false) {
                $data = $tmp;
            }
        }
        return $data;
    }

    /*
     * html代码实体化，用于输出数据时防止xss攻击
     * 单独调用:filter::char($data),前台业务建议使用clean调用: filter::clean($value, 'char')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function char(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::char($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = htmlspecialchars($val);
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = htmlspecialchars($data);
        }
        return $data;
    }

    /*
     * 反转html代码实体化，用于插入数据时保持数据原始性
     * 单独调用:filter::de_char($data),前台业务建议使用clean调用: filter::clean($value, 'de_char')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function de_char(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::de_char($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = htmlspecialchars_decode($val);
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = htmlspecialchars_decode($data);
        }
        return $data;
    }

    /*
     * 去除左右空格
     * 单独调用:filter::trim_string($data),前台业务建议使用clean调用: filter::clean($value, 'trim')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function trim_string(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::trim_string($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = trim($val);
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = trim($data);
        }
        return $data;
    }

    /*
     * 还原换行
     * 单独调用:filter::nl2br_string($data),前台业务建议使用clean调用: filter::clean($value, 'nl2br')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function nl2br_string(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::nl2br_string($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = nl2br($val);
                        //nl2br会失效，str_replace做多一层过滤
                        if (!strpos($val, '<br')) {
                            $data[$k] = str_replace(array("\r\n", "\n", "\r"), "<br/>", $val);
                        }
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = nl2br($data);
            //nl2br会失效，str_replace做多一层过滤
            if (!strpos($data, '<br')) {
                $data = str_replace(array("\r\n", "\n", "\r"), "<br/>", $data);
            }
        }
        return $data;
    }

    /*
     * 去除换行，专门用于editor去除\r\n的换行
     * 单独调用:filter::nobr2($data),前台业务建议使用clean调用: filter::clean($value, 'nobr2')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function nobr2(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::nobr2($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = str_replace(array("\r\n", "\n", "\r"), '', $val);
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = str_replace(array("\r\n", "\n", "\r"), '', $data);
        }
        return $data;
    }

    /*
     * 去除换行
     * 单独调用:filter::nobr($data),前台业务建议使用clean调用: filter::clean($value, 'nobr')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function nobr(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::nobr($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = str_replace(array("\r\n", "\n", "\r", "<br>", "<br/>", "<br />"), '', $val);
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = str_replace(array("\r\n", "\n", "\r", "<br>", "<br/>", "<br />"), '', $data);
        }
        return $data;
    }

    /*
     * 去除斜线
     * 单独调用:filter::noslash($data),前台业务建议使用clean调用: filter::clean($value, 'noslash')
     * 
     * @param string &$data 数据
     * 
     * @return string
     */
    static public function noslash(&$data){
        if (is_array($data)) {
            foreach ($data as $k => $val) {
                if (is_array($val)) {
                    $data[$k] = self::noslash($val);
                } else {
                    if (is_string($val)) {
                        $data[$k] = stripslashes($val);
                    } 
                }
            }
        } elseif (is_string($data)) {
            $data = stripslashes($data);
        }
        return $data;
    }
}
?>