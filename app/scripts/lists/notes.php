<?php 
session_start();
require_once '../connect.php';
try {
	
	switch ($_POST['q'])
	{
		case '1':
			
			$res = $dbh->prepare('SELECT COUNT(`id_notification`) AS count FROM `notifications` WHERE `id_status`=?');
			
			$res->execute(array(1));
			
			$row = $res->fetch(PDO::FETCH_ASSOC);
			
			$response = new stdClass();
			$response->total = $row['count'];
			$i=0;
			
			$res = $dbh->prepare('SELECT `text_notification` FROM `notifications` WHERE `id_status`=?');
			
			$res->execute(array(1));
			
			while($row = $res->fetch(PDO::FETCH_ASSOC))
			{
				$response->rows[$i]['text']=$row['text_notification'];
				$i++;
			}
			
			echo json_encode($response);
			
			break;
			
	}
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}