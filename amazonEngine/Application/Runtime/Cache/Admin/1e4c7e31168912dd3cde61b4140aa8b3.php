<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" method="post" action="<?php echo U();?>"> 
    <input type="hidden" name="pageNum" value="<?php echo ($_REQUEST["pageNum"]); ?>" />
    <input type="hidden" name="numPerPage" value="<?php echo ($_REQUEST["numPerPage"]); ?>" /> 
    <input type="hidden" name="_order" value="<?php echo ($order); ?>" />
    <input type="hidden" name="_sort" value="<?php echo ($sort); ?>" />  
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST["listRows"]); ?>"/>
</form>


<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="/MinimumPrice/productUrlList" method="post">
	<div class="searchBar">		
            <table class="searchContent">
                <tr>
                    <td>
                        URL:<input type="text" name="product_url" class="date" readonly="true" value="<?php echo ($_POST['product_url']); ?>"/>
                    </td>
                    <td>
                        Add time:<input type="text" name="add_time" class="date" readonly="true" value="<?php echo ($_POST['add_time']); ?>"/>
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
            <li><a class="add" href="<?php echo U('MinimumPrice/show',array('sign'=>code('6',1),'db'=>code('ProductUrlList',1)));?>" width='500' height='200'  target="dialog" mask="true"><span>Add</span></a></li>                      
            <li><a title="Are you sure you want to delete?" target="selectedTodo" rel="id[]" href="<?php echo U('MinimumPrice/del',array('db'=>code('ProductUrlList',1)));?>" class="delete"><span>Batch delete</span></a></li>                  
            <li><a class="edit" href="/MinimumPrice/show/id/{l_id}/db/<?php echo code('ProductUrlList',1)?>/sign/<?php echo code('6',1);?>" width='500' height='200' target="dialog" mask="true" warn="Please select a message"><span>Edit</span></a></li>
            <li class="line">line</li>
        </ul>
    </div>
    <table class="table" width="85%" layoutH="138">
        <thead>
            <tr>
                <th width="18" align="center"><input type="checkbox" group="id[]" class="checkboxCtrl"></th>
                <th width="20"  align="center">No.</th>
                <th width="320" >url</th>  
                <th width="60"  orderField='add_time' <?php if($_REQUEST['_order'] == 'add_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?> align="center">Add time</th>
                <th width="60" align="center">Operation</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1;?> 
            <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr target="l_id" rel="<?php echo ($list["id"]); ?>">
                <td><input name="id[]" value="<?php echo ($list["id"]); ?>" type="checkbox"></td>
                <td><?php echo ($key+1); ?></td>
                <td><?php echo ($list["product_url"]); ?></td>
                <td><?php echo ($list["add_time"]); ?></td>           
                <td>
                    <a title="Are you sure you want to delete" target="ajaxTodo" href="<?php echo U('MinimumPrice/del',array('id'=>code($list['id'],1),'db'=>code('ProductUrlList',1)));?>" style="color: red">Delete</a>                       
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