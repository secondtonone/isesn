<?php
/*файл для начальной загрузки классов через область видимости*/
session_start();

define('APP_PATH', __DIR__);
set_include_path(APP_PATH); 
spl_autoload_extensions(".php"); 
spl_autoload_register();
/*роутинг*/
App\Core\Route::start(); 