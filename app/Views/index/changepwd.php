旧密码：<input name="old_pwd" type="password" ><br>
新密码：<input name="new_pwd" type="password" ><br>
重复密码：<input name="new_pwd_repeat" type="password" ><br>
<input type="button" id="pwd_update_button" value="提交">
<script language="javascript" type="text/javascript">
	$("#pwd_update_button").click(function(){
		var url = "<?php echo $this->getModuleUrl()?>/dochangepwd";
		var data = new Object();
		data.old_pwd = $("input[name='old_pwd']").val();
		data.new_pwd = $("input[name='new_pwd']").val();
		data.new_pwd_repeat = $("input[name='new_pwd_repeat']").val();
		if(data.new_pwd!=data.new_pwd_repeat){
			notice("新密码不一致");
			return ;
		}
		$.post(
			url,
			data,
			function(return_data){
				if(return_data.success){
					notice("更新成功！");
				}else{
					notice(return_data.message);
				}
			},
			"json"
		);
		
	});
</script>