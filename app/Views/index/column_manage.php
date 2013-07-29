<table width="100%" id="frame_table">
    <tr>
        <td>字段</td>
        <td>是否显示</td>
    </tr>
<?php 
foreach($this->columns as $column) {?>
    <tr>
        <td><?php echo $column['Field'];?></td>
        <td><input type="checkbox" <?php 	if($column['Key']=="PRI"){?> disabled="disabled" <?php }?> name="fields[]" value=<?php echo "'".$column['Field']."' "; if($column["is_show"]) echo 'checked="checked"'?> ></td>
    </tr>
<?php }?>
	<tr>
        <td><input type="button" onClick="frame_submit()" value="确定"></td>
        <td><input type="button" onClick="frame_cancle()" value="取消"></td>
    </tr>
</table>
<script type="text/javascript" language="javascript">
function frame_submit(){
	var url = "<?php echo $this->getModuleUrl()?>/column_manage?table=<?php echo $this->table; ?>";
		var data = new Object();
		data.fields = "";
		$("#frame_table input[type=checkbox]").each(function(index, element) {
			if($(this).attr("checked"))  data.fields += ($(this).val()+","); 
        });
		$.post(
			url,
			data,
			function(return_data){
				if(return_data.success){
					log(1);
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