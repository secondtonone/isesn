<?php

namespace App\Controllers;

class panel_controller extends \App\Core\Controller
{
	
	
	function __construct()
    {
        $this->view = new \App\Core\View();
		$this->bootstrap='help_bootstrap.php';
		$this->template='template_view.php';
		
    }
	
    function user()
    {	
        $this->view->generate('help_user_view.php', $this->template,$this->bootstrap);
    }
	
	function admin()
    {	
        $this->view->generate('help_admin_view.php', $this->template,$this->bootstrap);
    }
}