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

	function getNotification()
	{
		return $.ajax({
					type: "POST",
					url: "/app/scripts/lists/notes.php",
					data: "q=1",
					async: false
				}).responseText;
	}

	function stikyGenerate()
	{
		var notes=getNotification(),
			note=JSON.parse(notes);

		if(note.total=='0')
		{
			return false;
		}
		else
		{
			for (var i=0; i<note.total; i++)
			{
				$.sticky(note.rows[i].text);
			}
		}
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
	$(document.body).on('keyup','#floor', function() {

		if ($("#id_owner").val()=='')
		{
			var id_street=$("#id_street").val(),
				house_number=$("#house_number").val(),
				id_category=$("#id_category :selected").val(),
				room_count=$("#room_count").val(),
				floor_obj=$("#floor").val(),
				id_planning=$("#id_planning :selected").val();

					$.ajax({
							type: "POST",
							url: "/app/scripts/lists/user_checkdata.php",
							data: 'q=1&id_street='+id_street+'&house_number='+house_number+'&id_category='+id_category+'&room_count='+room_count+'&id_planning='+id_planning+'&floor='+floor_obj,
							success: function(msg){
								if (msg.length!=0)
								{
									showErrorDialog(msg);
								}
							}
					});

			}
	});

	stikyGenerate();

	var selectList=getList(),
		selectСategory={value:selectList.rows.category,sopt:['eq']},
		selectBuilding={value:selectList.rows.building,sopt:['eq']},
		selectPlanning={value:selectList.rows.planning,sopt:['eq']},
		selectSellOutStatus={value:selectList.rows.sellstatus,sopt:['eq']},
		selectTimeStatus={value:selectList.rows.timestatus,sopt:['eq']},
		selectDistrict={value:selectList.rows.district,sopt:['eq']},
		selectFloor={value:selectList.rows.floor,sopt:['eq'],searchhidden: true},
		selectRenovation={value:selectList.rows.renovation,sopt:['eq'],searchhidden: true},
		selectWindow={value:selectList.rows.window,sopt:['eq'],searchhidden: true},
		selectCounter={value:selectList.rows.counter,sopt:['eq'],searchhidden: true};


	$(".preloader").hide();


$("#objects").jqGrid({
            url:"/app/scripts/jqgrid/user_getdata.php?q=1",
            datatype: 'json',
            mtype: 'POST',
            colNames:['#','','Собственник','Телефон','Район','Улица','Улица','№ дома','Тип здания','Категория','Кол-во комнат','Планировка','Этаж','Этажность','Тип этажности','Площадь, м. кв.','Статус','Статус по времени','Цена','Цена с комиссией','','Менеджер','Дата','','Ремонт','Окна','Счетчики'],
            colModel :[
                {name:'id_object', index:'id_object', width:45, align:'left',editable: false,edittype: "text",search:false},
				{name: "id_owner",index: "id_owner",editable: true,edittype: "text",hidden:true},
				{name:'name_owner', index:'name_owner', width:150, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['bw','eq','ne','cn']},editrules:{required:true},editoptions: {maxlength: 150}},
				{name:'number', index:'number', width:140, align:'center', edittype:"text",editable:true,searchoptions:{sopt:['bw','eq','ne','cn']},editrules:{required:true,integer:true},editoptions: {maxlength: 11}},
				{name: 'id_district',index: 'id_district',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectDistrict,stype:"select",searchoptions:selectDistrict,editrules:{required:true}},
				{name: "id_street",index: "id_street",editable: true,edittype: "text",hidden:true,editrules:{required:true}},
				{name:'name_street', index:'name_street', width:200, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['bw','eq','ne','cn']}, editoptions:{size: 20,
      dataInit: function (e) {
        $(e).autocomplete({
          source: "/app/scripts/lists/autocomplete.php?q=street",
          minLength: 1,
          focus: function (event, ui) {
            $(e).val(ui.item.label);
			return false;
          },
          select: function (event, ui) {
            $(e).val(ui.item.label);
            $("input#id_street").val(ui.item.value);
			return false;
          }
        });
		}}},
				{name:'house_number', index:'house_number', width:90, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['bw','eq','ne','cn']},editrules:{required:true},editoptions: {maxlength: 8}},
				{name: 'id_building',index: 'id_building',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectBuilding,stype:"select",searchoptions:selectBuilding,editrules:{required:true}},
				{name: 'id_category',index: 'id_category',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectСategory,stype:"select",searchoptions:selectСategory,editrules:{required:true}},
				{name:'room_count', index:'room_count', width:90, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['bw','eq','ne','cn'],clearSearch:true},editrules:{required:true,integer:true},editoptions: {maxlength: 2}},
				{name:'id_planning', index:'id_planning',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectPlanning,stype:"select",searchoptions:selectPlanning,editrules:{required:true}},
				{name:'floor', index:'floor', width:70, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['le','lt','gt','ge','bw','eq','ne']},editrules:{required:true,number:true},editoptions: {maxlength: 2}},
				{name:'number_of_floor', index:'number_of_floor', width:70, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['le','lt','gt','ge','bw','eq','ne']},editrules:{required:true,number:true},editoptions: {maxlength: 2}},
				{name:'id_floor_status', index:'id_floor_status',editable: false,hidden:true,edittype:"select",formatter:"select",editoptions:selectFloor,stype:"select",searchoptions:selectFloor},
				{name:'space', index:'space', width:90, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['le','lt','gt','ge','bw','eq','ne']},editrules:{required:true},editoptions: {maxlength: 7}},
				{name:'id_sell_out_status', index:'id_sell_out_status',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectSellOutStatus,stype:"select",searchoptions:selectSellOutStatus},
				{name:'id_time_status', index:'id_time_status',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectTimeStatus,stype:"select",searchoptions:selectTimeStatus},
				{name:'price', index:'price', width:110, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['le','lt','gt','ge','bw','eq','ne']},editrules:{required:true,number:true},editoptions: {maxlength: 10}},
				{name:'market_price', index:'market_price', width:110, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['le','lt','gt','ge','bw','eq','ne']},editrules:{required:true,number:true},editoptions: {maxlength: 10}},
				{name:'id_user', index:'id_user',editable: true,edittype: "text",hidden:true},
				{name:'name', index:'name', width:150, align:'left', edittype:"text",editable:false,searchoptions:{sopt:['bw','eq','ne','cn']}},
				{name:'date', index:'date', width:220, align:'left', edittype:"text",editable:false,searchoptions:{sopt:['bw','eq','ne','cn']}},
				{name:'edit_enable', index:'edit_enable',editable: true,edittype: "text",hidden:true},
				{name:'id_renovation', index:'id_renovation',edittype:"select",formatter:"select",editable: true, editrules: { edithidden: true }, hidedlg: false,stype:"select",hidden:true,editoptions:selectRenovation,searchoptions:selectRenovation},
				{name:'id_window', index:'id_window',edittype:"select",formatter:"select",editable: true, editrules: { edithidden: true }, hidedlg: false,stype:"select",hidden:true,editoptions:selectWindow,searchoptions:selectWindow},
				{name:'id_counter', index:'id_counter',edittype:"select",formatter:"select",editable: true, editrules: { edithidden: true }, hidedlg: false,stype:"select",hidden:true,editoptions:selectCounter,searchoptions:selectCounter}
				],
            pager: '#pager',
			autowidth:true,
            height:378,
			rowNum:20,
            rowList:[20,50,100],
            sortname: 'id_object',
            sortorder: "asc",
            caption: '<i class="icon-table icon-object"></i>Объекты недвижимости',
			viewrecords: true,
			subGrid: true,
			multiselect: true,
			toolbar: [true,"top"],
			editurl: '/app/scripts/jqgrid/user_modifydata.php?q=1',
			rowattr: function (row) {

					if (row.edit_enable =="0") {

						return {"class": "alt-row-class"};
					}
				},
			beforeSelectRow: function(rowid) {
				var rowData = $("#objects").jqGrid('getRowData',rowid);

				if (rowData.edit_enable=='1') {

					$("#edit_objects").removeClass('ui-state-disabled');
					$(".select-wrap").css({'display' : 'inline-block'});

				} else {

					$("#edit_objects").addClass('ui-state-disabled');
					$(".select-wrap").css({'display' : 'none'});
				}
				return true;
			},
			ondblClickRow: function (id){

				var mypostdata = $("#clients").jqGrid('getGridParam', 'postData'),
					rowData = $("#objects").jqGrid('getRowData',id),
					id_category = rowData.id_category,
					id_planning = rowData.id_planning,
					id_floor_status = rowData.id_floor_status,
					price = rowData.market_price,
					fields = ['{"field":"id_category","op":"eq","data":"'+id_category+'"},',
							  '{"field":"id_planning","op":"eq","data":"'+id_planning+'"},',
							  '{"field":"id_floor_status","op":"eq","data":"'+id_floor_status+'"},'],
					lowPrice,
					highPrice;

				lowPrice=parseInt(price)-100000;
				highPrice=parseInt(price)+100000;

				if (id_category=='13')
				{
					fields[0] = '';
				}
				if (id_planning=='7')
				{
					fields[1] = '';
				}
				if (id_floor_status=='4')
				{
					fields[2] = '{"field":"id_floor_status","op":"ne","data":"1"},';
				}
				if (id_floor_status=='5')
				{
					fields[2] = '{"field":"id_floor_status","op":"ne","data":"2"},';
				}
				if (id_floor_status=='6')
				{
					fields[2] = '';
				}

				mypostdata.filters='{"groupOp":"AND","rules":['+fields[0]+''+fields[1]+''+fields[2]+'{"field":"price","op":"ge","data":"'+lowPrice+'"},{"field":"price","op":"le","data":"'+highPrice+'"}]}';
				$("#clients").jqGrid('setGridParam', {postData: mypostdata, search:true});
				$("#clients").trigger("reloadGrid");
				},
			subGridRowExpanded: function(subgrid_id, row_id) {

				var lastSel,subgrid_table_id, pager_id;
				subgrid_table_id = subgrid_id+"_t";
				pager_id = "p_"+subgrid_table_id;

				$("#"+subgrid_id).html("<div class='subgridform'><table id='"+subgrid_table_id+"' class='scroll'></table></div>");

				$("#"+subgrid_table_id).jqGrid({
				url:"/app/scripts/jqgrid/user_getdata.php?q=3&id_object="+row_id,
				datatype: "json",
				mtype: 'GET',
				colNames: ['Ремонт','Окна','Счетчики','Дата изменения цены','',''],
				colModel: [
					{name:'id_renovation', index:'id_renovation',edittype:"select",formatter:"select",editable: true, editrules: selectRenovation, editoptions:selectRenovation},
					{name:'id_window', index:'id_window',edittype:"select",formatter:"select",editable: true, editrules:selectWindow, editoptions:selectWindow},
					{name:'id_counter', index:'id_counter',edittype:"select",formatter:"select",editable: true, editrules:selectCounter,editoptions:selectCounter},
					{name:'date_change', index:'date_change', width:200, align:'left', edittype:"text",editable:false,searchoptions:{sopt:['bw','eq','ne','cn']}},
					{name:'id_user', index:'id_user',editable: true,edittype: "text",hidden:true},
					{name:'edit_enable', index:'edit_enable',editable: true,edittype: "text",hidden:true}
				],
				rowNum:20,
				sortname: 'id_renovation',
				sortorder: "asc",
				editurl: '/app/scripts/jqgrid/user_modifydata.php?q=3&id_object='+row_id,
				onSelectRow: function(id) {

					var rowData = $(this).jqGrid('getRowData',id);

					if (id && id!==lastSel) {
						$("#"+subgrid_table_id).jqGrid('restoreRow',lastSel);

						if (rowData.edit_enable=='1') {

							$("#"+subgrid_table_id).jqGrid('editRow',id, true);
							lastSel = id;

						}
					}
				},
				height: '100%',
				rowattr: function (row) {

					if (row.edit_enable =="0") {

						return {"class": "alt-row-class"};
					}
				}
				});

			}
        }).navGrid('#pager',{edit:true,add:true,del:false,search:true},{width:350,reloadAfterSubmit:true,zIndex:99, beforeShowForm: function(form) {
									$('#tr_id_renovation', form).hide();
									$('#tr_id_window', form).hide();
									$('#tr_id_counter', form).hide();

									$('#name_owner', form).attr({"title":"ФИО в формате: Иванов Иван Иванович"});
									$('#number', form).attr({"title":"Номер в формате: 89011123456 (только цифры)"});
									$('#name_street', form).attr({"title":"Введите первые буквы улицы, выберите из вариантов"});
									$('#house_number', form).attr({"title":"Номер в формате: 34,7а,55/57"});

									$('#space', form).attr({"title":"Площадь в формате: 34, 34.5"});
									$('#price', form).attr({"title":"Цена в формате: 1000000 (только цифры без сокращений)"});
									$('#market_price', form).attr({"title":"Цена в формате: 1000000 (только цифры без сокращений)"});
			                      },
	afterSubmit: function (response) {
			if(response.responseText=='')
			{
				showErrorDialog('Вы не можите редактировать эту запись!');
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
			},},{width:350,reloadAfterSubmit:true,zIndex:99, beforeShowForm: function(form) {
			$('#tr_id_renovation', form).show();
			$('#tr_id_window', form).show();
			$('#tr_id_counter', form).show();

			$('#name_owner', form).attr({"title":"ФИО в формате: Иванов Иван Иванович"});
			$('#number', form).attr({"title":"Номер в формате: 89011123456 (только цифры)"});
			$('#name_street', form).attr({"title":"Введите первые буквы улицы, выберите из вариантов"});
			$('#house_number', form).attr({"title":"Номер в формате: 34,7а,55/57"});
			$('#space', form).attr({"title":"Площадь в формате: 34, 34.5"});
			$('#price', form).attr({"title":"Цена в формате: 1000000 (только цифры без сокращений)"});
			$('#market_price', form).attr({"title":"Цена в формате: 1000000 (только цифры без сокращений)"});
			},afterSubmit: function (response) {

			if(response.responseText=='')
			{
				showErrorDialog('Вы не можите добавить эту запись!');
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
			}},{width:350,reloadAfterSubmit:true,zIndex:99},{width:490,reloadAfterSubmit:true,multipleSearch:true,zIndex:99,closeAfterSearch:true},{width:350,reloadAfterSubmit:true,zIndex:99}
		).navSeparatorAdd("#pager",{sepclass:"ui-separator",sepcontent: ''}).navButtonAdd("#pager",{caption:"",buttonicon:"ui-icon-document", onClickButton:
	                         function () {
          $("#objects").jqGrid('excelExport',{"url":"/app/scripts/jqgrid/user_exportdata.php?q=1"});
       } , position: "last", title:"Экспорт в Excel", cursor: "pointer"}).navSeparatorAdd("#pager",{sepclass:"ui-separator",sepcontent: ''});

	    $("#edit_objects").addClass('ui-state-disabled');

	   	$("#objects").jqGrid('gridResize', { minWidth: 1150,maxWidth: 1800});

		$("#pager_left table.navtable tbody tr").append ('<div class="select-wrap">Статус: <select class="sell-out-status"><option value="0" selected="selected">выбрать...</option><option value="1">В продаже</option><option value="2">Продано</option><option value="3">Снять</option><option value="4">Времено снять</option></select></div>');
		$("#pager_left table.navtable tbody tr").append ('  <div class="select-wrap">Статус по времени: <select class="time-status"><option value="0" selected="selected">выбрать...</option><option value="2">срочно</option><option value="1">не срочно</option></select></div>');

		$("#t_objects").append("Фильтры: <button class='button-status' title='Показать объекты для срочной реализации'>Срочные</button>");

		$(".button-status").click(function(){
			var mypostdata = $("#objects").jqGrid('getGridParam', 'postData');
			mypostdata.filters='{"groupOp":"AND","rules":[{"field":"id_time_status","op":"eq","data":"2"}]}';
			$("#objects").jqGrid('setGridParam', {postData: mypostdata, search:true});
			$("#objects").trigger("reloadGrid");
		});

		$(".sell-out-status").change(function() {

			$("option:selected", $(this)).each(function() {

				var s= $("#objects").jqGrid('getGridParam','selarrrow'),
				id_status=$(".sell-out-status :selected").val();

				if (id_status==0)
				{
					showErrorDialog('Выберите значение!');

				}

				else if (s==false)
				{
					showErrorDialog('Поля не отмечены!');

				}
				else
				{
					for(var i=0;i<s.length;i++)
					{
						var cl = s[i],
							rowData = $("#objects").jqGrid('getRowData',cl),
							id_user = rowData.id_user;
						$.ajax({
						type: "POST",
						url: "/app/scripts/jqgrid/user_modifydata.php?q=1",
						data: 'oper=selloutstatus&id_object='+cl+'&id_status='+id_status+'&id_user='+id_user,
						success: function(msg){
									if(msg.length == 0)
									{
										showErrorDialog('Вы не можите редактировать эту запись!');

									}
								}
						});
					}
					$('#objects').trigger("reloadGrid");
					$('.sell-out-status option').prop('selected', function() {
							return this.defaultSelected;
						});
					}
				});
		});
		$(".time-status").change(function() {

			$("option:selected", $(this)).each(function() {

	    		var s= $("#objects").jqGrid('getGridParam','selarrrow'),
	    		id_status=$(".time-status :selected").val();

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
						var cl = s[i],
							rowData = $("#objects").jqGrid('getRowData',cl),
							id_user = rowData.id_user;
						$.ajax({
						type: "POST",
						url: "/app/scripts/jqgrid/user_modifydata.php?q=1",
						data: 'oper=timestatus&id_object='+cl+'&id_status='+id_status+'&id_user='+id_user,
						success: function(msg){
								if(msg.length == 0)
									{
										showErrorDialog('Вы не можите редактировать эту запись!');
									}

							}
						});
					}
					$('#objects').trigger("reloadGrid");
					$('.time-status option').prop('selected', function() {
							return this.defaultSelected;
						});
				}
			});
		});

