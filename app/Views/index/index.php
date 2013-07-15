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
$(document).ready(function(e) {
    var curent_table = "<?php echo $_GET["table"];?>";
	if(curent_table!=""){
		$("#table_list").val(curent_table);
	}
	$("#table_list").change(function(elem){
		var table_selected = $(this).val();
		if(table_selected!=""){
			window.location.href = "<?php echo $this->getActionUrl() ?>?table="+table_selected;
		}
	});
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
    <div id="user-tools"> 欢迎， <strong>admin</strong>. <a href="<?php echo $this->getModuleUrl()?>/changpwd"> 修改密码</a> / <a href="<?php echo $this->getIndexUrl()?>/login/logout/"> 注销</a> </div>
  </div>
  <!-- END Header -->
  
  <div class="breadcrumbs"> 数据库名称： &rsaquo; <?php echo $this->dbname;?> </div>
  
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
        <form id="changelist-form" action="" method="post">
          <div class="actions">
            <label>表:
              <select id="table_list" name="action">
                <option value="" selected="selected">---------</option>
                <?php 
					$tables = $this->tables;
					foreach($tables as $table){
						echo '<option value="'.$table.'">'.$table.'</option>';
					}
				?>
              </select>
            </label>
            <input type="hidden" class="select-across" value="0" name="select_across" />
            <!--<button type="submit" class="button" title="Run the selected action" name="index" value="0">执行</button>-->
<?php if(is_array($this->columns)){$columns = $this->columns; ?>
			共 <?php echo $this->count; ?> 条记录
              <!--<label for="searchbar"><img src="<?php echo $this->base_path;?>/Public/img/icon_searchbox.png" alt="Search" /></label>
              <input type="text" size="40" name="q" value="" id="searchbar" />
              <input type="submit" value="搜索" />-->
              <span style="margin:auto 50px auto 100px;">操作列表：</span>
              <input type="button" id="selectAllAction" value="全选" />
              <input type="button" id="selectNoneAction" value="全不选" />
              <input type="button" id="deleteAction" value="删除" />
              <input type="button" id="addAction" value="增加" />
              <input type="button" id="updateAction" value="修改" />
          <table cellspacing="0" id="result_list">
            <thead>
              <tr>
                <th class="action-checkbox-column"> <input type="checkbox" id="action-toggle" /></th>
<?php 
$primary_key = "id";
foreach($columns as $column){
	echo "<th> <a > ".$column['Field']." </a></th>";
	if($column["Key"]=="PRI"){
		$primary_key = $column['Field'];
	}
}?>
              </tr>
            </thead>
            <tbody>
            	<tr class="row2">
                	 <td class="action-checkbox"></td>
<?php 
foreach($columns as $column){
					  echo "<td> <a > ".$column['Type']."</a></th>";
					  
}?>
                </tr>
 <?php 
 		$data = $this->data;
		$i=0;
 		foreach($data as $row){$i++;?>
  				<tr class="row<?php echo $i%2;?>">
                <td class="action-checkbox"><input type="checkbox" class="action-select" value="<?php echo $row[$primary_key]?>" name="_selected_checkbox" /></td>
                <?php foreach($row as $feild){
					echo "<td> <a > ".$feild." </a></td>"; 
				}?>
                </tr>
 <?php }?>           
            </tbody>
          </table>
          <?php }else{echo "请选择数据表";}?>
          <p class="paginator">  共有 <?php echo $this->page_total?> 页 < <?php for($page=1;$page<=$this->page_total;$page++) echo "<a href=\"".$this->getActionUrl()."?table=".$_GET["table"]."&page={$page}\" >{$page}</a> "; ?> ></p>
        </form>
      </div>
    </div>
    <br class="clear" />
  </div>
  <!-- END Content -->
  
  <div id="footer"></div>
</div>
<!-- END Container -->

</body>
</html>
