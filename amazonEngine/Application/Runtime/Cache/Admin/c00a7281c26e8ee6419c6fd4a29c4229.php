<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" method="post" action="<?php echo U();?>"> 
    <input type="hidden" name="pageNum" value="<?php echo ($_REQUEST["pageNum"]); ?>" />
    <input type="hidden" name="numPerPage" value="<?php echo ($_REQUEST["numPerPage"]); ?>" /> 
    <input type="hidden" name="_order" value="<?php echo ($order); ?>" />
    <input type="hidden" name="_sort" value="<?php echo ($sort); ?>" />  
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST["listRows"]); ?>"/>
</form>


<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="/Web/role" method="post">
	<div class="searchBar">		
            <table class="searchContent">
                <tr>
                    <td>
                        角色名称：<input type="text" name="name" value="<?php echo ($_POST['name']); ?>"/>
                    </td>
                </tr>
            </table>
            <div class="subBar">
                <ul>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
                </ul>
            </div>
	</div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">            
            <li><a class="add" href="<?php echo U('Web/show',array('sign'=>code('2',1),'db'=>code('Role',1)));?>" target="dialog" mask="true" width='400' height='200'><span>添加角色</span></a></li>                      
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="id[]" href="/Web/del/sign/<?php echo code('1',1)?>/db/<?php echo code('Role',1);?>" class="delete"><span>批量删除</span></a></li>                  
            <li><a class="edit" href="/Web/show/id/{l_id}/sign/<?php echo code('2',1);?>/db/<?php echo code('Role',1);?>" target="dialog" mask="true" warn="请选择一条信息" width='400' height='200'><span>角色修改</span></a></li>
            <li class="line">line</li>
        </ul>
    </div>
    <table class="table" width="350" layoutH="138">
        <thead>
            <tr>
                <th width="22"><input type="checkbox" group="id[]" class="checkboxCtrl"></th>
                <th width="60"  align="center">序号</th>
                <th width="118" >角色名称</th>               
                <th width="150" align="center">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): foreach($list as $key=>$list): if(($list['name']) != "普通用户"): ?><tr target="l_id" rel="<?php echo ($list["id"]); ?>">
                        <td><input name="id[]" value="<?php echo ($list["id"]); ?>" type="checkbox"></td>
                        <td><?php echo ($key+1); ?></td>
                        <td><?php echo ($list["name"]); ?></td>          
                        <td>
                            <a href="<?php echo U('Web/nodeList',array('role_id'=>code($list['id'],1)));?>" target="dialog" height="600" width="500"><span style='color:green'>角色授权</span></a> | 
                            <a title="确定删除该角色" target="ajaxTodo" href="<?php echo U('Web/del',array('id'=>code($list['id'],1),'db'=>code('Role',1),'sign'=>code('1',1)));?>" style='color:red'>删除</a>                       
                        </td>
                    </tr><?php endif; endforeach; endif; ?>
        </tbody>        
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>显示</span>
            <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
            <span>条，共<?php echo ($totalCount); ?>条</span>          
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="6"  currentPage="<?php echo ($currentPage); ?>"></div>
    </div>
</div>