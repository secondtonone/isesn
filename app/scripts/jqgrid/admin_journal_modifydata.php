<?php 
session_start();
require_once '../connect.php';

try {
	if ($_GET['q']==1)
	{
		if ($_POST['oper']=="add")
		{
			if(strlen($_POST['text_notification'])>300)
			{
				echo "bigger";
			}
			else
			{
				$query=$dbh->prepare('INSERT INTO `notifications`(`text_notification`, `id_status`) VALUES (?,?)');
					
				$query->execute(array($_POST['text_notification'],$_POST['id_status']));
					
				echo 'Запись добавлена.';
			}
			
				
		}
		if ($_POST['oper']=="edit")
		{
			if(strlen($_POST['text_notification'])>300)
			{
				echo "bigger";
			}
			else
			{
				$query=$dbh->prepare('UPDATE `notifications` SET `text_notification`=?,`id_status`=? WHERE `id_notification`=?');
				$query->execute(array($_POST['text_notification'],$_POST['id_status'],$_POST['id']));
				
				echo "Запись отредактирована!";
			}
		}
		if ($_POST['oper']=="del")
		{
			$query=$dbh->prepare('DELETE FROM `notifications` WHERE `id_notification`=?');
			$query->execute(array($_POST['id']));
			
			echo "Запись удалена!";	
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