$(document.body).on('change','#id_planning', function() {
		if ($("#cl_price").val()=='')
		{
			var number=$("#number").val(),
				id_category=$("#id_category :selected").val(),
				id_planning=$("#id_planning :selected").val();

				$.ajax({
						type: "POST",
						url: "/app/scripts/lists/user_checkdata.php",
						data: 'q=2&number='+number+'&id_category='+id_category+'&id_planning='+id_planning,
						success: function(msg){
							if (msg.length!=0)
							{
								showErrorDialog(msg);
							}
						}
				});
		}
	});


$("#clients").jqGrid({
            url:"/app/scripts/jqgrid/user_getdata.php?q=2",
            datatype: 'json',
            mtype: 'POST',
            colNames:['#','Покупатель','Телефон','Категория','Планировка','Этажность','Цена','Статус по времени','Статус','','Менеджер','Дата',''],
            colModel :[
                {name:'id_client', index:'id_client', width:45, align:'right',editable:false, search:false},
				{name:'name', index:'clname', width:150, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['bw','eq','ne','cn'],clearSearch:true},editrules:{required:true},editoptions: {maxlength: 150}},
				{name:'number', index:'number', width:150, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['bw','eq','ne','cn'],clearSearch:true},editrules:{required:true,number:true},editoptions: {maxlength: 11}},
				{name: 'id_category',index: 'id_category',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectСategory,stype:"select",searchoptions:selectСategory,editrules:{required:true}},
				{name:'id_planning', index:'id_planning',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectPlanning,stype:"select",searchoptions:selectPlanning,editrules:{required:true}},
				{name:'id_floor_status', index:'id_floor_status',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectFloor,stype:"select",searchoptions:selectFloor},
				{name:'cl_price', index:'price', width:150, align:'left', edittype:"text",editable:true,searchoptions:{sopt:['le','lt','gt','ge','bw','eq','ne']},editrules:{required:true,number:true},editoptions: {maxlength: 10}},
				{name:'id_time_status', index:'id_time_status',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:selectTimeStatus,stype:"select",searchoptions:selectTimeStatus},
				{name:'id_status', index:'id_status',editable: true,width:150, align:'left',edittype:"select",formatter:"select",editoptions:{value:"1:Активен;2:Не активен"},stype:"select",searchoptions:{value:"1:Активен;2:Не активен",sopt:['eq']}},
				{name:'id_user', index:'id_user',editable: true,edittype: "text",hidden:true},
				{name:'name', index:'name', width:150, align:'left', edittype:"text",editable:false,searchoptions:{sopt:['bw','eq','ne','cn']}},
				{name:'date', index:'date', width:150, align:'left', edittype:"text",editable:false,searchoptions:{sopt:['bw','eq','ne','cn'],clearSearch:true}},
				{name:'cl_edit_enable', index:'cl_edit_enable',editable: true,edittype: "text",hidden:true}
				],
            pager: '#pager2',
			autowidth:true,
            height:278,
			rowNum:15,
            rowList:[15,45,90],
            sortname: 'id_client',
            sortorder: "desc",
            caption: '<i class="icon-table icon-client"></i>Покупатели',
			toolbar: [true,"top"],
			viewrecords: true,
			multiselect: true,
			rowattr: function (row) {

					if (row.cl_edit_enable =="0") {

						return {"class": "alt-row-class"};
					}
				},
			beforeSelectRow: function(rowid) {
				var rowData = $("#clients").jqGrid('getRowData',rowid);

				if (rowData.cl_edit_enable=='1') {

					$("#edit_clients").removeClass('ui-state-disabled');
					$(".cl-select-wrap").css({'display' : 'inline-block'});

				} else {

					$("#edit_clients").addClass('ui-state-disabled');
					$(".cl-select-wrap").css({'display' : 'none'});
				}
				return true;
			},
			ondblClickRow: function (id){

				var mypostdata = $("#objects").jqGrid('getGridParam', 'postData'),
					rowData = $("#clients").jqGrid('getRowData',id),
					id_category = rowData.id_category,
					id_planning = rowData.id_planning,
					id_floor_status = rowData.id_floor_status,
					price = rowData.cl_price,
					fields = ['{"field":"id_category","op":"eq","data":"'+id_category+'"},',
							  '{"field":"id_planning","op":"eq","data":"'+id_planning+'"},',
							  '{"field":"id_floor_status","op":"eq","data":"'+id_floor_status+'"},'],
					lowPrice,
					highPrice;

				lowPrice=parseInt(price)-100000;
				highPrice=parseInt(price)+100000;

				if (id_category=='13')
				{
					fields[0] = '';
				}
				if (id_planning=='7')
				{
					fields[1] = '';
				}
				if (id_floor_status=='4')
				{
					fields[2] = '{"field":"id_floor_status","op":"ne","data":"1"},';
				}
				if (id_floor_status=='5')
				{
					fields[2] = '{"field":"id_floor_status","op":"ne","data":"2"},';
				}
				if (id_floor_status=='6')
				{
					fields[2] = '';
				}

				mypostdata.filters='{"groupOp":"AND","rules":['+fields[0]+''+fields[1]+''+fields[2]+'{"field":"market_price","op":"ge","data":"'+lowPrice+'"},{"field":"market_price","op":"le","data":"'+highPrice+'"}]}';
				$("#objects").jqGrid('setGridParam', {postData: mypostdata, search:true});
				$("#objects").trigger("reloadGrid");
				},
			editurl: '/app/scripts/jqgrid/user_modifydata.php?q=2'
        }).navGrid('#pager2',{view:false, del:false, add:true, edit:true, search:true},{width:350,reloadAfterSubmit:true, beforeShowForm: function(form) {

			$('#name', form).attr({"title":"ФИО в формате: Иванов Иван Иванович"});
			$('#number', form).attr({"title":"Номер в формате: 89011123456 (только цифры)"});
			$('#cl_price', form).attr({"title":"Цена в формате: 1000000 (только цифры без сокращений)"});
			},afterSubmit: function (response) {
				if(response.responseText=="")
				{
					showErrorDialog('Вы не можите редактировать эту запись!');
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
			}},{width:350,reloadAfterSubmit:true, beforeShowForm: function(form) {

			$('#name', form).attr({"title":"ФИО в формате: Иванов Иван Иванович"});
			$('#number', form).attr({"title":"Номер в формате: 89011123456 (только цифры)"});
			$('#cl_price', form).attr({"title":"Цена в формате: 1000000 (только цифры без сокращений)"});
			},afterSubmit: function (response) {
			if(response.responseText=="")
			{
				showErrorDialog('Вы не можите добавить эту запись!');
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
			}},{width:350,reloadAfterSubmit:true},{width:490,reloadAfterSubmit:true,multipleSearch:true,closeAfterSearch:true},{width:350,reloadAfterSubmit:true}).navSeparatorAdd("#pager2",{sepclass:"ui-separator",sepcontent: ''}).navButtonAdd("#pager2",{caption:"",buttonicon:"ui-icon-document", onClickButton:
	                         function () {
          $("#clients").jqGrid('excelExport',{"url":"/app/scripts/jqgrid/user_exportdata.php?q=2"});
       } , position: "last", title:"Экспорт в Excel", cursor: "pointer"}).navSeparatorAdd("#pager2",{sepclass:"ui-separator",sepcontent: ''});

	   $("#edit_clients").addClass('ui-state-disabled');

	   $("#clients").jqGrid('gridResize', { minWidth: 1150,maxWidth: 1800});

	   $("#pager2_left table.navtable tbody tr").append('<div class="cl-select-wrap">Статус: <select class="active-status"><option value="0" selected="selected">выбрать...</option><option value="1">Активен</option><option value="2">Не активен</option></select></div>');

	   $("#pager2_left table.navtable tbody tr").append ('  <div class="cl-select-wrap">Статус по времени: <select class="cl-time-status"><option value="0" selected="selected">выбрать...</option><option value="2">срочно</option><option value="1">не срочно</option></select></div>');

		$("#t_clients").append("Фильтры: <button class='cl-button-status' title='Показать клиентов для срочной работы'>Срочные</button>");

		$(".cl-button-status").click(function(){
			var mypostdata = $("#clients").jqGrid('getGridParam', 'postData');
			mypostdata.filters='{"groupOp":"AND","rules":[{"field":"id_time_status","op":"eq","data":"2"}]}';
			$("#clients").jqGrid('setGridParam', {postData: mypostdata, search:true});
			$("#clients").trigger("reloadGrid");
		});


 $(".active-status").change(function() {

			$("option:selected", $(this)).each(function() {

				var s= $("#clients").jqGrid('getGridParam','selarrrow'),
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
						var cl = s[i],
							rowData = $("#clients").jqGrid('getRowData',cl),
							id_user = rowData.id_user;
						$.ajax({
						type: "POST",
						url: "/app/scripts/jqgrid/user_modifydata.php?q=2",
						data: 'oper=activestatus&id_client='+cl+'&id_status='+id_status+'&id_user='+id_user,
						success: function(msg){
									if(msg.length == 0)
									{

										showErrorDialog('Вы не можите редактировать эту запись!');
									}
								}
						});
					}
					$('#clients').trigger("reloadGrid");
					$('.active-status option').prop('selected', function() {
							return this.defaultSelected;
						});
				}
			});
		});

		$(".cl-time-status").change(function() {

			$("option:selected", $(this)).each(function() {

	    		var s= $("#clients").jqGrid('getGridParam','selarrrow'),
	    		id_status=$(".cl-time-status :selected").val();

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
						var cl = s[i],
							rowData = $("#clients").jqGrid('getRowData',cl),
							id_user = rowData.id_user;
						$.ajax({
						type: "POST",
						url: "/app/scripts/jqgrid/user_modifydata.php?q=2",
						data: 'oper=timestatus&id_client='+cl+'&id_status='+id_status+'&id_user='+id_user,
						success: function(msg){
								if(msg.length == 0)
									{
										showErrorDialog('Вы не можите редактировать эту запись!');
									}

							}
						});
					}
					$('#clients').trigger("reloadGrid");
					$('.cl-time-status option').prop('selected', function() {
							return this.defaultSelected;
						});
				}
			});
		});

});