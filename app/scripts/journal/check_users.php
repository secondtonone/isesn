<?php

$path=str_replace("journal","connect.php",dirname(__FILE__));

require $path;

try {
	
	    $query=$dbh->prepare('SELECT `id_user`,`time_activity` FROM `users` WHERE (`time_activity`+INTERVAL 5 MINUTE) < (NOW()+INTERVAL 2 HOUR) AND `online`=? AND `active`=?');
		
		$query->execute(array('online',1));
		
		while($row = $query->fetch(PDO::FETCH_ASSOC)) 
		{
			$res=$dbh->prepare('UPDATE `users` SET `online`=? WHERE `id_user`=?');
				
			$res->execute(array('offline',$row['id_user']));
			
			$res=$dbh->prepare('INSERT INTO `users_journal`(`id_user`, `id_type_event`,`time_event`) VALUES (?,?,?)');
				
			$res->execute(array($row['id_user'],4,$row['time_activity']));	
		}	
}
catch (PDOException $e) {
    echo 'Database error: '.$e->getMessage();
}