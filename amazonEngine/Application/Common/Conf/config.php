<?php
return array(
    //'配置项'=>'配置值'
    //url模式设置
    'URL_ROUTER_ON'         =>TRUE,
    'URL_MODEL'             =>2,  

    //让页面显示追踪日志信息
    'SHOW_PAGE_TRACE'       =>FALSE,  

    //url地址大小写不敏感设置
    'URL_CASE_INSENSITIVE'  =>TRUE,
   
    /*数据库连接配置*/
    'DB_TYPE'               => 'mysql',             // 数据库类型   
    'DB_HOST'               => '127.0.0.1',         // 服务器地址
    'DB_NAME'               => 'amazon',            // 数据库名
    'DB_USER'               => 'root',              // 用户名
    'DB_PWD'                => 'root',              // 密码
    'DB_PREFIX'             => '',                  // 数据库表前缀
    'DB_PORT'               => 3306,                // 端口
    
    /*
    'DB_TYPE'           =>  'mysql',  // 数据库类型
    'DB_USER'           =>  'root',   // 用户名
    'DB_PWD'            =>  'root',   // 密码
    'DB_PREFIX'         =>  'mh_',    // 数据库表前缀
    'DB_DSN'            =>  'mysql:host=localhost;dbname=mhsoft;charset=UTF8',
     * 
     */
    /*end*/	
    //修改定界符
    'TMPL_L_DELIM'	=>  '<{',
    'TMPL_R_DELIM'	=>  '}>',
    
    // URL伪静态后缀设置
    'URL_HTML_SUFFIX'   =>  'html',
    // URL禁止访问的后缀设置
    'URL_DENY_SUFFIX'   =>  'ico|png|gif|jpg|pdf', 
    
    //静态缓存设置
    'HTML_CACHE_ON'     => false,               // 关闭静态缓存
	
    //I方法默认过滤方式
    'DEFAULT_FILTER'    =>'htmlspecialchars',
    
    /*其他设置*/
    'HTTP_CACHE_CONTROL'=>'private',            // 网页缓存控制
    'CHECK_APP_DIR'     =>FALSE,                // 是否检查应用目录是否创建
    'FILE_UPLOAD_TYPE'  =>'Local',              // 文件上传方式
    'DATA_CRYPT_TYPE'   =>'Think',              // 数据加密方式
    'VAR_PAGE'          =>'pageNum',            //设置分页参数名称,默认为p，如为p可不设置
    //注册新的命名空间
    'AUTOLOAD_NAMESPACE'=>array(),
    
    //异位或加密密钥
    'AUTO_LOGIN_KEY'    =>md5('www.fantem.com'),
    
    //页面跳转的头标题
    'PAGE_JUMP_PARAM'   =>'',                   //1为Announcements，2为News,4为All
    
    //多语言支持
    'LANG_SWITCH_ON'    => true,                // 默认关闭语言包功能
    'LANG_AUTO_DETECT'  => true,                // 自动侦测语言 开启多语言功能后有效
    'DEFAULT_LANG'      => 'en-us',             // 默认语言
    'LANG_LIST'         => 'zh-cn,zh-tw,en-us', // 允许切换的语言列表 用逗号分隔
    'VAR_LANGUAGE'      => 'hl',                // 默认语言切换变量
	
    
    /*Cookie设置*/
    'COOKIE_EXPIRE'         =>  7*24*60,        // Cookie有效期
    'COOKIE_DOMAIN'         =>  '',             // Cookie有效域名
    'COOKIE_PATH'           =>  '/',            // Cookie路径
    'COOKIE_PREFIX'         =>  'ft_',          // Cookie前缀 避免冲突
    'COOKIE_HTTPONLY'       =>  '',             // Cookie的httponly属性 3.2.2新增

    /*SESSION设置*/
    'SESSION_AUTO_START'    =>  true,           // 是否自动开启Session
    'SESSION_OPTIONS'       =>  array(),        // session 配置数组 支持type name id path expire domain 等参数
    'SESSION_TYPE'          =>  '',             // session hander类型 默认无需设置 除非扩展了session hander驱动
    'SESSION_PREFIX'        =>  '', 		// session 前缀

    /*数据缓存设置*/
    'DATA_CACHE_TIME'       =>  0,      	// 数据缓存有效期 0表示永久缓存
    'DATA_CACHE_COMPRESS'   =>  false,   	// 数据缓存是否压缩缓存
    'DATA_CACHE_CHECK'      =>  false,   	// 数据缓存是否校验缓存
    'DATA_CACHE_PREFIX'     =>  '',     	// 缓存前缀
    'DATA_CACHE_TYPE'       =>  'File',  	// 数据缓存类型,支持:File|Db|Apc|Memcache|Shmop|Sqlite|Xcache|Apachenote|Eaccelerator
    'DATA_CACHE_PATH'       =>  TEMP_PATH,	// 缓存路径设置 (仅对File方式缓存有效)
    'DATA_CACHE_SUBDIR'     =>  false,    	// 使用子目录缓存 (自动根据缓存标识的哈希创建子目录)
    'DATA_PATH_LEVEL'       =>  1,        	// 子目录缓存级别

    /*日志设置*/
    'LOG_RECORD'            =>  false,                          // 默认不记录日志
    'LOG_TYPE'              =>  'File',                         // 日志记录类型 默认为文件方式
    'LOG_LEVEL'             =>  'EMERG,ALERT,CRIT,ERR',         // 允许记录的日志级别
    'LOG_EXCEPTION_RECORD'  =>  true,                           // 是否记录异常信息日志
    
    /*默认设定*/
    'DEFAULT_M_LAYER'       =>  'Model',                        // 默认的模型层名称
    'DEFAULT_C_LAYER'       =>  'Controller',                   // 默认的控制器层名称
    'DEFAULT_V_LAYER'       =>  'View',                         // 默认的视图层名称
    'DEFAULT_THEME'         =>  '',                             // 默认模板主题名称
    'DEFAULT_MODULE'        =>  'Home',                         // 默认模块
    'DEFAULT_CONTROLLER'    =>  'Index',                        // 默认控制器名称
    'DEFAULT_ACTION'        =>  'index',                        // 默认操作名称
    'DEFAULT_CHARSET'       =>  'utf-8',                        // 默认输出编码
    'DEFAULT_TIMEZONE'      =>  'PRC',                          // 默认时区
    'DEFAULT_AJAX_RETURN'   =>  'JSON',                         // 默认AJAX 数据返回格式,可选JSON XML ...
    'DEFAULT_JSONP_HANDLER' =>  'jsonpReturn',                  // 默认JSONP格式返回的处理方法
    'DEFAULT_FILTER'        =>  'htmlspecialchars',             // 默认参数过滤方法 用于I函数...
    'MODULE_DENY_LIST'      =>  array('Common','Runtime'),      // 禁止访问的模块列表
    'APP_AUTOLOAD_PATH'     => 'Think.Util.,@.Common.',         // 自动加载的路径（针对非命名空间定义类库） 3.2.1新增

);