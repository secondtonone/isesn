<?php 
session_start();
require_once '../connect.php';

try {
	if ($_GET['q']==1)
	{
		if ($_POST['oper']=="add")
		{
							
			$query=$dbh->prepare('INSERT INTO `notifications`(`text_notification`, `id_status`) VALUES (?,?)');
				
			$query->execute(array($_POST['text_notification'],$_POST['id_status']));
				
			echo 'Запись добавлена.';
			
				
		}
		if ($_POST['oper']=="edit")
		{
			$query=$dbh->prepare('UPDATE `notifications` SET `text_notification`=?,`id_status`=? WHERE `id_notification`=?');
			$query->execute(array($_POST['text_notification'],$_POST['id_status'],$_POST['id']));
			
			echo "Запись отредактирована!";	
		}
		if ($_POST['oper']=="activestatus")
		{
			$query=$dbh->prepare('UPDATE `notifications` SET `id_status`=? WHERE `id_notification`=?');
			$query->execute(array($_POST['id_status'],$_POST['id_notification']));
									
			echo "Статус изменен!";	
		}	
	}
}
catch (PDOException $e) {
    echo 'Database error: '.$e->getMessage();
}