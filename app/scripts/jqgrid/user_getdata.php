<?php
session_start();

require_once '../../models/panel_model.php';

try {	
	if($_GET['q']==1)
	{
		if (empty($_POST['filters']))
		{
			$filters='';
		}
		else
		{
			$filters=$_POST['filters'];
		}
		$panel_model = new panel_model();
			
		$panel_model->user_getdata ($_POST['page'],$_POST['rows'],$_POST['sidx'],$_POST['sord'],$_POST['_search'],$filters,'objects');	
	}
	if($_GET['q']==2)
	{
		if (empty($_POST['filters']))
		{
			$filters='';
		}
		else
		{
			$filters=$_POST['filters'];
		}
		$panel_model = new panel_model();
			
		$panel_model->user_getdata ($_POST['page'],$_POST['rows'],$_POST['sidx'],$_POST['sord'],$_POST['_search'],$filters,'clients');	
	}
	if($_GET['q']==3)
	{
		$panel_model = new panel_model();
			
		$panel_model->user_sub_objects ($_GET['id_object']);		
	}
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}