<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" method="post" action="<?php echo U();?>"> 
    <input type="hidden" name="pageNum" value="<?php echo ($_REQUEST["pageNum"]); ?>" />
    <input type="hidden" name="numPerPage" value="<?php echo ($_REQUEST["numPerPage"]); ?>" /> 
    <input type="hidden" name="_order" value="<?php echo ($order); ?>" />
    <input type="hidden" name="_sort" value="<?php echo ($sort); ?>" />  
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST["listRows"]); ?>"/>
</form>


<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="/MinimumPrice/currencyList" method="post">
	<div class="searchBar">		
            <table class="searchContent">
                <tr>
                    <td>
                        Currency Name<input type="text" name="name" value="<?php echo ($_POST['name']); ?>"/>
                    </td>
                </tr>
            </table>
            <div class="subBar">
                <ul>
                    <li><div class="buttonActive"><div class="buttonContent"><button type="submit">Search</button></div></div></li>
                </ul>
            </div>
	</div>
    </form>
</div>
<div class="pageContent">
    <div class="panelBar">
        <ul class="toolBar">            
            <li><a class="add" href="<?php echo U('MinimumPrice/show',array('sign'=>code('3',1),'db'=>code('Currency',1)));?>" target="dialog" mask="true" width='360' height='200'><span>Add</span></a></li>                      
            <li><a title="Are you sure you want to delete" target="selectedTodo" rel="id[]" href="/MinimumPrice/del/sign/<?php echo code('3',1)?>/db/<?php echo code('Currency',1);?>" class="delete"><span>Batch delete</span></a></li>                  
            <li><a class="edit" href="/MinimumPrice/show/id/{l_id}/sign/<?php echo code('3',1);?>/db/<?php echo code('Currency',1);?>" target="dialog" mask="true" warn="请选择一条信息" width='360' height='200'><span>Edit</span></a></li>
            <li class="line">line</li>
        </ul>
    </div>
    <table class="table" width="600" layoutH="138">
        <thead>
            <tr>
                <th width="22"><input type="checkbox" group="id[]" class="checkboxCtrl"></th>
                <th width="40"  align="center">No.</th>
                <th width="100" align="center">Currency name</th> 
                <th width="100" align="center">Currency symbol</th>
                <th width="140"  orderField='add_time' <?php if($_REQUEST['_order'] == 'add_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?> align="center">Warning time</th>
                <th width="150" align="center">Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr target="l_id" rel="<?php echo ($list["id"]); ?>">
                    <td><input name="id[]" value="<?php echo ($list["id"]); ?>" type="checkbox"></td>
                    <td><?php echo ($key+1); ?></td>
                    <td><?php echo ($list["name"]); ?></td> 
                    <td><?php echo ($list["currency_symbol"]); ?></td>
                    <td><?php echo ($list["add_time"]); ?></td>
                    <td>
                        <a href="<?php echo U('MinimumPrice/show',array('id'=>code($list['id'],1),'sign'=>code('3',1),'db'=>code('Currency',1)));?>" target="dialog" height="600" width="500"><span style='color:green'>Edit</span></a> | 
                        <a title="Delete the currency information" target="ajaxTodo" href="<?php echo U('MinimumPrice/del',array('id'=>code($list['id'],1),'db'=>code('Currency',1),'sign'=>code('3',1)));?>" style='color:red'>Delete</a>                       
                    </td>
                </tr><?php endforeach; endif; ?>
        </tbody>        
    </table>
    <div class="panelBar">
        <div class="pages">
            <span>Show </span>
            <select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
            <span> A total of <?php echo ($totalCount); ?></span>          
        </div>
        <div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="6"  currentPage="<?php echo ($currentPage); ?>"></div>
    </div>
</div>