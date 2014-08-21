<?php 
session_start();
require_once '../connect.php';
try {
			
	$html_out='';
			
	$res = $dbh->prepare('SELECT `id_user`,`login`,`name`,`online` FROM `users` WHERE `active`=? ORDER BY `online` DESC, `login` asc');
	$res->execute(array(1));
			
	while($row = $res->fetch(PDO::FETCH_ASSOC))
	{
		$html_temp = '<div id="'.$row['id_user'].'" class="user '.$row['online'].'">
                		<div class="marker '.$row['online'].'"></div>
                    	<div class="wrap">
                        	<div class="login">'.$row['login'].'</div>
                        	<div class="name">'.$row['name'].'</div>
                    	</div>
                	</div>';
					
		$html_out .=$html_temp;
	}
			
	echo $html_out;
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}