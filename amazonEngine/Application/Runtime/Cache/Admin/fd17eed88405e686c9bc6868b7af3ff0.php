<?php if (!defined('THINK_PATH')) exit();?><div class="pageContent">

    <form action="/Web/addAuth?callbackType=closeCurrent" method="post" class="pageForm required-validate"
          onsubmit="return validateCallback(this, dialogAjaxDone);">
        <input type="hidden" name="role_id" VALUE="<?php echo (code($_GET['role_id'])); ?>"/>
        <div class="pageFormContent" layoutH="58">
            <?php if(is_array($oneList)): foreach($oneList as $key=>$nList): ?><div class="unit">
                    <input type="checkbox" onclick="clickData(this)" id="sel<?php echo ($nList["id"]); ?>" name="nodeId[]" value="<?php echo ($nList["id"]); ?>"  <?php echo in_array($nList['id'], $rList) ? "checked" : "" ?>/><?php echo ($nList["name"]); ?>                   
                </div>
                <div class="unit">
                    <?php if(is_array($twoList)): foreach($twoList as $key=>$tList): if(($tList['p_id']) == $nList['id']): ?>&nbsp;　　&nbsp;<input type="checkbox" id="sel<?php echo ($nList["id"]); ?>" name="nodeId[]" value="<?php echo ($tList["id"]); ?>"  <?php echo in_array($tList['id'], $rList) ? "checked" : "" ?>/><?php echo ($tList["name"]); ?><br/><?php endif; endforeach; endif; ?>
                </div><?php endforeach; endif; ?>
        </div>
        <div class="formBar">
            <label style="float:left"><input type="checkbox" class="checkboxCtrl" group="nodeId[]"/>全选</label>
            <ul>
                <li>
                    <div class="button">
                        <div class="buttonContent">
                            <button type="button" class="checkboxCtrl" group="nodeId[]" selectType="invert">
                                反选
                            </button>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="buttonActive">
                        <div class="buttonContent">
                            <button type="submit">提交</button>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="button">
                        <div class="buttonContent">
                            <button type="button" class="close">取消</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </form>
</div>
<script>   
    function clickData(obj){
        var id=$(obj).attr("id");
       if($(obj).prop("checked") == true) {
           $("input[id='"+id+"']").prop("checked", true);
       }else{
           $("input[id='"+id+"']").prop("checked", false);
       }
    } 
</script>