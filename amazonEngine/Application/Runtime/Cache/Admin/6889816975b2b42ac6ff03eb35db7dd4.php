<?php if (!defined('THINK_PATH')) exit();?><style>    
    #testFileInput2{
        height: 30px; width: 102px; margin-left: 128px;
    }
    #preview1, #topPhpto
        {  
            width:150px;  
            padding-left: 65px;padding-top: 15px; border-top-width: 5px;
            //height:150px;  
        }
</style>
<div class="pageContent">	
    <form method="post" action="/amazonEngine/User/edit?callbackType=closeCurrent" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">
        <input type="hidden"  id='hidden'  name='id' value="<?php echo ($vo["id"]); ?>"/>
            
        <?php if($_sign == 1): ?><input type="hidden" name="image" value="<?php echo ($vo["image"]); ?>" id="image">
            <div class="pageFormContent" layoutH="58">
                <div class="unit">
                    <label>用户名：</label>
                    <input type="text" name="name" size="25"  value="<?php echo ($vo["name"]); ?>" class='required' />
                </div>
                <div class="unit">
                    <label>昵称：</label>
                    <input type="text" name="nickname" size="25"  value="<?php echo ($vo["nickname"]); ?>" class='' />
                </div>
                <div class="unit">
                    <label>密码：</label>
                    <input type="password" id="cp_newPassword" name="newPassword" size="25" minlength="6" maxlength="20" <?php if(($vo['id']) != ""): ?>class="alphanumeric"<?php else: ?>class="required alphanumeric"<?php endif; ?>/>
                </div>
                <div class="unit">
                    <label>重复输入密码：</label>
                    <input type="password" name="rnewPassword" size="25" equalTo="#cp_newPassword" <?php if(($vo['id']) != ""): ?>class="alphanumeric"<?php else: ?>class="required alphanumeric"<?php endif; ?>/>
                </div>
                <div id="preview1" href="">
                    <img src="<?php echo (PUBLIC_URL); ?>Upload/<?php echo ($vo["image"]); ?>" id='topPhpto'/>               
                </div>
                <div class="unit">
                    <label>头像：</label>  
                    <input id="testFileInput2" type="file" name="images"                                        
                        uploaderOption="{
                            swf:'<?php echo (DWZ_URL); ?>uploadify/scripts/uploadify.swf',
                            uploader:'/amazonEngine/Common/upImg',
                            formData:{authId:<?php echo $_SESSION['authId'];?>, ajax:4},
                            buttonText:'请选择文件',
                            fileSizeLimit:'20000KB',
                            fileTypeDesc:'*.jpg;*.jpeg;*.gif;*.png;',
                            fileTypeExts:'*.jpg;*.jpeg;*.gif;*.png;',
                            auto:true,
                            onUploadSuccess:function(file,data,respore){
                                $('#topPhpto').attr('src','<?php echo (PUBLIC_URL); ?>'+'Upload/'+data);
                                $('#image').val(data);
                            }
                        }"
                    />
                </div>
                <div class="unit">
                    <label>手机：</label>
                    <input type="text" name="tel" size="25" minlength="11" maxlength="11"  value="<?php echo ($vo["tel"]); ?>" class='phone' />
                </div>
                <div class="unit">
                    <label>邮箱：</label>
                    <input type="text" name="email" size="25"  value="<?php echo ($vo["email"]); ?>" class='email' />
                </div>
                <div class="unit">
                    <label>部门：</label>
                    <select class="" name="section_id" id='section' style="width: 101px;">
                        <?php if(is_array($list)): foreach($list as $key=>$section): if(($section['name']) != "所有部门"): ?><option value="<?php echo ($section['id']); ?>" <?php if($section['id'] == $vo['section_id']): ?>selected<?php endif; ?>><?php echo ($section["name"]); ?></option><?php endif; endforeach; endif; ?>
                    </select>
                </div>
                <div class="unit">
                    <label>角色：</label>
                    <select class="" name="role_id" id='section' style="width: 101px;">
                        <?php if(is_array($rlist)): foreach($rlist as $key=>$role): if(($_SESSION['authId']) != "1"): if($role['name'] != '超级管理员'): ?><option value="<?php echo ($role['id']); ?>" <?php if($role['id'] == $u_role): ?>selected<?php endif; ?>><?php echo ($role["name"]); ?></option><?php endif; ?>
                            <?php else: ?>
                                <option value="<?php echo ($role['id']); ?>" <?php if($role['id'] == $u_role): ?>selected<?php endif; ?>><?php echo ($role["name"]); ?></option><?php endif; endforeach; endif; ?>
                    </select>
                </div>
                <div class="unit">
                    <label>工号：</label>
                    <input type="text" name="job_number" size="25"  value="<?php echo ($vo["job_number"]); ?>" class='' />
                </div>
                <div class="unit">
                    <label>岗位：</label>
                    <input type="text" name="position" size="25"  value="<?php echo ($vo["position"]); ?>" class='' />
                </div>
                <div class="unit">
                    <label>入职日期：</label>
                    <input type="text" name="board_time" size="22"  value="<?php echo ($vo["board_time"]); ?>" class="date" readonly="true" />
                    <a class="inputDateButton" href="javascript:;">选择</a>
                </div>
                <div class="unit">
                    <label>个人描述：</label>
                    <textarea name="desc_" rows="6" cols="20" style="resize: none;" class=""><?php echo ($vo["desc_"]); ?></textarea>
                </div>
                <div class="unit">
                    <label>英文描述：</label>
                    <textarea name="en_desc_" rows="6" cols="20" style="resize: none;" class=""><?php echo ($vo["en_desc_"]); ?></textarea>
                </div>
            </div>
        <?php else: ?>
            <div class="pageFormContent" layoutH="58">
                <div class="unit">
                    <label>用户名：</label>
                    <input type="text" name="name" size="25"  value="<?php echo ($vo["name"]); ?>" class='required alphanumeric' />
                </div>
                <div class="unit">
                    <label>昵称：</label>
                    <input type="text" name="nickname" size="25"  value="<?php echo ($vo["nickname"]); ?>" class='required' />
                </div>
                <div class="unit">
                    <label>密码：</label>
                    <input type="password" id="cp_newPassword" name="password" size="25" minlength="6" maxlength="20" <?php if(($vo['id']) != ""): ?>class="alphanumeric"<?php else: ?>class="required alphanumeric"<?php endif; ?>/>
                </div>
                <div class="unit">
                    <label>重复输入密码：</label>
                    <input type="password" name="rnewPassword" size="25" equalTo="#cp_newPassword" <?php if(($vo['id']) != ""): ?>class="alphanumeric"<?php else: ?>class="required alphanumeric"<?php endif; ?>/>
                </div>
                <div class="unit">
                    <label>电话：</label>
                    <input type="text" name="tel" size="25"  value="<?php echo ($vo["tel"]); ?>" class='phone' />
                </div>
                <div class="unit">
                    <label>邮箱：</label>
                    <input type="text" name="email" size="25"  value="<?php echo ($vo["email"]); ?>" class='email' />
                </div>
                <div class="unit">
                    <label>角色：</label>
                    <select class="" name="role_id" id='section' style="width: 101px;">
                        <?php if(is_array($rlist)): foreach($rlist as $key=>$role): ?><option value="<?php echo ($role['id']); ?>" <?php if($role['id'] == $u_role): ?>selected<?php endif; ?>><?php echo ($role["name"]); ?></option><?php endforeach; endif; ?>
                    </select>
                </div>
            </div><?php endif; ?>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>	
</div>