<?php 
session_start();
require_once '../connect.php';

try {
	if ($_GET['q']==1)
	{
		if ($_POST['oper']=="add")
		{
			$res = $dbh->prepare('SELECT `id_user` FROM `users` WHERE `login`=?');
			
			$res->execute(array($_POST['login']));
			
			$row = $res->fetch(PDO::FETCH_ASSOC);
							
			if (!empty($row)) 
			{
				exit;
			}
			else
			{
				if(strlen($_POST['password'])<6 or strlen($_POST['login'])<6)
				{
					echo "less6";
				}
				elseif(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['password']) or !preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
				{
					echo "nomatch";
				}
				else
				{
					$pass=md5($_POST['password'].'salt');
					
					$query=$dbh->prepare('INSERT INTO `users`(`login`, `password`, `id_right`, `active`, `name`, `number`, `online`) VALUES (?,?,?,?,?,?,"offline")');
					
					$query->execute(array($_POST['login'],$pass,$_POST['id_right'],$_POST['active'],$_POST['name'],$_POST['number']));
					
					$res=$dbh->prepare('INSERT INTO `users_journal`(`id_user`, `id_type_event`,`time_event`) VALUES (?,?,NOW()+INTERVAL 2 HOUR)');
					
					$res->execute(array($_SESSION['id_user'],7));	
					
					echo 'Запись добавлена.';
				}
			}
				
		}
		if ($_POST['oper']=="edit")
		{
			if (!empty($_POST['password']))
			{
				if(strlen($_POST['password'])<6 or strlen($_POST['login'])<6)
				{
					echo "less6";
				}
				elseif(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['password']) or !preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
				{
					echo "nomatch";
				}
				else
				{
					$pass=md5($_POST['password'].'salt');
					
					$query=$dbh->prepare('UPDATE `users` SET `login`=?,`password`=?,`id_right`=?,`active`=?,`name`=?,`number`=? WHERE `id_user`=?');
					$query->execute(array($_POST['login'],$pass,$_POST['id_right'],$_POST['active'],$_POST['name'],$_POST['number'],$_POST['id']));
					
					echo "Запись отредактирована!";	
				}
				
			}
			else
			{
				if(strlen($_POST['login'])<6)
				{
					echo "less6";
				}
				elseif(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
				{
					echo "nomatch";
				}
				else
				{
					$query=$dbh->prepare('UPDATE `users` SET `login`=?,`id_right`=?,`active`=?,`name`=?,`number`=? WHERE `id_user`=?');
					$query->execute(array($_POST['login'],$_POST['id_right'],$_POST['active'],$_POST['name'],$_POST['number'],$_POST['id']));
					
					echo "Запись отредактирована!";	
				}
				
			}
		}
		if ($_POST['oper']=="activestatus")
		{
			$query=$dbh->prepare('UPDATE `users` SET `active`=? WHERE `id_user`=?');
			$query->execute(array($_POST['active'],$_POST['id_user']));
									
			echo "Статус изменен!";	
		}
		
		if ($_POST['oper']=="handobj")
		{
			$query=$dbh->prepare('UPDATE `objects` SET `id_user`=? WHERE `id_object`=?');
			$query->execute(array($_POST['id_user'],$_POST['id_object']));
			
			echo "Объект передан!";							
		}
		if ($_POST['oper']=="handcl")
		{
			$query=$dbh->prepare('UPDATE `clients` SET `id_user`=? WHERE `id_client`=?');
			$query->execute(array($_POST['id_user'],$_POST['id_client']));
			
			echo "Покупатель передан!";							
		}
		
	}
	if ($_GET['q']==2)
	{
		
		if ($_POST['oper']=="edit")
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
					
				$query->execute(array($_POST['id_user'],3));	
			}
			
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
	}
	if ($_GET['q']==3)
	{
		if ($_POST['oper']=="edit")
		{
			$query=$dbh->prepare('UPDATE `objects` SET `id_renovation`=?,`id_window`=?,`id_counter`=?,`date`=NOW() WHERE `id_object`=?');
			$query->execute(array($_POST['id_renovation'],$_POST['id_window'],$_POST['id_counter'],$_GET['id_object']));
		}
	}
	if ($_GET['q']==4)
	{
		if ($_POST['oper']=="edit")
		{
			
			$query=$dbh->prepare('UPDATE `clients` SET `name`=?,`number`=?,`id_category`=?,`id_planning`=?,`id_floor_status`=?,`price`=?,`id_time_status`=?,`id_status`=?,`date`=NOW() WHERE `id_client`=?');
				$query->execute(array($_POST['name'],$_POST['number'],$_POST['id_category'],$_POST['id_planning'],$_POST['id_floor_status'] ,$_POST['cl_price'], $_POST['id_time_status'],$_POST['id_status'],$_POST['id']));
						
			echo "Запись отредактирована!";		
		}
	}
}
catch (PDOException $e) {
    echo 'Database error: '.$e->getMessage();
}