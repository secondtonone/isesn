<?php
/*NOW()+INTERVAL 2 HOUR*/
session_start();
require_once '../connect.php';

try {
	if ($_POST['q']==1)
	{
		$query=$dbh->prepare('INSERT INTO `users_journal`(`id_user`, `id_type_event`,`time_event`) VALUES (?,?,NOW()+INTERVAL 2 HOUR)');
				
		$query->execute(array($_SESSION['id_user'],2));	
		
		$query=$dbh->prepare('UPDATE `users` SET `online`=? WHERE `id_user`=?');
				
		$query->execute(array('offline',$_SESSION['id_user']));	
	}
	
}
catch (PDOException $e) {
    echo 'Database error: '.$e->getMessage();
}