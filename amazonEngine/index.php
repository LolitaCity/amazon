<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

header("content-type:text/html;charset=utf-8");

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

//制作一个输出调试函数
function show_bug($msg){
    echo "<pre style='color:red'>";
    var_dump($msg);
    echo "</pre>";
}

//定义css、img、js常量
define("SITE_URL","/");
define("CSS_URL",SITE_URL."Application/Public/Home/css/");          //css
define("IMG_URL",SITE_URL."Application/Public/Home/img/");          //img
define("JS_URL",SITE_URL."Application/Public/Js/");                 //js

define("ADMIN_CSS_URL",SITE_URL."Application/Public/Admin/css/");   //css
define("ADMIN_IMG_URL",SITE_URL."Application/Public/Admin/img/");   //img

//public 常量
define("PUBLIC_URL",SITE_URL."Application/Public/"); 

//Dwz常量
define("DWZ_URL",SITE_URL."Application/Public/Admin/Dwz/"); 

//为上传图片设置路径
define("IMG_UPLOAD",SITE_URL."Application/Public/");

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',TRUE);

// 定义应用目录
define('APP_PATH','./Application/');

//定义默认模块
define('BIND_MODULE','Admin');

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单
