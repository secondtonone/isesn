<?php 
session_start();
require_once '../connect.php';
try {
	$result='';
	$response = new stdClass();
	
	if ($_POST['q']==1)
	{

			$res = $dbh->prepare('SELECT `id_building`, `name_building` FROM `objects_building`');
			
			$res->execute(array());
			
			$result=':выбрать...';
			
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_building'].':'.$row['name_building'];
			}
			
			$response->rows['building']=$result;

			

			$res = $dbh->prepare('SELECT `id_category`, `name_category` FROM `objects_category` ORDER BY `name_category`');
			
			$res->execute(array());
			
			$result=':выбрать...';
			
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_category'].':'.$row['name_category'];
			}
			
			$response->rows['category']=$result;
			
			
			$res = $dbh->prepare('SELECT `id_planning`, `name_planning` FROM `objects_planning`');
			
			$res->execute(array());
			
			$result=':выбрать...';
			
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_planning'].':'.$row['name_planning'];
			}
			
			$response->rows['planning']=$result;
			

			$res = $dbh->prepare('SELECT `id_sell_out_status`, `name_sell_out_status` FROM `objects_sell_out_status`');
			
			$res->execute(array());
			
			$result='';
					
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_sell_out_status'].':'.$row['name_sell_out_status'];
			}
	
			$response->rows['sellstatus']=trim($result,';');


			$res = $dbh->prepare('SELECT `id_time_status`, `name_time_status` FROM `objects_time_status`');
			
			$res->execute(array());
			
			$result='';
					
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_time_status'].':'.$row['name_time_status'];
			}
			
			$response->rows['timestatus']=trim($result,';');

			

			$res = $dbh->prepare('SELECT `id_renovation`, `name_renovation` FROM `objects_renovation`');
			
			$res->execute(array());
			
			$result='';
					
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_renovation'].':'.$row['name_renovation'];
			}
			
			$response->rows['renovation']=trim($result,';');
			

			$res = $dbh->prepare('SELECT `id_window`, `name_window` FROM `objects_window`');
			
			$res->execute(array());
			
			$result='';
					
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_window'].':'.$row['name_window'];
			}
			
			$response->rows['window']=trim($result,';');
			
			
	
			$res = $dbh->prepare('SELECT `id_counter`, `name_counter` FROM `objects_counter`');
			
			$res->execute(array());
			
			$result='';
					
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_counter'].':'.$row['name_counter'];
			}
			
			$response->rows['counter']=trim($result,';');
			
			

			$res = $dbh->prepare('SELECT `id_district`, `name_district` FROM `objects_district`');
			
			$res->execute(array());
			
			$result=':выбрать...';
			
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_district'].':'.$row['name_district'];
			}
			
			$response->rows['district']=$result;
			
			

			$res = $dbh->prepare('SELECT `id_floor_status`, `name_floor_status` FROM `clients_floor_status`');
			
			$res->execute(array());
			
			$result='';
					
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_floor_status'].':'.$row['name_floor_status'];
			}
			
			$response->rows['floor']=trim($result,';');
			
			

			$res = $dbh->prepare('SELECT `id_type_event`, `name_type_event` FROM `users_type_event`');
			
			$res->execute(array());
			
			$result='';
					
			while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
			{
				$result.=';'.$row['id_type_event'].':'.$row['name_type_event'];
			}
			
			$response->rows['type']=trim($result,';');
			
			echo json_encode($response);
	}
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}