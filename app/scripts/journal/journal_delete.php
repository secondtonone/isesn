<?php

$path=str_replace("journal","connect.php",dirname(__FILE__));

require $path;

try {
	$result='';
	
	$query=$dbh->prepare('DELETE FROM `users_journal` WHERE (MONTH(NOW())-MONTH(`time_event`))>2');	
	$query->execute(array());
	
}
catch (PDOException $e) {
    echo 'Database error: '.$e->getMessage();
}