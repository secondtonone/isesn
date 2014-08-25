<?php
session_start();

require_once '../../models/journal_model.php';

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
			
			$journal_model = new journal_model();
			
			$journal_model->admin_getdata ($_POST['page'],$_POST['rows'],$_POST['sidx'],$_POST['sord'],$_POST['_search'],$filters,'notifications');
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
			
			$journal_model = new journal_model();
			
			$journal_model->admin_getdata ($_POST['page'],$_POST['rows'],$_POST['sidx'],$_POST['sord'],$_POST['_search'],$filters,'events');	
			
		}
		if($_GET['q']==3)
		{
			$journal_model = new journal_model();
			
			$journal_model->users_online();		
		}
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}
