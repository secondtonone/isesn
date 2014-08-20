<?php
session_start();
require_once '../connect.php';

try {
	
	$term=$_GET["term"];
	$response=array();

	if ($_GET['q']=="street")
	{

		$res = $dbh->prepare("SELECT `id_street`, `name_street` FROM `objects_street` WHERE `name_street` LIKE ?");
		$res->execute(array("%$term%"));
		
		while($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$response[]=array('value' => $row["id_street"],'label' =>$row["name_street"]);
	    }
    	echo json_encode($response);
	}
	if ($_GET['q']=="user")
	{

		$res = $dbh->prepare("SELECT `id_user`, `name` FROM `users` WHERE `name` LIKE ?");
		
		$res->execute(array("%$term%"));
		
		while($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$response[]=array('value' => $row["id_user"],'label' =>$row["name"]);
	    }
    	echo json_encode($response);
	}
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}
