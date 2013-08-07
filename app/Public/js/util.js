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

function update_td(elem){
	var $elem = $(elem);
	if($elem.attr("old_data")==$elem.val()){
		td_recover("back");
	}else{
		if(window.confirm("确定要修改吗？")){
			//更新数据。。。。。
			var pri = $elem.parent().parent().attr("id").substr(3);
			var i=-1;
			var $pre = $elem.parent();
			while($pre.is("td") && i<100){
				i++;
				$pre = $pre.prev();
			}
			if(i>=100 || i<0){
				show_error("出现错误！请想编码人员反馈！");
				return;
			}
			var r_key = $("#result_list th").eq(i).html();
			var r_val = $elem.val();
			update_record(r_key,r_val,pri);
		}else{
			td_recover("back");
		}
	}
}
function update_record(r_key,r_val,pri){
	
	var data = new Object();
	data.key = r_key;
	data.val = r_val;
	data.pri_key = window.primaryKey?window.primaryKey:"id";
	data.pri_val = pri;
	data.action = "update";
	var url = window.location.href;
	$.post(
		url,
		data,
		function(return_data){
			//notice(return_data);return;
			if(return_data.success){
				td_recover("update");
			}else{
				show_error("更新失败！"+return_data.mess);
				td_recover("back");
			}
		},
		"json"
	);
}
function td_recover(type){
	//type 为 update 或者back
	var $input = $(".updating_td");
	var $td = $input.parent();
	var html_content;
	if(type=="update"){
		html_content=$input.val();
	}else{
		html_content=$input.attr("old_data")
	}
	$td.html("<a>"+html_content+"</a>");
}

//弹出隐藏层
function notice(msg,type){
	if(typeof(type)=="undefined" || type=="message"){
		type="message";
		$("#notice_content").html(msg);
	}else if(type=="url"){
		$("#notice_content").html("加载中。。。。");
	}
	$("#notice_bg").fadeTo(500,0.7);
	$("#notice_main").slideDown();
	$("#notice_bg").height($(document).height()).width($(document).width());
	
	if(type=="url"){
		$.get(msg,function(data){
			window.setTimeout(function(){
				$("#notice_content").html(data);
			},500);
		});
	}
	return false;
};
//关闭弹出层
function notice_end(){
	$("#notice_content").html("");
	$("#notice_main").slideUp(500);
	$("#notice_bg").fadeOut(500);
	return false;
}

function show_error(msg){
	//mark: to do
	return notice("===========出现错误============<br>"+msg);
}

function load_page(url_val){
	return notice(url_val,"url");
}

function menu(x,y){
	x = x + "px";
	y = y + "px";
	var data = {"left":x,"top":y};
	$("#r_menu").css(data);
	$("#r_menu").slideDown(200);	
}
function show_search_suggest(x,y,val){
	x = x + "px";
	y = y + "px";
	$("#search_suggest .search_value").html(val);
	$("#search_suggest").css({left:x,top:y});
	$("#search_suggest").slideDown(200);
}

function close_search_suggest(){
	$("#search_suggest").slideUp(200);
}


function menu_end(){
	$("#r_menu").slideUp(200);
}

$(document).ready(function(e) {
	
	$("#table_list").change(function(elem){
		var table_selected = $(this).val();
		if(table_selected!=""){
			window.location.href = action_url+"?table="+table_selected;
		}
	});
	
	$("#result_list tr td").dblclick(function(e) {
        var $this_td = $(this);
		var old_html = $this_td.children("a").html();
		if(old_html!=null){
			$this_td.html("<input class='updating_td'  old_data='"+old_html+"' value='"+old_html+"' onblur='update_td(this)' />");
			$(".updating_td").focus();
		}
    });
	
	$("#result_list tr td").mousedown(function(e){
		$(".curent_td").removeClass("curent_td");
		$(this).addClass("curent_td");
		e = e || event;
		if(e.which==3){
			menu(e.clientX+$(document).scrollLeft(),e.clientY+$(document).scrollTop());
        	e.cancelBubble = true;
       		e.returnValue = false;
			return false; 
		}else{
			menu_end();	
		}
	})
	
	$("#notice_close").click(function(){
	notice_end();
	});
	
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
			if(ids==""){
				show_error("请选择需要删除的数据！");
				return;
			}
			var data = new Object();
			data.action="delete";
			data.ids = ids;
			data.pri = window.primaryKey?window.primaryKey:"id";
			var url = window.location.href;
			$.post(
				url,
				data,
				function(return_data){					if(return_data.success){
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
		load_page(module_url+"/add?table="+curent_table);
	});
	
	$("#rowManageAction").click(function(){
		load_page(module_url+"/column_manage?table="+curent_table);
	});
	
	$("#tableManageAction").click(function(){
		load_page(module_url+"/table_manage?table="+curent_table);
	});
	
	$("#columnManageAction").click(function(){
		load_page(module_url+"/description_manage?table="+curent_table);
	});
	
	$("#searchAction").click(function(e){
		e = e||event;
		e.preventDefault();
		var $this = $(this);
		
		if($this.val()=="显示搜索"){
			$("#search_row").show();
			$("#doSearchAction").show();
			$this.val("隐藏搜索");
		}else{
			$("#search_row").hide();
			$("#doSearchAction").hide();
			$this.val("显示搜索");
		}
	});
	
	$("#showAllAction").click(function(e){
		window.location.href = action_url+"/table/"+curent_table;
	});
	
	$("#r_menu_edit").click(function(e){
		e = e || event;
		e.preventDefault();
		$(".curent_td").trigger("dblclick");
		menu_end();
	});
	
	$("#r_menu_view").click(function(e){//mark
		e = e || event;
		e.preventDefault();
		var pri = $(".curent_td").parent().attr("id").substr(3);
		load_page(module_url+"/view?table="+curent_table+"&pri="+pri);
		menu_end();
	});
	
	$(".search_field_input").keyup(function(e) {
        e = e || event;
		$elem = $(this).parent();
		var val = $(this).val();
		//mark 
//		if(val){
			show_search_suggest($elem.offset().left,$elem.offset().top+$elem.height()+10,val);
//		}else{
//			close_search_suggest();
//		}
    });
	
	$(".search_field_input").focus(function(e) {
        $(this).trigger("keyup");
    });

	$(".search_field_input").blur(function(e) {
		close_search_suggest();
    });
});

//禁止邮件菜单
document.oncontextmenu = function(e){
	e = e || event;
	e.returnValue = false;
	return false;
};