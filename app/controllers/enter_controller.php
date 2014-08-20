<?php

namespace App\Controllers;

class enter_controller extends \App\Core\Controller
{
	function __construct()
    {
        $this->view = new \App\Core\View();
		$this->index();
    }
	
    function index()
    {	
        $this->view->generate('enter_view.php', 'template_view.php','enter_bootstrap.php');
    }
}