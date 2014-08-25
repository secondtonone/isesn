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
			
			$panel_model->admin_getdata ($_POST['page'],$_POST['rows'],$_POST['sidx'],$_POST['sord'],$_POST['_search'],$filters,'objects');

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
			
			$panel_model->admin_getdata ($_POST['page'],$_POST['rows'],$_POST['sidx'],$_POST['sord'],$_POST['_search'],$filters,'users');	

		}
		if($_GET['q']==3)
		{
			if (empty($_GET['filters']))
			{
				$filters='';
			}
			else
			{
				$filters=$_GET['filters'];
			}
			
			$panel_model = new panel_model();
			
			$panel_model->admin_getdata ($_GET['page'],$_GET['rows'],$_GET['sidx'],$_GET['sord'],$_GET['_search'],$filters,'clients');	
			
		}
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}
