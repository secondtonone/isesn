<?php

namespace App\Controllers;

class panel_controller extends \App\Core\Controller
{
	function __construct()
    {
        $this->view = new \App\Core\View();
		$this->bootstrap='stats_bootstrap.php';
		$this->template='template_view.php';
    }

	function admin()
    {	
        $this->view->generate('stats_view.php', $this->template,$this->bootstrap);
    }
}