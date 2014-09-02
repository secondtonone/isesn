<?php
namespace App\Core;

class Model
{
	public function connect()
    {
		require $_SERVER['DOCUMENT_ROOT'].'/app/scripts/connect.php';
		return $dbh;
    }
	
	public function admin_getdata ()
	{

	}
	
	public function user_getdata ()
	{

	}
}