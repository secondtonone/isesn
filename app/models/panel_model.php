<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/core/model.php';

class panel_model extends \App\Core\Model
{
	public function admin_getdata ($page,$rows,$sidx,$sord,$search,$filters,$method)
	{
		$method='admin_'.$method;
		
		$this->$method($page,$rows,$sidx,$sord,$search,$filters);
	}
	public function user_getdata ($page,$rows,$sidx,$sord,$search,$filters,$method)
	{
		$method='user_'.$method;
		
		$this->$method($page,$rows,$sidx,$sord,$search,$filters);
	}
	private function admin_objects ($page,$rows,$sidx,$sord,$search,$filters)
	{
		$dbh=$this->connect();
		
		$curPage = $page;
		$rowsPerPage = $rows;
		$sortingField = $sidx;
		$sortingOrder = $sord;

		$enable="1";
		$qWhere = '';
		//определяем команду (поиск или просто запрос на вывод данных)
		//если поиск, конструируем WHERE часть запроса
				
		
		if (isset($search) && $search == 'true') {
			$allowedFields = array('name_owner','number','id_district', 'name_street', 'house_number' ,'id_category' ,'room_count' , 'id_planning','id_building' , 'floor','number_of_floor' , 'space' ,'id_renovation','id_floor_status','id_window' ,'id_counter','id_sell_out_status', 'id_time_status' , 'price' , 'market_price','id_user' ,'name' , 'date');
			$allowedOperations = array('AND', 'OR');
						
			$searchData = json_decode($filters);
		
			$qWhere = ' WHERE ';
			$firstElem = true;
		
			//объединяем все полученные условия
			foreach ($searchData->rules as $rule) {
				if (!$firstElem) {
					//объединяем условия (с помощью AND или OR)
					if (in_array($searchData->groupOp, $allowedOperations)) {
						$qWhere .= ' '.$searchData->groupOp.' ';
					}
					else {
					//если получили не существующее условие - возвращаем описание ошибки
						throw new Exception('Cool hacker is here!!! :)');
					}
				}
				else {
					$firstElem = false;
				}
								
							//вставляем условия
				if (in_array($rule->field, $allowedFields)) {
					$field='o.'.$rule->field;
						
					if ($rule->field=='name')
					{
						 $field='u.name';
					}
					if ($rule->field=='number')
					{
						 $field='ow.number';
					}
					if ($rule->field=='name_owner')
					{
						$field='ow.name_owner';
					}
					if ($rule->field=='name_street')
					{
						$field='st.name_street';
					}
					switch ($rule->op) {
						case 'lt': $qWhere .= $field.' < '.$dbh->quote($rule->data); break;
						case 'le': $qWhere .= $field.' <= '.$dbh->quote($rule->data); break;
						case 'gt': $qWhere .= $field.' > '.$dbh->quote($rule->data); break;
						case 'ge': $qWhere .= $field.' >= '.$dbh->quote($rule->data); break;
						case 'eq': $qWhere .= $field.' = '.$dbh->quote($rule->data); break;
						case 'ne': $qWhere .= $field.' <> '.$dbh->quote($rule->data); break;
						case 'bw': $qWhere .= $field.' LIKE '.$dbh->quote($rule->data.'%'); break;
						case 'cn': $qWhere .= $field.' LIKE '.$dbh->quote('%'.$rule->data.'%'); break;
						default: throw new Exception('Cool hacker is here!!! :)');
					}
				}
				else {
						//если получили не существующее условие - возвращаем описание ошибки
					throw new Exception('Cool hacker is here!!! :)');
				}
			}
		}
					 
		//определяем количество записей в таблице
		$rows = $dbh->prepare('SELECT COUNT(`id_object`) AS count FROM `objects` o '.$qWhere);
		$rows->execute(array());
			
		$totalRows = $rows->fetch(PDO::FETCH_ASSOC);

		$firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
			//получаем список из базы
		$res = $dbh->prepare('SELECT  o.`id_object` , o.`id_owner`, ow.`name_owner`,ow.`number`, o.`id_district`,o.`id_street`, o.`id_floor_status`,st.`name_street` , o.`house_number` ,o.`id_building`, o.`id_category`, o.`room_count` , o.`id_planning` , o.`floor`,o.`number_of_floor` , o.`space` , o.`id_renovation` , o.`id_window`, o.`id_counter`, o.`id_sell_out_status`, o.`id_time_status`, o.`price`, o.`market_price` ,o.`id_user`,u.`name` , o.`date` FROM `objects` o LEFT JOIN `objects_owners` ow ON o.`id_owner`= ow.`id_owner` LEFT JOIN `objects_street` st ON o.`id_street`= st.`id_street` LEFT JOIN `users` u ON o.`id_user`= u.`id_user` '.$qWhere.' ORDER BY '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
		$res->execute(array());
		
			//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
		$response = new stdClass();
		$response->page = $curPage;
		$response->total = ceil($totalRows['count'] / $rowsPerPage);
		$response->records = $totalRows['count'];
		
		$i=0;
			
		while($row = $res->fetch(PDO::FETCH_ASSOC)) {
						
			$response->rows[$i]['id']=$row['id_object'];
			$response->rows[$i]['cell']=array($row['id_object'] , $row['id_owner'], $row['name_owner'],$row['number'], $row['id_district'],$row['id_street'], $row['name_street'] , $row['house_number'],$row['id_building'],$row['id_category'], $row['room_count'] , $row['id_planning'], $row['floor'], $row['number_of_floor'],$row['id_floor_status'] , $row['space'], $row['id_sell_out_status'], $row['id_time_status'], $row['price'] , $row['market_price'] ,$row['id_user'],$row['name'],$row['date'],$enable);
					
			$i++;
		}
		echo json_encode($response);	
	}
	private function admin_users ($page,$rows,$sidx,$sord,$search,$filters)
	{
		$dbh=$this->connect();
		
		$curPage = $page;
		$rowsPerPage = $rows;
		$sortingField = $sidx;
		$sortingOrder = $sord;
			
			
		$qWhere = '';
					//определяем команду (поиск или просто запрос на вывод данных)
					//если поиск, конструируем WHERE часть запроса
					
			
		if (isset($search) && $search == 'true') {
			$allowedFields = array('login','active','id_right','name','number','time_activity','online');
			$allowedOperations = array('AND', 'OR');
							
			$searchData = json_decode($filters);
			
			$qWhere = ' WHERE ';
			$firstElem = true;
			
			//объединяем все полученные условия
			foreach ($searchData->rules as $rule) {
				if (!$firstElem) {
				//объединяем условия (с помощью AND или OR)
					if (in_array($searchData->groupOp, $allowedOperations)) {
						$qWhere .= ' '.$searchData->groupOp.' ';
					}
					else {
					//если получили не существующее условие - возвращаем описание ошибки
						throw new Exception('Cool hacker is here!!! :)');
					}
				}
				else
				{
					$firstElem = false;
				}
							
					//вставляем условия
				if (in_array($rule->field, $allowedFields)) {
					switch ($rule->op) {
						case 'eq': $qWhere .= $rule->field.' = '.$dbh->quote($rule->data); break;
						case 'ne': $qWhere .= $rule->field.' <> '.$dbh->quote($rule->data); break;
				 		case 'bw': $qWhere .= $rule->field.' LIKE '.$dbh->quote($rule->data.'%'); break;
						case 'cn': $qWhere .= $rule->field.' LIKE '.$dbh->quote('%'.$rule->data.'%'); break;
						default: throw new Exception('Cool hacker is here!!! :)');
					}
				}
				else {
					//если получили не существующее условие - возвращаем описание ошибки
					throw new Exception('Cool hacker is here!!! :)');
				}
			}
		}
						 
				//определяем количество записей в таблице
		$rows = $dbh->query('SELECT COUNT(*) AS count FROM `users`'.$qWhere);
		$totalRows = $rows->fetch(PDO::FETCH_ASSOC);
								
		$firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
				//получаем список из базы
		$res = $dbh->prepare('SELECT `id_user`,`login`,`password`,`active`,`id_right`,`name`,`number`,`online`,`time_activity` FROM `users`'.$qWhere.' ORDER BY '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
		$res->execute(array());
			
		//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
		$response = new stdClass();
		$response->page = $curPage;
		$response->total = ceil($totalRows['count'] / $rowsPerPage);
		$response->records = $totalRows['count'];
			
		$i=0;
		while($row = $res->fetch(PDO::FETCH_ASSOC)) {
			
			$row['password']="********************";
			$password='';
			
			$response->rows[$i]['id']=$row['id_user'];
			$response->rows[$i]['cell']=array($row['id_user'],$row['login'],$row['password'],$password,$row['active'],$row['id_right'],$row['name'],$row['number'],$row['online'],$row['time_activity']);
						
			$i++;
		}
		echo json_encode($response);
	}
	
	private function admin_clients ($page,$rows,$sidx,$sord,$search,$filters)
	{
		$dbh=$this->connect();
		
		$curPage = $page;
		$rowsPerPage = $rows;
		$sortingField = $sidx;
		$sortingOrder = $sord;
			
		$enable="1";
			
		$qWhere = '';
		//определяем команду (поиск или просто запрос на вывод данных)
		//если поиск, конструируем WHERE часть запроса
					
			
		if (isset($search) && $search == 'true') {
			$allowedFields = array('name','number','id_category','id_planning','id_floor_status','price', 'id_time_status','id_status','date');
			$allowedOperations = array('AND', 'OR');

			$searchData = json_decode($filters);
			
			$qWhere = ' AND ';
			$firstElem = true;
			
							//объединяем все полученные условия
			foreach ($searchData->rules as $rule) {
				if (!$firstElem) {
					//объединяем условия (с помощью AND или OR)
					if (in_array($searchData->groupOp, $allowedOperations)) {
						$qWhere .= ' '.$searchData->groupOp.' ';
					}
					else {
						//если получили не существующее условие - возвращаем описание ошибки
						throw new Exception('Cool hacker is here!!! :)');
					}
				}
				else
				{
					$firstElem = false;
				}
							
				//вставляем условия
				if (in_array($rule->field, $allowedFields)) {
					
					$field=$rule->field;
					
					switch ($rule->op) {
						case 'lt': $qWhere .= $field.' < '.$dbh->quote($rule->data); break;
						case 'le': $qWhere .= $field.' <= '.$dbh->quote($rule->data); break;
						case 'gt': $qWhere .= $field.' > '.$dbh->quote($rule->data); break;
						case 'ge': $qWhere .= $field.' >= '.$dbh->quote($rule->data); break;
						case 'eq': $qWhere .= $field.' = '.$dbh->quote($rule->data); break;
						case 'ne': $qWhere .= $field.' <> '.$dbh->quote($rule->data); break;
						case 'bw': $qWhere .= $field.' LIKE '.$dbh->quote($rule->data.'%'); break;
						case 'cn': $qWhere .= $field.' LIKE '.$dbh->quote('%'.$rule->data.'%'); break;
						default: throw new Exception('Cool hacker is here!!! :)');
					}
				}
				else {
				//если получили не существующее условие - возвращаем описание ошибки
					throw new Exception('Cool hacker is here!!! :)');
				}
			}
		}
						 
		//определяем количество записей в таблице
		$rows = $dbh->prepare('SELECT COUNT(*) AS count FROM `clients` c WHERE c.`id_user`=?'.$qWhere);
		$rows->execute(array($_GET["id_user"]));
			
		$totalRows = $rows->fetch(PDO::FETCH_ASSOC);
							
		$firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
		//получаем список из базы
		$res = $dbh->prepare('SELECT c.`id_client`, c.`name`, c.`number`, c.`id_category`, c.`id_planning`, c.`id_floor_status`,c.`price`, c.`id_time_status`,c.`id_status`, c.`id_user`, c.`date` FROM `clients` c WHERE c.`id_user`=? '.$qWhere.' ORDER BY '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
		$res->execute(array($_GET["id_user"]));
			
		//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
		$response = new stdClass();
		$response->page = $curPage;
		$response->total = ceil($totalRows['count'] / $rowsPerPage);
		$response->records = $totalRows['count'];
			
		$i=0;
		while($row = $res->fetch(PDO::FETCH_ASSOC)) {
						
			$response->rows[$i]['id']=$row['id_client'];
			$response->rows[$i]['cell']=array($row['id_client'],$row['name'],$row['number'],$row['id_category'],$row['id_planning'],$row['id_floor_status'],$row['price'],$row['id_time_status'],$row['id_status'],$row['id_user'],$row['date'],$enable);					
			$i++;
		}
		echo json_encode($response);		
	}
	private function user_objects ($page,$rows,$sidx,$sord,$search,$filters)
	{
		$dbh=$this->connect();
		
		$curPage = $page;
		$rowsPerPage = $rows;
		$sortingField = $sidx;
		$sortingOrder = $sord;
			
		$array_id = '';
		$qWhere = '';
		
		if (isset($search) && $search == 'true') {
			
			$allowedFields = array('name_owner','number','id_district', 'name_street', 'house_number' ,'id_category' ,'room_count' , 'id_planning','id_building' , 'floor','number_of_floor' , 'space' ,'id_renovation','id_floor_status','id_window' ,'id_counter','id_sell_out_status', 'id_time_status' , 'price' , 'market_price' ,'name' , 'date');
			$allowedOperations = array('AND', 'OR');
						
			$searchData = json_decode($filters);
		
			$qWhere = ' AND ';
			$firstElem = true;
		
			//объединяем все полученные условия
			foreach ($searchData->rules as $rule) {
				if (!$firstElem) {
				//объединяем условия (с помощью AND или OR)
					if (in_array($searchData->groupOp, $allowedOperations)) {
						$qWhere .= ' '.$searchData->groupOp.' ';
					}
					else {
					//если получили не существующее условие - возвращаем описание ошибки
						throw new Exception('Cool hacker is here!!! :)');
					}
				}
				else {
					$firstElem = false;
				}
								
				//вставляем условия
				if (in_array($rule->field, $allowedFields)) {
					
					$field='o.'.$rule->field;
						
					if ($rule->field=='name')
					{
						$field='u.name';
					}
					if ($rule->field=='number')
					{
						$field='ow.number';
					}
					if ($rule->field=='name_owner')
					{
						$field='ow.name_owner';
					}
					if ($rule->field=='name_street')
					{
						$field='st.name_street';
					}
					
					switch ($rule->op) {
						case 'lt': $qWhere .= $field.' < '.$dbh->quote($rule->data); break;
						case 'le': $qWhere .= $field.' <= '.$dbh->quote($rule->data); break;
						case 'gt': $qWhere .= $field.' > '.$dbh->quote($rule->data); break;
						case 'ge': $qWhere .= $field.' >= '.$dbh->quote($rule->data); break;
						case 'eq': $qWhere .= $field.' = '.$dbh->quote($rule->data); break;
						case 'ne': $qWhere .= $field.' <> '.$dbh->quote($rule->data); break;
						case 'bw': $qWhere .= $field.' LIKE '.$dbh->quote($rule->data.'%'); break;
						case 'cn': $qWhere .= $field.' LIKE '.$dbh->quote('%'.$rule->data.'%'); break;
						default: throw new Exception('Cool hacker is here!!! :)');
					}
				}
				else {
				//если получили не существующее условие - возвращаем описание ошибки
					throw new Exception('Cool hacker is here!!! :)');
				}
			}
		}
					 
		//определяем количество записей в таблице
		$rows = $dbh->prepare('SELECT COUNT(`id_object`) AS count FROM `objects` o WHERE (o.`id_sell_out_status`=1 OR o.`id_sell_out_status`=4)'.$qWhere);
		$rows->execute(array());
			
		$totalRows = $rows->fetch(PDO::FETCH_ASSOC);

		
		$rows = $dbh->prepare('SELECT `id_user` FROM `users` WHERE `id_user`<>? AND `id_right`=?');
		$rows->execute(array($_SESSION["id_user"],'user'));
			
		while($id_user=$rows->fetch(PDO::FETCH_ASSOC))
		{
			$array_id.=$id_user["id_user"].',';
		}

		$array_id = rtrim($array_id, ",");
			
		$firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
		//получаем список из базы
		$res = $dbh->prepare('SELECT o.`id_object` , o.`id_owner`, ow.`name_owner`,ow.`number`, o.`id_district`,o.`id_street`, o.`id_floor_status`,st.`name_street` , o.`house_number` ,o.`id_building`, o.`id_category`, o.`room_count` , o.`id_planning` , o.`floor`,o.`number_of_floor` , o.`space` , o.`id_renovation` , o.`id_window`, o.`id_counter`, o.`id_sell_out_status`, o.`id_time_status`, o.`price`, o.`market_price` ,o.`id_user`,u.`name` , o.`date` FROM `objects` o LEFT JOIN `objects_owners` ow ON o.`id_owner`= ow.`id_owner` LEFT JOIN `objects_street` st ON o.`id_street`= st.`id_street` LEFT JOIN `users` u ON o.`id_user`= u.`id_user` WHERE (o.`id_sell_out_status`=1 OR o.`id_sell_out_status`=4) '.$qWhere.' ORDER BY FIELD( o.`id_user` ,'.$array_id.'), '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
		$res->execute(array());
		
			//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
		$response = new stdClass();
		$response->page = $curPage;
		$response->total = ceil($totalRows['count'] / $rowsPerPage);
		$response->records = $totalRows['count'];
		
		$i=0;
		while($row = $res->fetch(PDO::FETCH_ASSOC)) {
			if ($row['id_user']==$_SESSION["id_user"]) 
			{
				$number=$row['number'];
				$enable="1";
			}
			else
			{
				$number="[скрыт]";
				$enable="0";
			}
				
				
			$response->rows[$i]['id']=$row['id_object'];
			$response->rows[$i]['cell']=array($row['id_object'] , $row['id_owner'], $row['name_owner'],$number, $row['id_district'],$row['id_street'], $row['name_street'] , $row['house_number'],$row['id_building'],$row['id_category'], $row['room_count'] , $row['id_planning'], $row['floor'], $row['number_of_floor'],$row['id_floor_status'] , $row['space'], $row['id_sell_out_status'], $row['id_time_status'], $row['price'] , $row['market_price'] ,$row['id_user'],$row['name'],$row['date'],$enable);
					
			$i++;
		}
		echo json_encode($response);
	}
	private function user_clients ($page,$rows,$sidx,$sord,$search,$filters)
	{
		$dbh=$this->connect();
		
		$curPage = $page;
		$rowsPerPage = $rows;
		$sortingField = $sidx;
		$sortingOrder = $sord;
			
		$array_id = '';
		$qWhere = '';
		//определяем команду (поиск или просто запрос на вывод данных)
		//если поиск, конструируем WHERE часть запроса
					
			
		if (isset($search) && $search == 'true') {
			$allowedFields = array('clname','name','number','id_category','id_planning','id_floor_status','price', 'id_time_status','id_status','date');
			$allowedOperations = array('AND', 'OR');

			$searchData = json_decode($filters);
			
			$qWhere = ' AND ';
			$firstElem = true;
			
			//объединяем все полученные условия
			foreach ($searchData->rules as $rule) {
				if (!$firstElem) {
				//объединяем условия (с помощью AND или OR)
					if (in_array($searchData->groupOp, $allowedOperations)) {
						$qWhere .= ' '.$searchData->groupOp.' ';
					}
					else {
						//если получили не существующее условие - возвращаем описание ошибки
						throw new Exception('Cool hacker is here!!! :)');
					}
				}
				else
				{
					$firstElem = false;
				}
							
				//вставляем условия
				if (in_array($rule->field, $allowedFields)) {
						
					$field=$rule->field;
						
					if ($rule->field=='name')
					{
						 $field='u.name';
					}
					if ($rule->field=='clname')
					{
						 $field='c.name';
					}
					if ($rule->field=='number')
					{
						 $field='c.number';
					}
					switch ($rule->op) {
							
						case 'lt': $qWhere .= $field.' < '.$dbh->quote($rule->data); break;
						case 'le': $qWhere .= $field.' <= '.$dbh->quote($rule->data); break;
						case 'gt': $qWhere .= $field.' > '.$dbh->quote($rule->data); break;
						case 'ge': $qWhere .= $field.' >= '.$dbh->quote($rule->data); break;
						case 'eq': $qWhere .= $field.' = '.$dbh->quote($rule->data); break;
						case 'ne': $qWhere .= $field.' <> '.$dbh->quote($rule->data); break;
						case 'bw': $qWhere .= $field.' LIKE '.$dbh->quote($rule->data.'%'); break;
						case 'cn': $qWhere .= $field.' LIKE '.$dbh->quote('%'.$rule->data.'%'); break;
						default: throw new Exception('Cool hacker is here!!! :)');
					}
				}
				else {
				//если получили не существующее условие - возвращаем описание ошибки
					throw new Exception('Cool hacker is here!!! :)');
				}
			}
		}
						 
			//определяем количество записей в таблице
		$rows = $dbh->prepare('SELECT COUNT(*) AS count FROM `clients` c WHERE c.`id_status`=? '.$qWhere);
		$rows->execute(array(1));
			
		$totalRows = $rows->fetch(PDO::FETCH_ASSOC);
					
		$rows = $dbh->prepare('SELECT `id_user` FROM `users` WHERE `id_user`<>? AND `id_right`=?');
		$rows->execute(array($_SESSION["id_user"],'user'));
			
		while($id_user=$rows->fetch(PDO::FETCH_ASSOC))
		{
			$array_id.=$id_user["id_user"].',';
		}

		$array_id = rtrim($array_id, ",");
					
		$firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
		//получаем список из базы
		$res = $dbh->prepare('SELECT c.`id_client`, c.`name` as clname, c.`number`, c.`id_category`, c.`id_planning`, c.`id_floor_status`,c.`price`, c.`id_time_status`,c.`id_status`, c.`id_user`,u.`name`, c.`date` FROM `clients` c LEFT JOIN `users` u ON c.`id_user`= u.`id_user` WHERE c.`id_status`=? '.$qWhere.' ORDER BY FIELD( c.`id_user` ,'.$array_id.'), '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
		$res->execute(array(1));
			
		//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
		$response = new stdClass();
		$response->page = $curPage;
		$response->total = ceil($totalRows['count'] / $rowsPerPage);
		$response->records = $totalRows['count'];
			
		$i=0;
		while($row = $res->fetch(PDO::FETCH_ASSOC)) {
			if ($row['id_user']==$_SESSION["id_user"]) 
			{
				$number=$row['number'];
				$enable="1";
			}
			else
			{
				$number="[скрыт]";
				$enable="0";
			}
						
			$response->rows[$i]['id']=$row['id_client'];
			$response->rows[$i]['cell']=array($row['id_client'],$row['clname'],$number,$row['id_category'],$row['id_planning'],$row['id_floor_status'],$row['price'],$row['id_time_status'],$row['id_status'],$row['id_user'],$row['name'],$row['date'],$enable);					
			$i++;
		}
		echo json_encode($response);
	}
	public function user_sub_objects ($id_object)
	{
		$dbh=$this->connect();
		
		$res = $dbh->prepare('SELECT o.`id_renovation`, o.`id_window`, o.`id_counter`,o.`id_user`,o.`date_change` FROM `objects` o WHERE o.`id_object`=?');
		$res->execute(array($id_object));
		
		//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
		$response = new stdClass();
		
		$i=0;
		
		while($row = $res->fetch(PDO::FETCH_ASSOC)) {
				
			if ($row['id_user']==$_SESSION["id_user"]) 
			{
				$enable="1";
			}
			else
			{		
				$enable="0";
			}

			$response->rows[$i]['cell']=array($row['id_renovation'], $row['id_window'],$row['id_counter'],$row['date_change'],$row['id_user'],$enable);
					
			$i++;
		}
		echo json_encode($response);
	}
	public function user_lists()
	{
		$dbh=$this->connect();
		
		$result='';
		$response = new stdClass();

		$res = $dbh->prepare('SELECT `id_building`, `name_building` FROM `objects_building`');
			
		$res->execute(array());
			
		$result=':выбрать...';
			
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_building'].':'.$row['name_building'];
		}
			
		$response->rows['building']=$result;

			

		$res = $dbh->prepare('SELECT `id_category`, `name_category` FROM `objects_category` ORDER BY `name_category`');
			
		$res->execute(array());
			
		$result=':выбрать...';
			
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_category'].':'.$row['name_category'];
		}
			
		$response->rows['category']=$result;
			
			
		$res = $dbh->prepare('SELECT `id_planning`, `name_planning` FROM `objects_planning`');
			
		$res->execute(array());
			
		$result=':выбрать...';
			
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_planning'].':'.$row['name_planning'];
		}
			
		$response->rows['planning']=$result;
			

		$res = $dbh->prepare('SELECT `id_sell_out_status`, `name_sell_out_status` FROM `objects_sell_out_status`');
			
		$res->execute(array());
			
		$result='';
					
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_sell_out_status'].':'.$row['name_sell_out_status'];
		}
	
		$response->rows['sellstatus']=trim($result,';');


		$res = $dbh->prepare('SELECT `id_time_status`, `name_time_status` FROM `objects_time_status`');
			
		$res->execute(array());
			
		$result='';
					
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_time_status'].':'.$row['name_time_status'];
		}
			
		$response->rows['timestatus']=trim($result,';');

			

		$res = $dbh->prepare('SELECT `id_renovation`, `name_renovation` FROM `objects_renovation`');
			
		$res->execute(array());
			
		$result='';
					
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_renovation'].':'.$row['name_renovation'];
		}
			
