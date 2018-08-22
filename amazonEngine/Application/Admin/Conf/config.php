<?php
return array(
    //'配置项'=>'配置值'
    'APP_AUTOLOAD_PATH'     =>'@.TagLib',
    'SESSION_AUTO_START'    => true,                    //是否自动开启Session
    'USER_AUTH_KEY'         =>'authId',                 //设置session的标记名称
    //'ADMIN_AUTH_KEY'        =>'administrator',        //设置管理员用户标记
    'AUTH_PWD_ENCODE'       =>'md5',                    //用户认证加密方式
    'USER_AUTH_GATEWAY'     =>'Public/login',           //默认用户网关
    'NOT_AUTH_MODULE'       =>'Public',                 //默认不需要认证的模块
    'REQUIRE_AUTH_MODULE'   =>'',			//默认需要认证的模块
    'NOT_AUTH_ACTION'       =>'',                       //默认不需要认证的操作
    'REQUIRE_AUTH_ACTION'   =>'',                       //默认需要认证的操作
    'AUTH_ON'               => true,                    //认证开关 
    //自定义错误页面
    'TMPL_ACTION_ERROR'     =>  'Public/error', 	
    
);