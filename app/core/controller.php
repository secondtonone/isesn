<?php
namespace App\Core;

class Controller {
    
    public $model;
    public $view;
	public $bootstrap;
	public $template;
    
    function __construct()
    {
        $this->view = new \App\Core\View();
		$this->index();
    }
    
    function index()
    {
		
    }
}