		$response->rows['renovation']=trim($result,';');
			

		$res = $dbh->prepare('SELECT `id_window`, `name_window` FROM `objects_window`');
			
		$res->execute(array());
			
		$result='';
					
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_window'].':'.$row['name_window'];
		}
			
		$response->rows['window']=trim($result,';');
			
			
	
		$res = $dbh->prepare('SELECT `id_counter`, `name_counter` FROM `objects_counter`');
		
		$res->execute(array());
			
		$result='';
					
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_counter'].':'.$row['name_counter'];
		}
			
		$response->rows['counter']=trim($result,';');
			
			

		$res = $dbh->prepare('SELECT `id_district`, `name_district` FROM `objects_district`');
			
		$res->execute(array());
			
		$result=':выбрать...';
			
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_district'].':'.$row['name_district'];
		}
			
		$response->rows['district']=$result;
			
			

		$res = $dbh->prepare('SELECT `id_floor_status`, `name_floor_status` FROM `clients_floor_status`');
			
		$res->execute(array());
			
		$result='';
					
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_floor_status'].':'.$row['name_floor_status'];
		}
			
		$response->rows['floor']=trim($result,';');
			
			

		$res = $dbh->prepare('SELECT `id_type_event`, `name_type_event` FROM `users_type_event`');
			
		$res->execute(array());
			
		$result='';
					
		while ($row = $res->fetch(PDO::FETCH_ASSOC)) 
		{
			$result.=';'.$row['id_type_event'].':'.$row['name_type_event'];
		}
			
		$response->rows['type']=trim($result,';');
			
		echo json_encode($response);
	}
	
}