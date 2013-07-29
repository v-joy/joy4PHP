<table width="100%" id="frame_table">
    <tr>
        <td>字段</td>
        <td>类型</td>
        <td>数据</td>
    </tr>
<?php 
foreach($this->columns as $column) {?>
    <tr>
        <td><?php echo $column['Field'];?></td>
        <td><?php echo $column['Type'];?></td>
        <td><input type="text" name="<?php echo $column['Field'];?>" value="" ></td>
    </tr>
<?php }?>
	<tr>
        <td><input type="button" onClick="frame_submit()" value="确定"></td>
        <td><input type="button" onClick="frame_cancle()" value="取消"></td>
    </tr>
</table>
<script type="text/javascript" language="javascript">
function frame_submit(){
	var url = "<?php echo $this->getModuleUrl()?>/add?table=<?php echo $this->table; ?>";
		var data = new Object();
		data.keys = "";
		data.val = "";
		$("#frame_table input[type=text]").each(function(index, element) {
			if($(this).val() != ""){
				//mark : 这样会出现的问题是：如果里面会出现逗号，会出现错误
				var key = ($(this).attr("name")+",");
				var val = ($(this).val()+",");
				data.keys += key;
				data.val += val;
			}
        });
		$.post(
			url,
			data,
			function(return_data){
				log(return_data);
				if(return_data.success){
					//mark 应该更友好地显示数据
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