<?php
/*роутер*/
namespace App\Core;

class Route
{
	/*функция для проверки вводимого адреса*/
	static function check_route($str)
	{
		$str = trim($str);
		
		if (preg_match("/^[a-zA-Z0-9]+$/",$str)) 
		{			
			if (strlen($str)>15)
			{
				throw new \Exception();
			}
			else
			{
				return $str;
			}
		}
		else 
		{
			throw new \Exception();
		}
	}
	/*функция для проверки сессии, сравниваются хеши*/
	static function check_session($login,$hash)
	{
		$db_hash=\App\Scripts\Auth\reg_class::check_hash(array($login));
				
		if($db_hash==$hash)
		{
			return $hash;
		}
		else
		{
			return $db_hash;
		}
	}
	
	/*функция для проверки кук, сравниваются хеши*/
	static function check_cookies($login,$hash_cookie)
	{
		\App\Scripts\Auth\reg_class::check_cookies(array($login),$hash_cookie);
	}		
	/*главная функция для роута*/
    static function start()
    {
		try {
			
			/*контроллер и действие по умолчанию*/
			$controller_name = 'enter';
        	$action_name = 'index';
			
			/*парсинг URI*/
			$routes = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
			$routes = explode('/', $routes);
			$routs_count = count($routes);
				
			/*если куки существует, происходит их проверка и запись в сессию*/
			if (isset($_COOKIE["l"]) and isset($_COOKIE["h"]))
			{
				self::check_cookies($_COOKIE["l"],$_COOKIE["h"]);
			}
			/*если сессия существует, происходит проверка*/
			if (isset($_SESSION["login"]) and isset($_SESSION["hash"]))
			{
				$session=self::check_session($_SESSION["login"],$_SESSION["hash"]);								
			}
			/*сессия проходит проверку, происходит перенаправление на рабочую панель*/
			if (!empty($session))
			{	
				/*контроллер по умолчанию*/
				$controller_name = 'panel';
				/*действия по умолчанию для админа и для юзера*/
				if ($_SESSION["id_right"] == 'admin')
				{
					$action_name = 'admin';
				}
				if ($_SESSION["id_right"] == 'user')
				{
					$action_name = 'user';
				}	
				/*новый контроллер из распарсенного URI*/
				if (!empty($routes[1]))
				{	
					$controller_name = self::check_route($routes[1]);						
				}
				/*новое действие из распарсенного URI*/
				if (!empty($routes[2]))
				{	
					$action_name = self::check_route($routes[2]);
				}
				if ($routs_count > 3)
				{	
					throw new \Exception();
				}
									
			}
			else
			{
				throw new \Exception();
			}
									
			/*название класса через namespace*/
			$controller_name = '\\App\\Controllers\\'.$controller_name.'_controller';
				
			/*проверка существует ли такой класс*/		
			if(class_exists($controller_name))
			{
				$controller = new $controller_name();
			}
			else
			{
				throw new \Exception();
			}	
				
			/*проверяет существует ли метод*/
			if(method_exists($controller,$action_name))
			{
				$controller -> $action_name();
			}
			else
			{
				throw new \Exception();
			}
			
		}
		catch (\Exception $e) 
		{
			/*исключение, перенаправление на главную страницу*/
			$controller_name = 'enter';
        	$action_name = 'index';
			/*исключение, перенаправление на главную страницу панели для пользователей*/
			if (isset($_SESSION["login"]) and isset($_SESSION["hash"]))
			{
				$session=self::check_session($_SESSION["login"],$_SESSION["hash"]);
			}
			if (!empty($session))
			{
				$controller_name = 'panel';
				
				if ($_SESSION["id_right"] == 'admin')
				{
					$action_name = 'admin';
				}
				if ($_SESSION["id_right"] == 'user')
				{
					$action_name = 'user';
				}	
			}
			
			$controller_name='\\App\\Controllers\\'.$controller_name.'_controller';	
			
			$controller = new $controller_name();
			$controller -> $action_name();		
		}
	}
}