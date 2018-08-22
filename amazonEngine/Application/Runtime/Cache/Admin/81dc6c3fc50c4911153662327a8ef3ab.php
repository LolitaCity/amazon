<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" method="post" action="<?php echo U();?>"> 
    <input type="hidden" name="pageNum" value="<?php echo ($_REQUEST["pageNum"]); ?>" />
    <input type="hidden" name="numPerPage" value="<?php echo ($_REQUEST["numPerPage"]); ?>" /> 
    <input type="hidden" name="_order" value="<?php echo ($order); ?>" />
    <input type="hidden" name="_sort" value="<?php echo ($sort); ?>" />  
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST["listRows"]); ?>"/>
</form>


<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="/Web/visit" method="post">
	<div class="searchBar">		
            <table class="searchContent">
                <tr>
                    <td>
                        图片名称<input type="text" name="name" value="<?php echo ($_POST['name']); ?>"/>
                    </td>
                    <td>
                        添加时间<input type="text" name="add_time" value="<?php echo ($_POST['add_time']); ?>" class="date" readonly="true"/>
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
            <li><a class="add" href="<?php echo U('Web/show',array('sign'=>code('3',1),'db'=>code('Banner',1)));?>" target="dialog" mask="true" width='600' height='400'><span >添加</span></a></li>                      
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="id[]" href="<?php echo U('Web/del',array('db'=>code('Banner',1)));?>" class="delete"><span>批量删除</span></a></li>                  
            <li><a class="edit" href="/Web/show/id/{l_id}/sign/<?php echo code('3',1);?>/db/<?php echo code('Banner',1)?>" target="dialog" mask="true" warn="请选择一条信息" width='600' height='400'><span>修改</span></a></li>
            <li class="line">line</li>
        </ul>
    </div>
    <table class="table" width="80%" layoutH="108">
        <thead>
            <tr>         
                <th width="22"><input type="checkbox" group="id[]" class="checkboxCtrl"></th>
                <th width="60"  align="center">序号</th>
                <th width="80" align="center">名称</th>               
                <th width="100" align="center">图片</th> 
                <th width="120" >超链接</th> 
                <th width="60" orderField='sort_' <?php if($_REQUEST['_order'] == 'sort_'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?> align="center">排序</th> 
                <th width="60" align="center">状态</th>
                <th width="88" orderField='add_time' <?php if($_REQUEST['_order'] == 'add_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?> align="center">添加时间</th> 
                <th width="150" align="center">操作</th>
        </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr target="l_id" rel="<?php echo ($list["id"]); ?>">
                    <td><input name="id[]" value="<?php echo ($list["id"]); ?>" type="checkbox"></td>
                    <td><?php echo ($key+1); ?></td>
                    <td><?php echo ($list["name"]); ?></td>          
                    <td class="imgs"><img src="<?php echo (PUBLIC_URL); ?>Upload/<?php echo ($list["small_image"]); ?>" width="96px"/></td>
                    <td><?php echo ($list["imgurl"]); ?></td>
                    <td><?php echo ($list["sort_"]); ?></td>
                    <td><?php echo (getStatus($list["status"])); ?></td>
                    <td><?php echo (substr($list["add_time"],0,10)); ?></td>
                    <td>
                        <?php if(($list['status']) == "1"): ?><a href="<?php echo U('Web/changeStatus',array('id'=>code($list['id'],1),'status'=>code('2',1),'db'=>code('Banner',1)));?>" target="ajaxTodo" style='color:#c6ff00'><span>禁用</span></a> |
                        <?php else: ?>
                            <a href="<?php echo U('Web/changeStatus',array('id'=>code($list['id'],1),'status'=>code('1',1),'db'=>code('Banner',1)));?>" target="ajaxTodo" style='color:green'><span>启用</span></a> |<?php endif; ?>
                        <a title="确定删除该图片" target="ajaxTodo" href="<?php echo U('Web/del',array('id'=>code($list['id'],1),'db'=>code('Banner',1)));?>" style='color:red'>删除</a>                       
                    </td>
                </tr><?php endforeach; endif; ?>
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
<style type="text/css" media="screen">
    .imgs div{height:100px!important;}
</style>