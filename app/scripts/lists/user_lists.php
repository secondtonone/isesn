<?php 
session_start();

require_once '../../models/panel_model.php';

try {
	
	if ($_POST['q']==1)
	{
		
		$panel_model = new panel_model();
			
		$panel_model->user_lists();
			
	}
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}