<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" method="post" action="<?php echo U();?>"> 
    <input type="hidden" name="pageNum" value="<?php echo ($_REQUEST["pageNum"]); ?>" />
    <input type="hidden" name="numPerPage" value="<?php echo ($_REQUEST["numPerPage"]); ?>" /> 
    <input type="hidden" name="_order" value="<?php echo ($order); ?>" />
    <input type="hidden" name="_sort" value="<?php echo ($sort); ?>" />  
    <input type="hidden" name="listRows" value="<?php echo ($_REQUEST["listRows"]); ?>"/>
</form>


<div class="pageHeader">
    <form rel="pagerForm" onsubmit="return navTabSearch(this);" action="/User/adminIndex" method="post">
	<div class="searchBar">		
            <table class="searchContent">
                <tr>
                    <td>
                        用户名：<input type="text" name="name"  value="<?php echo ($_POST['name']); ?>"/>
                    </td>
                    <td>
                        昵称：<input type="text" name="nickname" value="<?php echo ($_POST['nickname']); ?>"/>
                    </td>
                    <td>
                        电话：<input type="text" name="tel" value="<?php echo ($_POST['tel']); ?>"/>
                    </td>
                    <td>
                        邮箱：<input type="text" name="email" value="<?php echo ($_POST['email']); ?>"/>
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
            <li><a class="add" href="<?php echo U('User/show',array('sign'=>code('2',1),'db'=>code('Admin',1)));?>" target="dialog" mask="true" width='600' height='600'><span>添加用户</span></a></li>                      
            <li><a title="确实要删除这些记录吗?" target="selectedTodo" rel="id[]" href="<?php echo U('User/del',array('db'=>code('Admin',1)));?>" class="delete"><span>批量删除</span></a></li>                  
            <li><a class="edit" href="/User/show/id/{l_id}/sign/<?php echo code('2',1);?>/db/<?php echo code('Admin',1)?>" target="dialog" mask="true" warn="请选择一条信息" width='600' height='600'><span>修改用户</span></a></li>
            <li class="line">line</li>
        </ul>
    </div>
    <table class="table" width="85%" layoutH="138">
        <thead>
            <tr>
                <th width="22"><input type="checkbox" group="id[]" class="checkboxCtrl"></th>
                <th width="40"  align="center">序号</th>
                <th width="100" align="center">用户名</th>
                <th width="100" align="center">昵称</th>
                <th width="100" align="center">电话</th>
                <th width="120" align="center">邮箱</th>
                <?php if(($_SESSION['authId']) == "1"): ?><th width="40" align="center" orderField='id' <?php if($_REQUEST['_order'] == 'id'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>id</th>
                    <th width="100" align="center" orderField='last_logintime' <?php if($_REQUEST['_order'] == 'last_logintime'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>上次登录时间</th><?php endif; ?>
                <th width="100" align="center">角色</th>
                <th width="140" align="center" orderField='add_time' <?php if($_REQUEST['_order'] == 'add_time'): ?>class="<?php echo ($_REQUEST["_sort"]); ?>"<?php endif; ?>>注册日期</th>
                <th width="150" align="center">操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(is_array($list)): foreach($list as $key=>$list): ?><tr target="l_id" rel="<?php echo ($list["id"]); ?>">
                    <td><input name="id[]" value="<?php echo ($list["id"]); ?>" type="checkbox"></td>
                    <td><?php echo ($key+1); ?></td>
                    <td><?php echo ($list["name"]); ?></td>
                    <td><?php echo ($list["nickname"]); ?></td>
                    <td><?php echo ($list["tel"]); ?></td>
                    <td><?php echo ($list["email"]); ?></td>
                    <?php if(($_SESSION['authId']) == "1"): ?><td><?php echo ($list["id"]); ?></td>
                        <td><?php echo (date("Y-m-d",$list["last_logintime"])); ?></td><?php endif; ?>
                    <td><?php echo (getAdminRole($list["id"])); ?></td>
                    <td><?php echo (substr($list["add_time"],0,10)); ?></td>
                    <td>
                        <a title="确定删除该用户" target="ajaxTodo" href="<?php echo U('User/del',array('id'=>code($list['id'],1),'db'=>code('Admin',1)));?>" style='color:red'>删除</a>                       
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
</div><style type="text/css" media="screen">
    .imgs div{height:100px!important;}
</style>