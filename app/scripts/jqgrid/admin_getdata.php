<?php
session_start();
require_once '../connect.php';
try {
		if($_GET['q']==1)
		{	
		    $curPage = $_POST['page'];
			$rowsPerPage = $_POST['rows'];
			$sortingField = $_POST['sidx'];
			$sortingOrder = $_POST['sord'];

			$enable="1";
			$qWhere = '';
				//определяем команду (поиск или просто запрос на вывод данных)
				//если поиск, конструируем WHERE часть запроса
				
		
				if (isset($_POST['_search']) && $_POST['_search'] == 'true') {
						$allowedFields = array('name_owner','number','id_district', 'name_street', 'house_number' ,'id_category' ,'room_count' , 'id_planning','id_building' , 'floor','number_of_floor' , 'space' ,'id_renovation','id_floor_status','id_window' ,'id_counter','id_sell_out_status', 'id_time_status' , 'price' , 'market_price','id_user' ,'name' , 'date');
						$allowedOperations = array('AND', 'OR');
						
						$searchData = json_decode($_POST['filters']);
		
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
		if($_GET['q']==2)
			{	
			   //читаем параметры
				$curPage = $_POST['page'];
				$rowsPerPage = $_POST['rows'];
				$sortingField = $_POST['sidx'];
				$sortingOrder = $_POST['sord'];
			
			
				$qWhere = '';
					//определяем команду (поиск или просто запрос на вывод данных)
					//если поиск, конструируем WHERE часть запроса
					
			
				if (isset($_POST['_search']) && $_POST['_search'] == 'true') {
					$allowedFields = array('login','active','id_right','name','number');
					$allowedOperations = array('AND', 'OR');
							
					$searchData = json_decode($_POST['filters']);
			
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
			$res = $dbh->prepare('SELECT `id_user`,`login`,`password`,`active`,`id_right`,`name`,`number` FROM `users`'.$qWhere.' ORDER BY '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
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
			$response->rows[$i]['cell']=array($row['id_user'],$row['login'],$row['password'],$password,$row['active'],$row['id_right'],$row['name'],$row['number']);
						
			$i++;
				}
			echo json_encode($response);
		}
		if($_GET['q']==3)
		{
				$curPage = $_GET['page'];
				$rowsPerPage = $_GET['rows'];
				$sortingField = $_GET['sidx'];
				$sortingOrder = $_GET['sord'];
			
				$enable="1";
			
				$qWhere = '';
					//определяем команду (поиск или просто запрос на вывод данных)
					//если поиск, конструируем WHERE часть запроса
					
			
				if (isset($_GET['_search']) && $_GET['_search'] == 'true') {
					$allowedFields = array('name','number','id_category','id_planning','id_floor_status','price', 'id_time_status','id_status','date');
					$allowedOperations = array('AND', 'OR');

					$searchData = json_decode($_GET['filters']);
			
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
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}
