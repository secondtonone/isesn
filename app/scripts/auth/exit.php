<?php
/*
 * Запускаем сессию
 */
session_start();
/*
 * Удаляем данные из сессии
 */
setcookie ("l", '',time()-3600,"/");
setcookie ("h", '',time()-3600,"/");	
session_unset();
/*
 * Закрываем сессию
 */
session_destroy();
// удалить hash из БД, почистить куки