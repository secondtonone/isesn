<?php
session_start();
/*
* Подключаемся к базе
*/

require_once 'reg_class.php';

/*
* Обрабатываем переданное имя
*/

if (isset($_POST["form"]))
{
	$login=$_POST["login"];
	$password=$_POST["password"];
	
	if (isset($_POST["checked"]))
	{
		$checked=$_POST["checked"];
	}
	else
	{
		$checked='';
	}
	
	$result=new \App\Scripts\Auth\reg_class();
	
	$result->authorize($login,$password,$checked);
}
else
{
	exit;
}