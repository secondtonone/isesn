$(document).ready(function(){
	
	function getList()
	{	
		var lists = $.ajax({
					type: "POST",
					url: "/app/scripts/lists/user_lists.php",
					data: "q=1",
					async: false
				}).responseText,
			list = JSON.parse(lists);
		return list;
	}
	
	function showErrorDialog(str)
	{	
		$(document.body).append('<div id="dialog-message" title="Внимание!"><div style="padding: 10px;margin-top:15px;" class="ui-state-error ui-corner-all"><span class="ui-icon  ui-icon-alert" style="float:left; margin:0 7px 50px 0;"></span>'+str+'</div></div>');
		$( "#dialog-message" ).dialog({
  			close: function( event, ui ) {
				$("#dialog-message").empty();
				$("#dialog-message").remove();
			}
		});
		setTimeout(function () {
					$("#dialog-message").empty();
					$("#dialog-message").remove();
				}, 7000);
				
		return false;
	}
	
	function getUsers()
	{	
		return $.ajax({
					type: "POST",
					url: "/app/scripts/jqgrid/admin_journal_getdata.php?q=3",
					async: false
				}).responseText;
	}
	
	function userListGenerate()
	{
		var users=getUsers();
		$(".list-preloader").hide();
		$('.user-list').html(users);
		
	}

	userListGenerate();
	
	setInterval(userListGenerate,150000);
	
	var selectList=getList(),
		selectType={value:selectList.rows.type,sopt:['eq']};
	
$("#notifications").jqGrid({
            url:"/app/scripts/jqgrid/admin_journal_getdata.php?q=1",
            datatype: 'json',
            mtype: 'POST',
            colNames:['#','Текст сообщения','Статус'],
            colModel :[
                {name:'id_notification', index:'id_notification', width:25, align:'left',editable: false,edittype: "text",search:false},
				{name:'text_notification', index:'text_notification', width:350, align:'left', edittype:"textarea",editable:true,searchoptions:{sopt:['bw','eq','ne','cn']},editrules:{required:true},editoptions: {rows:"6",cols:"50"}},
				{name: 'id_status',index: 'id_status', width:50, align:'left',edittype:"select",formatter:"select",search:true,editoptions:{value:"1:Активен;2:Не активен"},editable:true,stype:"select", searchoptions:{value:"1:Активен;2:Не активен",sopt:['eq']}}
				],
            pager: '#pager',
			autowidth:true,
            height:278,
			rowNum:20,
            rowList:[20,50,100],
            sortname: 'id_notification',
            sortorder: "asc",
            caption: '<i class="icon-table icon-notification"></i>Уведомления',
			viewrecords: true,
			multiselect: true,
			editurl: '/app/scripts/jqgrid/admin_journal_modifydata.php?q=1',
			onSelectRow: function(id){}
        }).navGrid('#pager',{edit:true,add:true,view:false,del:true,search:true},{width:460,reloadAfterSubmit:true,zIndex:99, beforeShowForm: function(form) {
			$('#text_notification', form).attr({"title":"Сообщение не должно содержать больше 300 символов."});
			},
	afterSubmit: function (response) {
			if(response.responseText=="")
			{
				showErrorDialog('Вы не можите редактировать эту запись!');
				return false;
			}
			else if (response.responseText=="bigger")
			{
				showErrorDialog('Сообщение слишком большое!');
				return false;
			}
			else
			{
				var myInfo = '<div class="ui-state-highlight ui-corner-all">'+
							 '<span class="ui-icon ui-icon-info" ' +
								 'style="float: left; margin-right: .3em;"></span>' +
							 response.responseText +
							 '</div>',
					$infoTr = $("#TblGrid_" + $.jgrid.jqID(this.id) + ">tbody>tr.tinfo"),
					$infoTd = $infoTr.children("td.topinfo");
				$infoTd.html(myInfo);
				$infoTr.show();
			
				setTimeout(function () {
					$infoTr.slideUp("slow");
				}, 3000);
				return [true, "", ""];
			}
			}},{width:460,reloadAfterSubmit:true,zIndex:99, beforeShowForm: function(form) {
				$('#text_notification', form).attr({"title":"Сообщение не должно содержать больше 300 символов."});
				},afterSubmit: function (response) {
			if(response.responseText=="")
			{
				showErrorDialog('Вы не можите редактировать эту запись!');
				return false;
			}
			else if (response.responseText=="bigger")
			{
				showErrorDialog('Сообщение слишком большое!');
				return false;
			}
			else
			{
				var myInfo = '<div class="ui-state-highlight ui-corner-all">'+
							 '<span class="ui-icon ui-icon-info" ' +
								 'style="float: left; margin-right: .3em;"></span>' +
							 response.responseText +
							 '</div>',
					$infoTr = $("#TblGrid_" + $.jgrid.jqID(this.id) + ">tbody>tr.tinfo"),
					$infoTd = $infoTr.children("td.topinfo");
				$infoTd.html(myInfo);
				$infoTr.show();
			
				setTimeout(function () {
					$infoTr.slideUp("slow");
				}, 3000);
				return [true, "", ""];
			}
			}},{width:460,reloadAfterSubmit:true,zIndex:99},{width:460,reloadAfterSubmit:true,multipleSearch:true,zIndex:99,closeAfterSearch:true},{width:460,reloadAfterSubmit:true,zIndex:99}).navSeparatorAdd("#pager",{sepclass:"ui-separator",sepcontent: ''}); 

$("#pager_left table.navtable tbody tr").append('Статус: <select class="active-status"><option value="0" selected="selected">выбрать...</option><option value="1">Активен</option><option value="2">Не активен</option></select>');

 $(".active-status").change(function() {
			
			$("option:selected", $(this)).each(function() {
				
				var s= $("#notifications").jqGrid('getGridParam','selarrrow'),
				id_status=$(".active-status :selected").val();
				
				if (id_status==0) 
				{
					showErrorDialog('Выберите значение!');
					
				}
				
				else if (s==false){ 
					showErrorDialog('Поля не отмечены!');
				}
				else 
				{
					for(var i=0;i<s.length;i++)
					{
						var cl = s[i];
						$.ajax({  
						type: "POST",  
						url: "/app/scripts/jqgrid/admin_journal_modifydata.php?q=1",  
						data: 'oper=activestatus&id_notification='+cl+'&id_status='+id_status,
						success: function(msg){
									if(msg.length == 0)
									{
										showErrorDialog('Вы не можите редактировать эту запись!');
									}
									
								}
						});
					}
					$('#notifications').trigger("reloadGrid");
					$('.active-status option').prop('selected', function() {
							return this.defaultSelected;
						});
				}	
			});
		});

$("#journal").jqGrid({
            url:"/app/scripts/jqgrid/admin_journal_getdata.php?q=2",
            datatype: 'json',
            mtype: 'POST',
            colNames:['#','ID Пользователя','Пользователь','Действие','Время'],
            colModel :[
                {name:'id_event', index:'id_event', width:35, align:'right',editable:false, search:false},
				{name:'id_user', index:'id_user',editable: true,edittype: "text",hidden:true,searchoptions:{sopt:['eq'],searchhidden: true}},
				{name:'name', index:'name', width:150, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['bw','eq','ne','cn']},editrules:{required:true},editoptions:{maxlength: 15}},
				{name: 'id_type_event',index: 'id_type_event', width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectType,stype:"select",searchoptions:selectType},
				{name:'time_event', index:'time_event', width:150, align:'left', edittype:"text",editable:false,searchoptions:{sopt:['bw','eq','ne','cn']}}				
				],
            pager: '#pager2',
			autowidth:true,
            height:328,
			rowNum:60,
            rowList:[60,120],
            sortname: 'id_event',
            sortorder: "desc",
			multiselect: true,
            caption: '<i class="icon-table icon-archive"></i>Журнал действий',
			viewrecords: true
        }).navGrid('#pager2',{edit:false,add:false,del:false,view:true,search:true},{width:450,reloadAfterSubmit:true,zIndex:99},{width:450,reloadAfterSubmit:true,zIndex:99},{width:450,reloadAfterSubmit:true,zIndex:99},{width:450,reloadAfterSubmit:true,multipleSearch:true,zIndex:99},{width:450,multipleSearch:true,reloadAfterSubmit:true,zIndex:99,closeAfterSearch:true}); 

$('.user-list').on('click','.user',function(){
		var id_user=$(this).attr('id'),
			mypostdata = $("#journal").jqGrid('getGridParam', 'postData');
			mypostdata.filters='{"groupOp":"AND","rules":[{"field":"id_user","op":"eq","data":'+id_user+'}]}';
			$("#journal").jqGrid('setGridParam', {postData: mypostdata, search:true});
			$("#journal").trigger("reloadGrid");
	});


});

  
