<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" method="post" action="<?php echo U();?>"> 
    <input type="hidden" name="pageNum" value="<?php echo ($_REQUEST["pageNum"]); ?>" />
    <input type="hidden" name="numPerPage" value="<?php echo ($_REQUEST["numPerPage"]); ?>" /> 
    <input type="hidden" name="_order" value="<?php echo ($order); ?>" />
    <input type="hidden" name="_sort" value="<?php echo ($sort); ?>" />  
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST["listRows"]); ?>"/>
</form>


<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="/System/loginLog" method="post">
	<div class="searchBar">		
            <table class="searchContent">
                <tr>
                    <td>
                        登录人：<input type="text" name="user_id" value="<?php echo ($_POST['user_id']); ?>"/>
                    </td>                    
                    <td>
                        添加时间：<input type="text" name="add_time" class="date" readonly="true" value="<?php echo ($_POST['add_time']); ?>"/>
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
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="id[]" href="/System/delete/db/<?php echo code('Log',1);?>" class="delete"><span>批量删除</span></a></li>                  
            <li class="line">line</li>
        </ul>
    </div>
    <table class="table" width="50%" layoutH="138">
        <thead>
            <tr>
                <th width="22"><input type="checkbox" group="id[]" class="checkboxCtrl"></th>
                <th width="50"  align="center">序号</th>
                <th width="80" orderField='user_id' <?php if($_REQUEST['_order'] == 'user_id'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?> align="center">登录人</th>
                <th width="120" align="center">ip</th>
                <th width="100" align="center">地点</th>
                <th width="80"  orderField='add_time' <?php if($_REQUEST['_order'] == 'add_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?> align="center">时间</th>
                <th width="70" align="center">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1;?> 
            <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr target="l_id" rel="<?php echo ($list["id"]); ?>">
                <td><input name="id[]" value="<?php echo ($list["id"]); ?>" type="checkbox"></td>
                <td><?php echo ($key+1); ?></td>
                <td><?php echo (getAdminname($list["user_id"])); ?></td>
                <td><?php echo ($list["ip"]); ?></td>
                <td><?php echo ($list["addr"]); ?></td>
                <td><?php echo (substr($list["add_time"],0,10)); ?></td>
                <td>
                    <a title="确定删除该记录" target="ajaxTodo" href="<?php echo U('System/delete',array('id'=>code($list['id'],1),'db'=>code('Log',1)));?>" style='color:red'>删除</a>                       
                </td>
            </tr>
            <?php $i++; endforeach; endif; ?>
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