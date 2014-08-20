<?php 
session_start();
require_once '../connect.php';
try {
	$result='';
	
	switch ($_POST['q'])
	{
		case '1':

			$res = $dbh->prepare('SELECT `id_user` FROM `users` WHERE `login`=?');
			
			$res->execute(array($_POST['login']));
			
			$row = $res->fetch(PDO::FETCH_ASSOC);
							
			if (!empty($row)) 
			{
				echo "Такой логин уже используется!";
			}

			break;
			
	}
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}