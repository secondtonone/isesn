<?php
namespace App\Core;

class Model
{
	public function connect()
    {
		require $_SERVER['DOCUMENT_ROOT'].'/app/scripts/connect.php';
		return $dbh;
    }
}