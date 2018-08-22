<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" method="post" action="<?php echo U();?>"> 
    <input type="hidden" name="pageNum" value="<?php echo ($_REQUEST["pageNum"]); ?>" />
    <input type="hidden" name="numPerPage" value="<?php echo ($_REQUEST["numPerPage"]); ?>" /> 
    <input type="hidden" name="_order" value="<?php echo ($order); ?>" />
    <input type="hidden" name="_sort" value="<?php echo ($sort); ?>" />  
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST["listRows"]); ?>"/>
</form>


<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="/Node/index" method="post">
	<div class="searchBar">		
            <table class="searchContent">
                <tr>
                    <td>
                        节点名：<input type="text" name="name" value="<?php echo ($_POST['name']); ?>"/>
                    </td>
                    <td>
                        <select class="combox" name="p_id">
                            <option value="">父节点</option>
                            <?php if(is_array($topNoList)): foreach($topNoList as $key=>$topNoList): ?><option value="<?php echo ($topNoList["id"]); ?>" <?php if($_POST['p_id']== $topNoList['id']): ?>selected<?php endif; ?>><?php echo ($topNoList["name"]); ?></option><?php endforeach; endif; ?>
                        </select>
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
            <li><a class="add" href="/Node/show" target="dialog" mask="true"><span>添加</span></a></li>                      
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="id[]" href="/Node/del" class="delete"><span>批量删除</span></a></li>                  
            <li><a class="edit" href="/Node/show/id/{l_id}" target="dialog" mask="true" warn="请选择一条信息"><span>修改</span></a></li>
            <li class="line">line</li>
        </ul>
    </div>
    <table class="table" width="100%" layoutH="138">
        <thead>
            <tr>
                <th width="22"><input type="checkbox" group="id[]" class="checkboxCtrl"></th>
                <th width="80"  align="center">序号</th>
                <th width="120" >节点名称</th>
                <th width="200" >控制器</th>
                <th width="100" >方法</th>
                <th width="100" orderField="level" align="center" <?php if($_REQUEST['_order'] == 'level'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>层级</th>
                <th width="100" orderField="sort_" align="center" <?php if($_REQUEST['_order'] == 'sort_'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>排序</th>
                <th width="80"  orderField='p_id' <?php if($_REQUEST['_order'] == 'p_id'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?> align="center">父节点</th>
                <th width="80"  orderField='add_time' <?php if($_REQUEST['_order'] == 'add_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?> align="center">添加时间</th>
                <th width="100" align="center">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php $i=1;?> 
            <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr target="l_id" rel="<?php echo ($list["id"]); ?>">
                <td><input name="id[]" value="<?php echo ($list["id"]); ?>" type="checkbox"></td>
                <td><?php echo ($key+1); ?></td>
                <td>
                    <?php if($list["level"] == 0): ?><div style='color:blue'><?php echo ($list["name"]); ?></div>
                    <?php else: ?>
                        <div style='color:green'>--<?php echo ($list["name"]); ?></div><?php endif; ?>
                </td>
                <td><?php echo ($list["controller"]); ?></td>
                <td><?php echo ($list["action"]); ?></td>
                <td>
                    <?php if(($list['level']) == "0"): ?><div style='color:blue'>顶级节点</div>
                    <?php else: ?>
                        <div style='color:green'>次级节点</div><?php endif; ?>
                </td>
                <td><?php echo ($list["sort_"]); ?></td>
                <td><?php echo (getNodeName($list["p_id"])); ?></td>
                <td><?php echo (substr($list["add_time"],0,10)); ?></td>           
                <td>
                    <a title="确定删除该记录" target="ajaxTodo" href="<?php echo U('Node/del',array('id'=>code($list['id'],1)));?>" style='color: red'>删除</a>                       
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