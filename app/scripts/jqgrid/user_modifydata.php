<?php 
session_start();
require_once '../connect.php';

try {
	if ($_GET['q']==1)
	{
		if($_POST['floor']==1)
		{
			$id_floor_status=1;
		}	
		if($_POST['floor']==$_POST['number_of_floor'] and $_POST['floor']!=1)
		{
			$id_floor_status=2;	
		}
		if($_POST['floor']!=1 and $_POST['floor']!=$_POST['number_of_floor'])
		{
			$id_floor_status=3;
		}
		if($_POST['id_sell_out_status']==2)
		{
			$query=$dbh->prepare('INSERT INTO `users_journal`(`id_user`, `id_type_event`,`time_event`) VALUES (?,?,NOW()+INTERVAL 2 HOUR)');
				
			$query->execute(array($_SESSION['id_user'],3));	
		}
		
		if ($_POST['oper']=="add")
		{
			$res = $dbh->prepare('SELECT o.`id_object` FROM `objects` o LEFT JOIN `objects_owners` ow ON o.`id_owner`= ow.`id_owner` WHERE o.`id_street`=? AND o.`house_number`=? AND o.`id_category`=? AND o.`room_count`=? AND o.`id_planning`=? AND o.`floor`=? AND ow.`number`=?');
			
			$res->execute(array($_POST['id_street'],$_POST['house_number'],$_POST['id_category'],$_POST['room_count'],$_POST['id_planning'],$_POST['floor'],$_POST['number']));
			
			$row = $res->fetch(PDO::FETCH_ASSOC);
							
			if (!empty($row)) 
			{
				exit;
			}
			else
			{
				$query=$dbh->prepare('INSERT INTO `objects_owners`(`name_owner`, `number`) VALUES (?,?)');
				
				$query->execute(array($_POST['name_owner'],$_POST['number']));	
				
				$lastid=$dbh->lastInsertId();
				
				$query=$dbh->prepare('INSERT INTO `objects`(`id_owner`,`id_district`, `id_street`, `house_number`,`id_building`, `id_category`, `room_count`, `id_planning`, `floor`,`number_of_floor`,`id_floor_status`, `space`, `id_renovation`, `id_window`, `id_counter`, `id_sell_out_status`, `id_time_status`, `price`, `market_price`, `id_user`, `date`,`date_change`) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW()+INTERVAL 2 HOUR,NOW()+INTERVAL 2 HOUR)');
				
				$query->execute(array($lastid,$_POST['id_district'],$_POST['id_street'],$_POST['house_number'],$_POST['id_building'],$_POST['id_category'],$_POST['room_count'],$_POST['id_planning'],$_POST['floor'],$_POST['number_of_floor'],$id_floor_status,$_POST['space'], $_POST['id_renovation'], $_POST['id_window'], $_POST['id_counter'], $_POST['id_sell_out_status'], $_POST['id_time_status'], $_POST['price'], $_POST['market_price'],$_SESSION['id_user']));
				
				echo 'Запись добавлена.';
			}
				
		}
		if ($_POST['oper']=="edit")
		{
			if ($_SESSION['id_user']==$_POST['id_user'])
			{
				
				$res = $dbh->prepare('SELECT o.`price`,o.`market_price`,o.`date_change` FROM `objects` o WHERE o.`id_object`=?');
			
				$res->execute(array($_POST['id']));
			
				$row = $res->fetch(PDO::FETCH_ASSOC);
				
				if ($row['price']!=$_POST['price'] or $row['market_price']!=$_POST['market_price'])
				{
					$query=$dbh->prepare('UPDATE `objects` SET `date_change`=NOW()+INTERVAL 2 HOUR WHERE `id_object`=?');
					$query->execute(array($_POST['id']));
				}
				
				$query=$dbh->prepare('UPDATE `objects_owners` SET `name_owner`=?,`number`=? WHERE `id_owner`=?');
				$query->execute(array($_POST['name_owner'],$_POST['number'],$_POST['id_owner']));
				
				$query=$dbh->prepare('UPDATE `objects` SET `id_district`=?,`id_street`=?,`house_number`=?,`id_building`=?,`id_category`=?,`room_count`=?,`id_planning`=?,`floor`=?,`number_of_floor`=?,`id_floor_status`=?,`space`=?,`id_sell_out_status`=?,`id_time_status`=?,`price`=?,`market_price`=?,`date`=NOW()+INTERVAL 2 HOUR WHERE `id_object`=?');
				$query->execute(array($_POST['id_district'],$_POST['id_street'],$_POST['house_number'],$_POST['id_building'],$_POST['id_category'],$_POST['room_count'],$_POST['id_planning'],$_POST['floor'],$_POST['number_of_floor'],$id_floor_status,$_POST['space'],$_POST['id_sell_out_status'],$_POST['id_time_status'],$_POST['price'],$_POST['market_price'],$_POST['id']));
					
				echo "Запись отредактирована!";	
			}
			else
			{
				exit;	
			}
		}
		if ($_POST['oper']=="selloutstatus")
		{
			if ($_SESSION['id_user']==$_POST['id_user'])
			{
				$query=$dbh->prepare('UPDATE `objects` SET `id_sell_out_status`=?,`date`=NOW()+INTERVAL 2 HOUR WHERE `id_object`=?');
				$query->execute(array($_POST['id_status'],$_POST['id_object']));
				
				if($_POST['id_status']==2)
				{
					$query=$dbh->prepare('INSERT INTO `users_journal`(`id_user`, `id_type_event`,`time_event`) VALUES (?,?,NOW()+INTERVAL 2 HOUR)');
					
					$query->execute(array($_SESSION['id_user'],3));	
				}
									
				echo "Статус изменен!";	
				
			}
			else
			{
				exit;	
			}
		}
		if ($_POST['oper']=="timestatus")
		{
			if ($_SESSION['id_user']==$_POST['id_user'])
			{
				$query=$dbh->prepare('UPDATE `objects` SET `id_time_status`=?,`date`=NOW()+INTERVAL 2 HOUR WHERE `id_object`=?');
				$query->execute(array($_POST['id_status'],$_POST['id_object']));
									
				echo "Статус изменен!";	
			}
			else
			{
				exit;	
			}
		}
	}
	if ($_GET['q']==2)
	{
		if ($_POST['oper']=="add")
		{
			$res = $dbh->prepare('SELECT `id_client` FROM `clients` WHERE `number`=? AND `id_category`=? AND `id_planning`=? AND`id_status`=?');
			
			$res->execute(array($_POST['number'],$_POST['id_category'],$_POST['id_planning'],1));
			
			$row = $res->fetch(PDO::FETCH_ASSOC);
							
			if (!empty($row)) 
			{
				exit;
			}
			else
			{			
				$query=$dbh->prepare('INSERT INTO `clients`(`name`, `number`, `id_category`, `id_planning`,`id_floor_status`, `price`,`id_time_status`, `id_status`, `id_user`, `date`) VALUES (?,?,?,?,?,?,?,?,?,NOW()+INTERVAL 2 HOUR)');
				
				$query->execute(array($_POST['name'],$_POST['number'],$_POST['id_category'],$_POST['id_planning'],$_POST['id_floor_status'], $_POST['cl_price'], $_POST['id_time_status'],$_POST['id_status'],$_SESSION['id_user']));
				
				echo 'Запись добавлена.';
			}
				
		}
		if ($_POST['oper']=="edit")
		{
			if ($_SESSION['id_user']==$_POST['id_user'])
			{
				$query=$dbh->prepare('UPDATE `clients` SET `name`=?,`number`=?,`id_category`=?,`id_planning`=?,`id_floor_status`=?,`price`=?,`id_time_status`=?,`id_status`=?,`date`=NOW()+INTERVAL 2 HOUR WHERE `id_client`=?');
				$query->execute(array($_POST['name'],$_POST['number'],$_POST['id_category'],$_POST['id_planning'],$_POST['id_floor_status'] ,$_POST['cl_price'], $_POST['id_time_status'],$_POST['id_status'],$_POST['id']));
						
				echo "Запись отредактирована!";
			}
			else
			{
				exit;
			}
		
		}
		if ($_POST['oper']=="activestatus")
		{
			if ($_SESSION['id_user']==$_POST['id_user'])
			{
				$query=$dbh->prepare('UPDATE `clients` SET `id_status`=?,`date`=NOW()+INTERVAL 2 HOUR WHERE `id_client`=?');
				$query->execute(array($_POST['id_status'],$_POST['id_client']));
											
				echo "Статус изменен!";
			}
			else
			{
				exit;
			}
			
		}
		if ($_POST['oper']=="timestatus")
		{
			if ($_SESSION['id_user']==$_POST['id_user'])
			{
				$query=$dbh->prepare('UPDATE `clients` SET `id_time_status`=?,`date`=NOW()+INTERVAL 2 HOUR WHERE `id_client`=?');
				$query->execute(array($_POST['id_status'],$_POST['id_client']));
											
				echo "Статус изменен!";
			}
			else
			{
				exit;
			}
			
		}
	}
	if ($_GET['q']==3)
	{
		if ($_POST['oper']=="edit")
		{
			if ($_SESSION['id_user']==$_POST['id_user'])
			{
				$query=$dbh->prepare('UPDATE `objects` SET `id_renovation`=?,`id_window`=?,`id_counter`=?,`date`=NOW()+INTERVAL 2 HOUR WHERE `id_object`=?');
				$query->execute(array($_POST['id_renovation'],$_POST['id_window'],$_POST['id_counter'],$_GET['id_object']));
					
				
			}
			else
			{
				echo "Вы не можите редактировать эту запись!";	
			}
		}
	}
}
catch (PDOException $e) {
    echo 'Database error: '.$e->getMessage();
}