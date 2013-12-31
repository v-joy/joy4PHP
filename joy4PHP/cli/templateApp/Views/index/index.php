<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn" xml:lang="zh-cn" >
<head>
<title>PXE数据库管理</title>
<?php
$this->css(array("base","changelists"));
$this->js(array("jquery.min","util"));
?>
<!--[if lte IE 7]><link rel="stylesheet" type="text/css" href="/media/css/ie.css" /><![endif]-->
<script type="text/javascript">
curent_table = "";
module_url="<?php echo $this->getModuleUrl()?>";
action_url="<?php echo $this->getActionUrl()?>";
$(document).ready(function(e) {
	curent_table = "<?php echo $_GET["table"];?>";
	if(curent_table!=""){
		$("#table_list").val(curent_table);
	}

});


</script>
<meta name="robots" content="NONE,NOARCHIVE" />
</head>
<body class="change-list">

<!-- Container -->
<div id="container">
<!-- Header -->
<div id="header">
  <div id="branding">
    <h1 id="site-name">PXE数据库管理</h1>
  </div>
  <div id="user-tools"> 欢迎， <strong>admin</strong>. <a onclick="return load_page('<?php echo $this->getModuleUrl()?>/changepwd')" style="cursor:pointer; color:white;"> 修改密码</a> / <a href="<?php echo $this->getIndexUrl()?>/login/logout/"> 注销</a> </div>
</div>
<!-- END Header -->

<div class="breadcrumbs" > 数据库名称： &rsaquo; <?php echo $this->dbname;?> </div>

<!-- Content -->
<div id="content" class="flex">
  <h1>选择 数据库的表 来修改</h1>
  <div id="content-main"> 
    <!--<ul class="object-tools">
        <li> <a href="add/" class="addlink"> 增加 Server </a> </li>
      </ul>-->
    <div class="module" id="changelist"> 
      <!--<div id="toolbar">
          <form id="changelist-search" action="" method="post">
            <div>
              <label for="searchbar"><img src="<?php echo $this->base_path;?>/Public/img/icon_searchbox.png" alt="Search" /></label>
              <input type="text" size="40" name="q" value="" id="searchbar" />
              <input type="submit" value="搜索" />
            </div>
          </form>
        </div>-->
      <form id="changelist-form" action="<?php echo $this->getActionUrl()."/table/".$_GET["table"]; ?>" method="get">
        <div class="actions">
        <label>模块:
          <select id="table_list">
            <option value="" selected="selected">---------</option>
            <?php 
					$tables = $this->tables;
					foreach($tables as $table){
						echo '<option value="'.$table["name"].'">'.$table["show_name"].'</option>';
					}
				?>
          </select>
        </label>
        <!--<button type="submit" class="button" title="Run the selected action" name="index" value="0">执行</button>-->
        <?php if(is_array($this->columns)){$columns = $this->columns; ?>
        共 <?php echo $this->count; ?> 条记录 
        <!--<label for="searchbar"><img src="<?php echo $this->base_path;?>/Public/img/icon_searchbox.png" alt="Search" /></label>
              <input type="text" size="40" name="q" value="" id="searchbar" />
              <input type="submit" value="搜索" />--> 
        <span style="margin:auto 20px auto 100px;">操作列表：</span>
        <input type="button" id="selectAllAction" value="全选" />
        <input type="button" id="selectNoneAction" value="全不选" />
        <input type="button" id="deleteAction" value="删除" />
        <input type="button" id="addAction" value="增加" />
        <input type="button" id="searchAction" value="显示搜索" />
        <input type="submit" name="is_search" id="doSearchAction" class="hide" value="搜索" />
        <?php if(isset($_GET["is_search"])) {?> 
        <input type="button" id="showAllAction" value="显示全部" />
        <?php }?>
		<span style=" margin:auto 20px;">|</span>
        <input type="button" id="rowManageAction" value="管理显示列" />
        <input type="button" id="tableManageAction" value="管理模块名称" />
        <input type="button" id="columnManageAction" value="管理数据项名称" />
        <!--<input type="button" id="updateAction" value="修改" />-->
        <table cellspacing="0" id="result_list">
          <thead>
            <tr>
              <th class="action-checkbox-column"> <input type="checkbox" id="action-toggle" /></th>
              <?php 
$primary_key = $this->primary_key;
foreach($columns as $column){
	echo "<th> ".$column['show_name']."</th>";
}?>
            </tr>
          </thead>
          <tbody>
<!--            <tr class="row2">
              <td class="action-checkbox"></td>
              <?php 
foreach($columns as $column){
					  echo "<td>".$column['type']."</td>";
					  
}?>
            </tr>-->
            <!-- mark : 搜索用-->
            <tr id="search_row" class="row1 hide">
            	<td></td>
                <?php 
				foreach($columns as $column){?>
					 <td><input type="text" class="search_field_input"  name="<?php echo $column['Field']; ?>" /></td>
<?php			}?>             
            </tr>
            <?php 
 		$data = $this->data;
		$i=0;
 		foreach($data as $row){$i++;?>
            <tr class="row<?php echo $i%2;?>" id="tr_<?php echo $row[$primary_key] ?>">
              <td class="action-checkbox"><input type="checkbox" class="action-select" value="<?php echo $row[$primary_key]?>" name="_selected_checkbox" /></td>
              <?php foreach($row as $feild){
					echo "<td><a>".$feild."</a></td>"; 
				}?>
            </tr>
            <?php }?>
          </tbody>
        </table>
        <?php }else{echo "请选择数据表";}?>
        <p class="paginator"> 共有 <?php echo $this->page_total?> 页 <
          <?php for($page=1;$page<=$this->page_total;$page++) echo "<a href=\"".url_set_var("page",$page)."\" >{$page}</a> "; ?>
          ></p>
      </form>
    </div>
  </div>
  <br class="clear" />
</div>
<!-- END Content --> 
<script language="javascript" type="text/javascript">
  window.primaryKey="<?php echo $primary_key?>";
  </script>
<div id="footer"></div>

<!-- BEGIN 弹出层-->
<div id="notice_bg" class="black_overlay"> </div>
<div id="notice_main" class="white_content_small">
  <div id="notice_close"><img  src="<?php echo $this->base_path;?>/Public/img/close.jpg" alt="close" /></div>
  <div id="notice_content"></div>
</div>
<!-- END 弹出层 -->

<style type="text/css">
.skin {
	width : 200px;
	border : 1px solid gray;
	padding : 2px;
	height:auto;
	display:none;
	position : absolute !important;
	background-color:#CCC;
}
div.menuitems {
	margin:4px auto;
	border-bottom:1px solid #FFF;
	height:25px;
	line-height:25px;
}
div.menuitems a {
	text-decoration : none;
}
div.menuitems:hover {
	background-color : #c0c0c0;
}
</style>

<div id="r_menu" class="skin">
    <div class="menuitems" >
        <a href="#" id="r_menu_edit">编辑</a>
    </div>
    <div class="menuitems">
        <a href="#" id="r_menu_view">查看详情</a>
    </div>
</div>

<div id="search_suggest" class="skin">
    <div class="menuitems" >
        严格等于 '<span class="search_value"></span>'
    </div>
    <div class="menuitems">
        包含该值 '<span class="search_value"></span>'
    </div>
    <div class="menuitems">
        大于该值 '<span class="search_value"></span>'
    </div>
    <div class="menuitems">
        小于该值 '<span class="search_value"></span>'
    </div>
    <div class="menuitems">
        选择时间(开发中)
    </div>
</div>

</body>
</html>
