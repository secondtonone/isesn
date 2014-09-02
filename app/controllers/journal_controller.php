<?php

namespace App\Controllers;

class journal_controller extends \App\Core\Controller
{
	function __construct()
    {
        $this->view = new \App\Core\View();
		$this->bootstrap='journal_bootstrap.php';
		$this->template='template_view.php';
    }
	
	function admin()
    {	
        $this->view->generate('journal_view.php', $this->template,$this->bootstrap);
    }
}