<?php if (!defined('THINK_PATH')) exit();?><div class="pageContent">	
    <form method="post" action="/Node/edit?callbackType=closeCurrent" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone)">
        <input type="hidden"  id='hidden'  name='id' value="<?php echo ($vo["id"]); ?>"/>
        <input type="hidden"  id='level'  name='level' value="<?php echo ($vo["level"]); ?>"/>
        <div class="pageFormContent" layoutH="58">
            <div class="unit">
                <label>节点名称：</label>
                <input type="text" name="name" size="15" maxlength="30" value="<?php echo ($vo["name"]); ?>" class='required' />
            </div>
            <div class="unit">
                <label>父节点：</label>
                <select class="" name="p_id" id='p_id' style="width: 101px;">
                    <option value='' selected="selected" id='selected'>顶级节点</option>
                    <?php if(is_array($topNoList)): foreach($topNoList as $key=>$topNoList): ?><option value="<?php echo ($topNoList["id"]); ?>" <?php if($topNoList['id'] == $vo['p_id']): ?>selected<?php endif; ?>><?php echo ($topNoList["name"]); ?></option><?php endforeach; endif; ?>
                </select>
            </div>
            <?php if($vo['p_id'] == '' ): ?><div class="unit" id='control'>
                    <label>控制s器：</label>
                    <input type="text" id='seled' name="controller" size="15" class="alphanumeric" value="<?php echo ($vo["controller"]); ?>"/>
                </div><?php endif; ?>
            <div class="unit">
                <label>方法：</label>
                <input type="text" id='action' name="action" size="15" class="alphanumeric" value="<?php echo ($vo["action"]); ?>"/>
            </div>
            <div class="unit">
                <label>排序：</label>
                <input type="text" name="sort_" size="15" value="<?php echo ($vo["sort_"]); ?>" class="digits"/>
            </div>
        </div>
        <div class="formBar">
            <ul>
                <li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
                <li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
            </ul>
        </div>
    </form>	
</div>
<script>
    //修改：1.如果是顶级节点，只允许修改节点名
    if($("#p_id  option:selected").text() == '顶级节点' && $("#hidden").val() !=''){
        $("#action").attr('readonly',true).addClass("readonly");
        $("#p_id").attr("readonly","readonly");
    }else if($("#p_id  option:selected").text() !='顶级节点' && $("#hidden").val() !=''){
        //修改：1.如果不是顶级节点，允许修改节点名、位置、改方法名，不允许修改控制器名（控制器名由下来列表自动修改），不允许设为顶级节点
        $("#selected").remove();
   }else if($("#p_id  option:selected").text() == '顶级节点' && $("#hidden").val() ==''){
       //新增：1.新增顶级节点，显示控制器名，方法名锁定
        $("#action").attr('readonly',true).addClass("readonly");
        //下拉列表改变，不为顶级节点时，控制器锁定，控制器名称自动加载，方法名可以填写
        $("#p_id").change(function(){
            if($("#p_id  option:selected").text() !='顶级节点'){
                $("#action").attr('readonly',false).removeClass("readonly").addClass("required"); 
                $("#level").val(1);
                var p_id=$("#p_id  option:selected").val();
                $.post("/Node/ajaxControl",{pid : p_id},function(data){
                    if(data.substring(0,1)=="\""){
                         //字符串以"开头，去掉"
                         data=data.substr(1,data.length-1)
                   }
                   if(data.substring(data.length-1)=="\"")
                   {
                       //字符串以"结尾，去掉"
                        data=data.substr(0,data.length-1)
                   }
                   $("#seled").val(data).attr('readonly',true);
                },"html");
            }else{
                $("#level").val(0);
                $("#seled").val('');
                $("#seled").attr('readonly',false);
                $("#action").attr('readonly',true).addClass("readonly").removeClass("required");
            }
        })
   }
</script>