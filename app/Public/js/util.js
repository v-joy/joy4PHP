/*!
 * joy4php JavaScript Library v0.1
 * http://v-joy.net/
 *
 * Released under the MIT, BSD, and GPL Licenses.
 *
 */
if(typeof(console)=="undefined"){
	console = {
			log:function(){}
		};
		
}
function log(vars){
	return console.log(vars);
}
function show_error(msg){
	//mark: to do
	return notice("===========出现错误============<br>"+msg);
}
$(document).ready(function(e) {
	$("#selectAllAction").click(function(){
		$(":checkbox").attr("checked",true);
	});
	
	$("#selectNoneAction").click(function(){
		$(":checkbox").removeAttr("checked");
	});
	
    $("#deleteAction").click(function(){
		if(window.confirm("确定要删除吗？")){
			var ids = "";
			$("input[name='_selected_checkbox']:checked").each(function(index, element) {
                ids += $(this).val()+",";
            });
			var data = new Object();
			data.action="delete";
			data.ids = ids;
			data.pri = window.primaryKey?window.primaryKey:"id";
			var url = window.location.href;
			$.post(
				url,
				data,
				function(return_data){
					if(return_data.success){
						$("input[name='_selected_checkbox']:checked").each(function(index, element) {
							$(this).parent().parent().remove();
						});
					}else{
						show_error("删除失败");
					}
				},
				"json"
			);
		}
	});
	
	$("#addAction").click(function(){
		
	});
	
/*	$("#updateAction").click(function(){
		
	});*/
	
});