<?php
session_start();
require_once '../connect.php';

try {
	if ($_POST['q']==1)
	{	
		$query=$dbh->prepare('UPDATE `users` SET `online`=?,`time_activity`=NOW()+INTERVAL 2 HOUR WHERE `id_user`=?');
				
		$query->execute(array('online',$_SESSION['id_user']));
	}
	
}
catch (PDOException $e) {
    echo 'Database error: '.$e->getMessage();
}