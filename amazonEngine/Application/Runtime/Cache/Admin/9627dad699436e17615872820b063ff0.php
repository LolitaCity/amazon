<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" method="post" action="<?php echo U();?>"> 
    <input type="hidden" name="pageNum" value="<?php echo ($_REQUEST["pageNum"]); ?>" />
    <input type="hidden" name="numPerPage" value="<?php echo ($_REQUEST["numPerPage"]); ?>" /> 
    <input type="hidden" name="_order" value="<?php echo ($order); ?>" />
    <input type="hidden" name="_sort" value="<?php echo ($sort); ?>" />  
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST["listRows"]); ?>"/>
</form>


<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="/UrlCont/index" method="post">
	<div class="searchBar">		
            <table class="searchContent">
                <tr>
                    <td>
                        Add Time<input type="text" name="add_time" class="date" readonly="true" value="<?php echo ($_POST['add_time']); ?>"/>
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
            <li><a class="add" href="/UrlCont/show" target="dialog" mask="true"><span>Add</span></a></li>                      
            <li><a title="Are you sure you want to delete these records?" target="selectedTodo" rel="id[]" href="<?php echo U('UrlCont/del',array('db'=>code('urls',1)));?>" class="delete"><span>Batch delete</span></a></li>                  
            <li><a class="edit" href="/UrlCont/show/id/{l_id}/db/<?php echo code('urls',1)?>" target="dialog" mask="true" warn="Please select a message"><span>Edit</span></a></li>
            <li class="line">line</li>
        </ul>
    </div>
    <table class="table" width="80%" layoutH="138">
        <thead>
            <tr>
                <th width="14" align="center"><input type="checkbox" group="id[]" class="checkboxCtrl"></th>
                <th width="20"  align="center">No.</th>
                <th width="330" >url</th>  
                <th width="50"  orderField='add_time' <?php if($_REQUEST['_order'] == 'add_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?> align="center">Add Time</th>
                <th width="40" align="center">Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1;?> 
            <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr target="l_id" rel="<?php echo ($list["id"]); ?>">
                <td><input name="id[]" value="<?php echo ($list["id"]); ?>" type="checkbox"></td>
                <td><?php echo ($key+1); ?></td>
                <td><?php echo ($list["url_content"]); ?></td>
                <td><?php echo (substr($list["add_time"],0,10)); ?></td>           
                <td>
                    <a title="Delete the record" target="ajaxTodo" href="<?php echo U('UrlCont/del',array('id'=>code($list['id'],1),'db'=>code('urls',1)));?>" style="color: red">Delete</a>                       
                </td>
            </tr>
            <?php $i++; endforeach; endif; ?>
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