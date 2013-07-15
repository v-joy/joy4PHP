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
			$.post(
				"",
				data,
				function(return_data){
					log(return_data);
				}
			);
		}
	});
	
	$("#addAction").click(function(){
		
	});
	
	$("#updateAction").click(function(){
		
	});
	
});