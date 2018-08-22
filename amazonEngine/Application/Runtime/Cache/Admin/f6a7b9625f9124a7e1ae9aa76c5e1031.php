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
                        按年检索<input type="text" name="add_time_y" value="<?php echo (substr($_POST['add_time_y'],0,4)); ?>"/>
                    </td>
                    <td>
                        按年月检索<input type="text" name="add_time_ym" value="<?php echo (substr($_POST['add_time_ym'],0,7)); ?>" alt="2016-12" minlength="7" maxlength="7"/>
                    </td>
                    <td>
                        具体日期<input type="text" name="add_time_ymd" value="<?php echo ($_POST['add_time_ymd']); ?>" class="date" readonly="true"/>
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
   
    <table class="table" width="60%" layoutH="108">
        <thead>
            <tr>                
                <th width="60"  align="center">序号</th>
                <th width="80" >IP</th>               
                <th width="68" >地区</th> 
                <th width="68" >访问人</th> 
                <th width="88" orderField='add_time' <?php if($_REQUEST['_order'] == 'add_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?> align="center">访问时间</th> 
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr target="l_id" rel="<?php echo ($list["id"]); ?>">
                    <td><?php echo ($key+1); ?></td>
                    <td><?php echo ($list["ip"]); ?></td>          
                    <td><?php echo ($list["area"]); ?></td>
                    <td><?php echo (getUsername($list["u_id"])); ?></td>
                    <td><?php echo (substr($list["add_time"],0,10)); ?></td>
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