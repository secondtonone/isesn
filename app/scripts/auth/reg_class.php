<?php
namespace App\Scripts\Auth;

define('ROOT',$_SERVER['DOCUMENT_ROOT'].'/app/scripts/connect.php');

class reg_class {
	
	private $path = ROOT;
	private $query_for_data = 'SELECT * FROM `users` WHERE login=?';
	private $query_for_upd = 'UPDATE `users` SET `hash`=? WHERE `id_user`=?';
	private $query = 'UPDATE `users` SET `browser`=?,`online`=?,`time_activity`=NOW()+INTERVAL 2 HOUR WHERE `id_user`=?';
	private $query_for_journal = 'INSERT INTO `users_journal`(`id_user`, `id_type_event`,`time_event`) VALUES (?,?,NOW()+INTERVAL 2 HOUR)';
	
	public function authorize ($login,$password,$checked=NULL)
	{
		
		$login=$this->check_name($login);
		$password=$this->check_pass($password);
		
		$data=$this->get_data(array($login),$this->query_for_data);
		
		if(!empty($data["id_user"]))
		{
			if($data["password"]==$password AND $data["active"]==1)
			{	
				$ip=$_SERVER['REMOTE_ADDR'];
				$browser=$_SERVER['HTTP_USER_AGENT'];
				$time=date('G:i:sO Y-m-d');
				
				$this->upd_data(array($browser,'online',$data["id_user"]),$this->query);
				//добавить запрос на добавления события в журнал
								
				$_SESSION["id_user"]=$data["id_user"];
				$_SESSION["login"]=$data["login"];
				$_SESSION["id_right"]=$data["id_right"];
				
				$_SESSION["hash"]=$this->make_hash($data);
				
				$this->upd_data(array($data["id_user"],1),$this->query_for_journal);
				
				if (!empty($checked))
				{
					$cookie_hash=$this->make_cookies($data["login"],$browser);
					
					setcookie ("l", $data["login"],time()+604800,"/");
					setcookie ("h", $cookie_hash,time()+604800,"/");					
				}

				return false;
			}
			else
			{
				echo "Вы ввели не верный пароль! ";
				return false;
			}
		}
		else
		{
			echo "Вы ввели не верные данные! ";
			return false;
		}
	}
		
	protected function check_data($str)
	{
		$str = trim($str);
		
		if (preg_match("/^[a-zA-Z0-9]+$/",$str)) 
		{
			return $str;
		}
		else
		{
			return false;
		}
	}
	
	protected function connect()
    {
		require ($this->path);
		return $dbh;
    }
	
	public static function check_cookies ($login,$hash_cookie) 
	{	
		$reg_class=new self;
			
		$data=$reg_class->get_data($login,$reg_class->query_for_data);
		
		$gen_hash=$reg_class->make_cookies($data["login"],$data["browser"]);
		/*добавить проверка на активность*/
		if($gen_hash==$hash_cookie AND $data["active"]==1)
		{
			$ip=$_SERVER['REMOTE_ADDR'];
			$browser=$_SERVER['HTTP_USER_AGENT'];
			
			$time=date('G:i:sO Y-m-d');
				
			$reg_class->upd_data(array($browser,'online',$data["id_user"]),$reg_class->query);
				//добавить запрос на добавления события в журнал
								
			$_SESSION["id_user"]=$data["id_user"];
			$_SESSION["login"]=$data["login"];
			$_SESSION["id_right"]=$data["id_right"];
				
			$_SESSION["hash"]=$reg_class->make_hash($data);
			
			$reg_class->upd_data(array($data["id_user"],1),$reg_class->query_for_journal);
		}
		else
		{
			return false;
		}
	}
	
	public static function check_hash ($data) 
	{
		
		$reg_class=new self;	
		
		$data=$reg_class->get_data ($data,$reg_class->query_for_data);
		
		return $data['hash'];
	}
	
	public static function make_cookies ($login,$browser) 
	{
		$cookie_hash=md5($login.$browser);
		
		return $cookie_hash;
	}
	
	protected function make_hash ($data) 
	{
		$hash='';
		$dbh=$this->connect();
		
		foreach($data as $value)
		{
			$hash.=md5($value);
		}
		
		$hash=md5($hash.date("h i s"));
		
		$this->upd_data(array($hash,$data['id_user']),$this->query_for_upd);
		
		return $hash;
	}
	
	protected function upd_data($data,$query) 
	{
		$dbh=$this->connect();
		$query = $dbh->prepare($query);
		$query->execute($data);
	}
	
	protected function get_data ($data,$query) 
	{
		$dbh=$this->connect();
		$query = $dbh->prepare($query);
		$query->execute($data);
		$row = $query->fetch(\PDO::FETCH_ASSOC);
		return $row;
	}
	
	public function unauthorize () 
	{
		$this->upd_data(array($_SERVER['HTTP_USER_AGENT'],'offline',$_SESSION["id_user"]),$this->query);
		$this->upd_data(array($_SESSION["id_user"],2),$this->query_for_journal);
		
		setcookie ("l", '',time()-3600,"/");
		setcookie ("h", '',time()-3600,"/");	
		
		session_unset();
		session_destroy();	
	}
		
	protected function check_name ($string) 
	{
		if(isset($string) and !empty($string))
		{
			if (strlen($string)<6)
			{ 
				echo "Логин должен содержать больше 5 символов! ";
				return false;
			}
			else
			{
				$string=$this->check_data($string);
				return $string;
			}
		}
		else
		{
			echo "Вы не ввели логин! ";
			return false;
		}	
	}
	
	protected function check_pass ($string)
	{
		
		if((isset($string) and !empty($string)))
		{
			$string=$this->check_data($string);
			
			if (strlen($string)<6)
			{
				echo "Пароль должен содержать больше 5 символов. ";
				return false;
			}		
			else
			{
				$string=md5($string.'salt');
				
				return $string;
			}
		}
		else
		{
			echo "Вы не ввели пароль! ";
			return false;
		}
	}		  
}

