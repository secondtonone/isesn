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

			$qWhere = '';
				//определяем команду (поиск или просто запрос на вывод данных)
				//если поиск, конструируем WHERE часть запроса
				
		
				if (isset($_POST['_search']) && $_POST['_search'] == 'true') {
						$allowedFields = array('id_notification', 'text_notification', 'id_status');
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
										switch ($rule->op) {
												case 'lt': $qWhere .= 'n.'.$rule->field.' < '.$dbh->quote($rule->data); break;
												case 'le': $qWhere .= 'n.'.$rule->field.' <= '.$dbh->quote($rule->data); break;
												case 'gt': $qWhere .= 'n.'.$rule->field.' > '.$dbh->quote($rule->data); break;
												case 'ge': $qWhere .= 'n.'.$rule->field.' >= '.$dbh->quote($rule->data); break;
												case 'eq': $qWhere .= 'n.'.$rule->field.' = '.$dbh->quote($rule->data); break;
												case 'ne': $qWhere .= 'n.'.$rule->field.' <> '.$dbh->quote($rule->data); break;
												case 'bw': $qWhere .= 'n.'.$rule->field.' LIKE '.$dbh->quote($rule->data.'%'); break;
												case 'cn': $qWhere .= 'n.'.$rule->field.' LIKE '.$dbh->quote('%'.$rule->data.'%'); break;
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
			$rows = $dbh->prepare('SELECT COUNT(`id_notification`) AS count FROM `notifications` n '.$qWhere);
			$rows->execute(array());
			
			$totalRows = $rows->fetch(PDO::FETCH_ASSOC);

		
			
			
			$firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
			//получаем список из базы
			$res = $dbh->prepare('SELECT n.`id_notification`, n.`text_notification`, n.`id_status` FROM `notifications` n '.$qWhere.' ORDER BY '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
			$res->execute(array());
		
			//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
			$response = new stdClass();
			$response->page = $curPage;
			$response->total = ceil($totalRows['count'] / $rowsPerPage);
			$response->records = $totalRows['count'];
		
			$i=0;
			while($row = $res->fetch(PDO::FETCH_ASSOC)) {
						
				$response->rows[$i]['id']=$row['id_notification'];
				$response->rows[$i]['cell']=array($row['id_notification'] , $row['text_notification'], $row['id_status']);
					
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
					$allowedFields = array('id_event', 'name', 'id_type_event', 'time_event');
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
			$rows = $dbh->query('SELECT COUNT(*) AS count FROM `users_journal` '.$qWhere.'');
			$totalRows = $rows->fetch(PDO::FETCH_ASSOC);
					
			
					
			$firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
				//получаем список из базы
			$res = $dbh->prepare('SELECT j.`id_event`, u.`name`, j.`id_type_event`, j.`time_event` FROM `users_journal` j LEFT JOIN `users` u ON j.`id_user`= u.`id_user` '.$qWhere.' ORDER BY '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
			$res->execute(array());
			
				//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
			$response = new stdClass();
			$response->page = $curPage;
			$response->total = ceil($totalRows['count'] / $rowsPerPage);
			$response->records = $totalRows['count'];
			
			$i=0;
			while($row = $res->fetch(PDO::FETCH_ASSOC)) {
					
			$response->rows[$i]['id']=$row['id_event'];
			$response->rows[$i]['cell']=array($row['id_event'],$row['name'],$row['id_type_event'],$row['time_event']);
						
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
						switch ($rule->op) {
							case 'lt': $qWhere .= $rule->field.' < '.$dbh->quote($rule->data); break;
												case 'le': $qWhere .= $rule->field.' <= '.$dbh->quote($rule->data); break;
												case 'gt': $qWhere .= $rule->field.' > '.$dbh->quote($rule->data); break;
												case 'ge': $qWhere .= $rule->field.' >= '.$dbh->quote($rule->data); break;
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
