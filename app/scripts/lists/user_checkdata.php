<?php 
session_start();
require_once '../connect.php';
try {
	$result='';
	
	switch ($_POST['q'])
	{
		case '1':

			$res = $dbh->prepare('SELECT o.`id_object` FROM `objects` o LEFT JOIN `objects_owners` ow ON o.`id_owner`= ow.`id_owner` WHERE o.`id_street`=? AND o.`house_number`=? AND o.`id_category`=? AND o.`room_count`=? AND o.`id_planning`=? AND o.`floor`=? AND ow.`number`=?');
			
			$res->execute(array($_POST['id_street'],$_POST['house_number'],$_POST['id_category'],$_POST['room_count'],$_POST['id_planning'],$_POST['floor'],$_POST['number']));
			
			$row = $res->fetch(PDO::FETCH_ASSOC);
							
			if (!empty($row)) 
			{
				echo "Такой объект уже занесен!";
			}

			break;
			
		case '2':
			$res = $dbh->prepare('SELECT `id_client` FROM `clients` WHERE `number`=? AND `id_category`=? AND `id_planning`=? AND `id_status`=?');
			
			$res->execute(array($_POST['number'],$_POST['id_category'],$_POST['id_planning'],1));
			
			$row = $res->fetch(PDO::FETCH_ASSOC);
							
			if (!empty($row)) 
			{
				echo "Такой покупатель уже занесен!";
			}
			break;
	}
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}