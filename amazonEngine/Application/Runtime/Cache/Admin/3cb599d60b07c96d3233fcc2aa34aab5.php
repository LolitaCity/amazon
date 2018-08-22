<?php if (!defined('THINK_PATH')) exit();?>  <style>    
    #testFileInput2{
        height: 30px; width: 102px; margin-left: 128px;
    }
    #preview1, #topPhpto,#preview2, #topPhpto2,#preview3, #topPhpto3,#preview4, #topPhpto4
    {  
        width:150px;  
        padding-left: 65px;padding-top: 15px; border-top-width: 5px;
        //height:150px;  
    }
    #testFileInput3-button {
        height: 30px; line-height: 30px; width: 120px; margin-left: 130px;
    }
    #testFileInput4-button{
        height: 30px; line-height: 30px; width: 120px; padding-left: 0px; border-left-width: 2px; margin-left: 140px;
    }
    #testFileInput5-button{
        height: 30px; line-height: 30px; width: 120px; margin-left: 140px;
    }
</style>
<script src="<?php echo (JS_URL); ?>imgbox.js"></script>
<div class="pageContent" style="width:800px;height:600px">	
    <form method="post" action="/Web/edit?callbackType=closeCurrent" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">
        <input type='hidden' name='id' value="<?php echo ($vo['id']); ?>"/>
        <?php if($_sign == 1): ?><input type='hidden' name='icon' value="<?php echo ($vo["icon"]); ?>"  id='img1'/>
            <input type='hidden' name='logo' value="<?php echo ($vo["logo"]); ?>"  id='img2'/>
            <input type='hidden' name='image'value="<?php echo ($vo["image"]); ?>" id='img3'/>
            <input type='hidden' name='img'  value="<?php echo ($vo["img"]); ?>"   id='img4'/>
            <div class="pageFormContent" layoutH="58"> 
                <div class="unit">
                    <label>网站名：</label>
                    <textarea name="name" rows="1" cols="35" style="resize: none;" class="required"><?php echo ($vo["name"]); ?></textarea>
                </div>
                <div class="unit">
                    <label>英文网站名：</label>
                    <textarea name="en_name" rows="1" cols="35" style="resize: none;" class="required"><?php echo ($vo["en_name"]); ?></textarea>
                </div>
                <div id="preview1" href=""><img src="<?php echo (PUBLIC_URL); ?>Upload/<?php echo ($vo["icon"]); ?>" id='topPhpto'/></div>
                <div class="unit">
                    <label>上传图标：</label>  
                    <input id="testFileInput2" type="file" name="images"                                        
                        uploaderOption="{
                            swf:'<?php echo (DWZ_URL); ?>uploadify/scripts/uploadify.swf',
                            uploader:'/Common/upImg',
                            formData:{authId:<?php echo $_SESSION['authId'];?>, ajax:1},
                            buttonText:'请选择文件',
                            fileSizeLimit:'20000KB',
                            fileTypeDesc:'*.ico;',
                            fileTypeExts:'*.ico;',
                            auto:true,
                            onUploadSuccess:function(file,data,respore){
                                $('#topPhpto').attr('src','<?php echo (PUBLIC_URL); ?>'+'Upload/'+data);
                                $('#img1').val(data);
                            }
                        }"
                    />
                </div>
                <div id="preview2" href=""><img src="<?php echo (PUBLIC_URL); ?>Upload/<?php echo ($vo["logo"]); ?>" id='topPhpto2'/></div>
                <div class="unit">
                    <label>上传LOGO：</label>  
                    <input id="testFileInput3" type="file" name="images"                                        
                        uploaderOption="{
                            swf:'<?php echo (DWZ_URL); ?>uploadify/scripts/uploadify.swf',
                            uploader:'/Common/upImg&aid=<?php echo (session('ad_image')); ?>',
                            formData:{authId:<?php echo $_SESSION['authId'];?>, ajax:1},
                            buttonText:'请选择文件',
                            fileSizeLimit:'20000KB',
                            fileTypeDesc:'*.JPEG;*.jpg;*.png;*.gif;',
                            fileTypeExts:'*.JPEG;*.jpg;*.png;*.gif;',
                            auto:true,
                            onUploadSuccess:function(file,data,respore){
                                $('#topPhpto2').attr('src','<?php echo (PUBLIC_URL); ?>'+'Upload/'+data);
                                $('#img2').val(data);
                            }
                        }"
                    />
                </div>
                <div id="preview3" href=""><img src="<?php echo (PUBLIC_URL); ?>Upload/<?php echo ($vo["image"]); ?>" id='topPhpto3'/></div>
                <div class="unit">
                    <label>公众号二维码：</label>  
                    <input id="testFileInput4" type="file" name="images"                                        
                        uploaderOption="{
                            swf:'<?php echo (DWZ_URL); ?>uploadify/scripts/uploadify.swf',
                            uploader:'/Common/upImg&aid=<?php echo (session('ad_image')); ?>',
                            formData:{authId:<?php echo $_SESSION['authId'];?>, ajax:1},
                            buttonText:'请选择文件',
                            fileSizeLimit:'20000KB',
                            fileTypeDesc:'*.JPEG;*.jpg;*.png;*.gif;',
                            fileTypeExts:'*.JPEG;*.jpg;*.png;*.gif;',
                            auto:true,
                            onUploadSuccess:function(file,data,respore){
                                $('#topPhpto3').attr('src','<?php echo (PUBLIC_URL); ?>'+'Upload/'+data);
                                $('#img3').val(data);
                            }
                        }"
                    />
                </div>
                <div id="preview4" href=""><img src="<?php echo (PUBLIC_URL); ?>Upload/<?php echo ($vo["img"]); ?>" id='topPhpto4'/></div>
                <div class="unit">
                    <label>关于我们图片：</label>  
                    <input id="testFileInput5" type="file" name="images"                                        
                        uploaderOption="{
                            swf:'<?php echo (DWZ_URL); ?>uploadify/scripts/uploadify.swf',
                            uploader:'/Common/upImg&aid=<?php echo (session('ad_image')); ?>',
                            formData:{authId:<?php echo $_SESSION['authId'];?>, ajax:1},
                            buttonText:'请选择文件',
                            fileSizeLimit:'20000KB',
                            fileTypeDesc:'*.JPEG;*.jpg;*.png;*.gif;',
                            fileTypeExts:'*.JPEG;*.jpg;*.png;*.gif;',
                            auto:true,
                            onUploadSuccess:function(file,data,respore){
                                $('#topPhpto4').attr('src','<?php echo (PUBLIC_URL); ?>'+'Upload/'+data);
                                $('#img4').val(data);
                            }
                        }"
                    />
                </div>
                <div class="unit">
                    <label>联系电话1：</label>
                    <input type="text" name="tel" value="<?php echo ($vo["tel"]); ?>" class="phone" size="25" />
                </div>
                <div class="unit">
                    <label>联系电话2：</label>
                    <input type="text" name="tel" value="<?php echo ($vo["tel_1"]); ?>" class="phone" size="25" />
                </div>
                <div class="unit">
                    <label>联系电话3：</label>
                    <input type="text" name="tel" value="<?php echo ($vo["tel_2"]); ?>" class="phone" size="25" />
                </div>
                <div class="unit">
                    <label>网址1：</label>
                    <input type="text" name="weburl" value="<?php echo ($vo["weburl"]); ?>" class="" size="25" />
                </div>
                <div class="unit">
                    <label>网址2：</label>
                    <input type="text" name="weburl_2" value="<?php echo ($vo["weburl_2"]); ?>" class="" size="25" />
                </div>
                <div class="unit">
                    <label>邮箱：</label>
                    <input type="text" name="email" value="<?php echo ($vo["email"]); ?>" class="email" size="25"/>
                </div>
                <div class="unit">
                    <label>公司简介（中文）：</label>
                    <textarea name="company_profile" rows="8" cols="60" style="resize: none;" class="required"><?php echo ($vo["company_profile"]); ?></textarea>
                </div>
                <div class="unit">
                    <label>公司简介（英文）：</label>
                    <textarea name="en_company_profile" rows="8" cols="60" style="resize: none;" class="required"><?php echo ($vo["en_company_profile"]); ?></textarea>
                </div>
                <div class="unit">
                    <label>SEO关键字：</label>
                    <textarea name="keywords" rows="4" cols="60" style="resize: none;" class="required"><?php echo ($vo["keywords"]); ?></textarea>
                </div>
                <div class="unit">
                    <label>SEO英文关键字：</label>
                    <textarea name="en_keywords" rows="4" cols="60" style="resize: none;" class="required"><?php echo ($vo["en_keywords"]); ?></textarea>
                </div>
                <div class="unit">
                    <label>网站描述：</label>
                    <textarea name="desc_" rows="6" cols="60" style="resize: none;" class="required"><?php echo ($vo["desc_"]); ?></textarea>
                </div>
                <div class="unit">
                    <label>网站英文描述：</label>
                    <textarea name="en_desc_" rows="6" cols="60" style="resize: none;" class="required"><?php echo ($vo["en_desc_"]); ?></textarea>
                </div>                
                <div class="unit">
                    <label>地址：</label>
                    <textarea name="address" rows="4" cols="60" style="resize: none;" class=""><?php echo ($vo["address"]); ?></textarea>
                </div>
                <div class="unit">
                    <label>英文地址：</label>
                    <textarea name="en_address" rows="4" cols="60" style="resize: none;" class=""><?php echo ($vo["en_address"]); ?></textarea>
                </div>
                <div class="unit">
                    <label>版权信息：</label>
                    <textarea name="copyright" rows="6" cols="60" style="resize: none;" class="required"><?php echo ($vo["copyright"]); ?></textarea>
                </div>
                <div class="unit">
                    <label>英文版权信息：</label>
                    <textarea name="en_copyright" rows="6" cols="60" style="resize: none;" class="required"><?php echo ($vo["en_copyright"]); ?></textarea>
                </div>
            </div>
        <?php elseif($_sign == 2): ?>
            <input type="hidden" name='db' value='Role'/>
            <div class="pageFormContent" layoutH="58">
                <div class="unit">
                    <label>角色名称：</label>
                    <input type="text" name="name" size="15" class="required" value="<?php echo ($vo["name"]); ?>" />
                </div>            			
            </div>            
        <?php elseif($_sign == 3): ?>
            <input type="hidden" name='db' value='Banner'/>
            <input type='hidden' name='image' value="<?php echo ($vo["image"]); ?>"  id='img1'/>
            <div class="pageFormContent" layoutH="58">
                <div class="unit">
                    <label>图片名称：</label>
                    <input type="text" name="name" size="25" class="" value="<?php echo ($vo["name"]); ?>" />
                </div>   
                <div id="preview1" href=""><img src="<?php echo (PUBLIC_URL); ?>Upload/<?php echo ($vo["image"]); ?>" id='topPhpto'/></div>
                <div class="unit">
                    <label>上传图标：</label>  
                    <input id="testFileInput2" type="file" name="images"                                        
                        uploaderOption="{
                            swf:'<?php echo (DWZ_URL); ?>uploadify/scripts/uploadify.swf',
                            uploader:'/Common/upImg',
                            formData:{authId:<?php echo $_SESSION['authId'];?>, ajax:2},
                            buttonText:'请选择文件',
                            fileSizeLimit:'20000KB',
                            fileTypeDesc:'*.jpg;*.jpeg;*.gif;*.png;',
                            fileTypeExts:'*.jpg;*.jpeg;*.gif;*.png;',
                            auto:true,
                            onUploadSuccess:function(file,data,respore){
                                $('#topPhpto').attr('src','<?php echo (PUBLIC_URL); ?>'+'Upload/'+data);
                                $('#img1').val(data);
                            }
                        }"
                    />
                </div>
                <div class="unit">
                    <label>超链接：</label>
                    <input type="text" name="imgurl" size="25" class="" value="<?php echo ($vo["imgurl"]); ?>" />
                </div>
                <div class="unit">
                    <label>排序：</label>
                    <input type="text" name="sort_" size="25" class="" value="<?php echo ($vo["sort_"]); ?>" />
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