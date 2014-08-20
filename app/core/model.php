<?php
namespace App\Core;

class Model
{
	public function connect()
    {
		require_once $_SERVER['DOCUMENT_ROOT'].'/app/scripts/connect.php';
		return $dbh;
    }
	
    public function get_data()
    {
		
    }
}