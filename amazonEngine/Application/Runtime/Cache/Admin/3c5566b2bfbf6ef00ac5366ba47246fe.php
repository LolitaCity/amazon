<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Aeotec background management platform</title>
<link href="<?php echo (DWZ_URL); ?>themes/css/login.css" rel="stylesheet" type="text/css" />
<script src="<?php echo (JS_URL); ?>jquery.js" type="text/javascript"></script>
</head>
    <style>
        #subt{
            border: 0;
            background: #ffffff;
            width:0px;
            height:0px;
        }
    </style>
<body>
    <div id="login">
        <div id="login_header">
            <h1 class="login_logo">
               <!-- <a href="http://demo.dwzjs.com">
                   <img src="<?php echo (DWZ_URL); ?>themes/default/images/login_logo.gif" /></a>-->
            </h1>
            <div class="login_headerContent">
              <!--  <div class="navList">
                    <ul>
                        <li><a href="#">设为首页</a></li>
                        <li><a href="http://bbs.dwzjs.com">反馈</a></li>
                        <li><a href="doc/dwz-user-guide.pdf" target="_blank">帮助</a></li>
                    </ul>
                </div>-->
                <h2 class="login_title">
                    <!--<img src="<?php echo (DWZ_URL); ?>themes/default/images/login_title.png" /></h2>-->
            </div>
        </div>
        <div id="login_content">
            <div class="loginForm">
                <form action="/Public/checkLogin" method="post" id="myForm">
                    <p>
                        <label>User:</label>
                        <input type="text" name="user_name" size="20" class="login_input" />
                    </p>
                    <p>
                        <label>Password:</label>
                        <input type="password" name="password" size="20" class="login_input" />
                    </p>
                    <p>
                        <label>Captcha :</label>
                        <input class="code" id="captcha" name="captcha" type="text" size="5"/>
                        <span><img src="/Public/verifyImg"  alt="" onclick="this.src='/Public/verifyImg?d='+Math.random();" width="75" height="24"/></span>
                    </p>
                        
                </form>
                <div class="login_bar">
                    <button class="sub" id="sub"></button>                       
                </div>                
            </div>
            <div class="login_banner"><img src="<?php echo (DWZ_URL); ?>themes/default/images/login_banner_.jpg" /></div>
            <div class="login_main">
<!--                <ul class="helpList">
                    <li><a href="#">公共后台</a></li>
                    <li><a href="#" style="color:#0000FF;font-size: 20px">用户名：admin</a></li>
                    <li><a href="#" style="color:green;font-size: 20px">密　码：123456</a></li>
                    <li><a href="#">仅供测试</a></li>
                </ul>
                <div class="login_inner">
                    <p>您可以使用 网易网盘 ，随时存，随地取</p>
                    <p>您还可以使用 闪电邮 在桌面随时提醒邮件到达，快速收发邮件。</p>
                    <p>在 百宝箱 里您可以查星座，订机票，看小说，学做菜…</p>
                </div>-->
            </div>
        </div>
        <div id="login_footer">
          <!--  < ICP证：<a href="http://www.miitbeian.gov.cn" target="_block">粤ICP备17007275号</a>-->
        </div>
    </div>
</body>
</html>
<script>
    $(function(){
        $("#sub").click(function(){
            var name    =$("input[name='user_name']").val();
            var password=$("input[name='password']").val();
            var captcha =$("#captcha").val();
            if(name==''){
                alert('请填写用户名');
                return false;
            }
            if(password==''){
                alert('请填写密码');
                return false;
            } 
            if(captcha==''){
                alert('请填写验证码');
                return false;
            }else{            
                $.post("/Public/ajaxCode",{captcha:captcha},function(data){
                    if(data==1){
                        alert("验证码错误");
                        return false;
                    }else{
                        $("#myForm").submit();
                    }
                },'json');
            }               
        });            
    });
        
</script>