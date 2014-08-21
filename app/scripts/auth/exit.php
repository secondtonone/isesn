<?php
session_start();

require_once 'reg_class.php';

$result=new \App\Scripts\Auth\reg_class();
	
$result->unauthorize();

exit;