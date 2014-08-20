<?php

namespace App\Controllers;

class panel_controller extends \App\Core\Controller
{
	function __construct()
    {
        $this->view = new \App\Core\View();
		$this->template='template_view.php';
    }
	
    function user()
    {	
        $this->view->generate('panel_user_view.php', $this->template,'panel_user_bootstrap.php');
    }
	
	function admin()
    {	

        $this->view->generate('panel_admin_view.php', $this->template,'panel_admin_bootstrap.php');
    }
}