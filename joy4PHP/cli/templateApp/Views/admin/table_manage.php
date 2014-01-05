<table width="100%" id="frame_table">
    <tr>
        <td>名称</td>
        <td>是否显示</td>
    </tr>
<?php
foreach($this->columns as $column) {?>
    <tr>
        <td><?php echo $column['name'];?></td>
        <td><input type="text" name="<?php echo $column['name'];?>" value="<?php echo $column['show_name'];?>" /></td>
    </tr>
<?php }?>
	<tr>
        <td><input type="button" onClick="frame_submit()" value="确定"></td>
        <td><input type="button" onClick="frame_cancle()" value="取消"></td>
    </tr>
</table>
<script type="text/javascript" language="javascript">
function frame_submit(){
	var url = "<?php echo $this->getModuleUrl()?>/table_manage?table=<?php echo $this->table; ?>";
		var data = new Object();
		var data_value = new Array();
		var data_key = new Array();
		$("#frame_table input[type=text]").each(function(index, element) {
			var val = $(this).val();
			var key = $(this).parent().prev().html();
			data_value.push(val);
			data_key.push(key);
        });
		data.data_value = data_value;
		data.data_key = data_key;
		$.post(
			url,
			data,
			function(return_data){
				if(return_data.success){
					window.location.reload();
				}else{
					notice(return_data.message);
				}
			},
			"json"
		);
}
function frame_cancle(){
	notice_end();
}
</script>