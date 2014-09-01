<?php

$path=str_replace("journal","connect.php",dirname(__FILE__));

require $path;

try {
/*	$result='';
	
	 $query=$dbh->prepare('SELECT `id_user` FROM `users` WHERE `online`=? AND `active`=?');
		
	$query->execute(array('online',1));
	
	while($row = $query->fetch(PDO::FETCH_ASSOC)) 
	{
			$result.=$row['id_user'];
	}
	echo $result;*/
	
}
catch (PDOException $e) {
    echo 'Database error: '.$e->getMessage();
}