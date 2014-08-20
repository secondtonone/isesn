<?php
session_start();
require_once '../connect.php';
try {

	if ($_GET['q']==1)
	{   	
		$curPage = $_GET['page'];
		$rowsPerPage = $_GET['rows'];
		$sortingField = $_GET['sidx'];
		$sortingOrder = $_GET['sord'];
		$array_id = '';
	
		$qWhere = '';
		//определяем команду (поиск или просто запрос на вывод данных)
		//если поиск, конструируем WHERE часть запроса
		
	
		if (isset($_GET['_search']) && $_GET['_search'] == 'true') {
			$allowedFields = array('name_owner','number','id_district', 'name_street', 'house_number' ,'id_category' ,'room_count' , 'id_planning','id_building' , 'floor','number_of_floor' , 'space' ,'id_renovation','id_floor_status','id_window' ,'id_counter','id_sell_out_status', 'id_time_status' , 'price' , 'market_price' ,'name' , 'date');
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
		
			$rows = $dbh->prepare('SELECT COUNT(`id_object`) AS count FROM `objects` o WHERE (o.`id_sell_out_status`=1 OR o.`id_sell_out_status`=4)'.$qWhere);
			$rows->execute(array());
				
			$totalRows = $rows->fetch(PDO::FETCH_ASSOC);
	
			
			$rows = $dbh->prepare('SELECT `id_user` FROM `users` WHERE `id_user`<>? AND `id_right`=? AND `active`=?');
			$rows->execute(array($_SESSION["id_user"],'user',1));
				
			while($id_user=$rows->fetch(PDO::FETCH_ASSOC))
			{
				$array_id.=$id_user["id_user"].',';
			}
	
			$array_id = rtrim($array_id, ",");
	
		
		$firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
		//получаем список из базы
		$res = $dbh->prepare('SELECT o.`id_object` , ow.`name_owner`,ow.`number`,d.`name_district`, st.`name_street` , o.`house_number`,b.`name_building`, cat.`name_category` , o.`room_count` , p.`name_planning` , o.`floor`,o.`number_of_floor` , o.`space`,r.`name_renovation` ,w.`name_window` , c.`name_counter`,s.`name_sell_out_status`, t.`name_time_status` , o.`price` , o.`market_price`,o.`id_user`,u.`name` , o.`date` FROM `objects` o LEFT JOIN `objects_owners` ow ON o.`id_owner`= ow.`id_owner` LEFT JOIN `objects_street` st ON o.`id_street`= st.`id_street` LEFT JOIN `objects_building` b ON o.`id_building`= b.`id_building` LEFT JOIN `objects_category` cat ON o.`id_category`= cat.`id_category` LEFT JOIN `objects_planning` p ON  o.`id_planning`= p.`id_planning` LEFT JOIN `objects_district` d ON  o.`id_district`= d.`id_district` LEFT JOIN `objects_renovation` r ON o.`id_renovation`=r.`id_renovation` LEFT JOIN `objects_window` w ON o.`id_window`=w.`id_window` LEFT JOIN `objects_counter` c ON o.`id_counter`= c.`id_counter` LEFT JOIN `objects_sell_out_status` s ON o.`id_sell_out_status`=s.`id_sell_out_status` LEFT JOIN `objects_time_status` t ON  o.`id_time_status`= t.`id_time_status` LEFT JOIN `users` u ON o.`id_user`= u.`id_user` WHERE (o.`id_sell_out_status`=1 OR o.`id_sell_out_status`=4) '.$qWhere.' ORDER BY FIELD( o.`id_user` ,'.$array_id.'), '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
			$res->execute(array());
		//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
		$response = new stdClass();
		$response->page = $curPage;
		$response->total = ceil($totalRows['count'] / $rowsPerPage);
		$response->records = $totalRows['count'];
	
	  $filename = "Данные об объектах " . date('Y-m-d') . ".xls";
	
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel");
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
	<html xmlns="http://www.w3.org/1999/xhtml"> 
	<head> 
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	</head>';
	 echo '<table width="1000" border="1"> 
	  <tr> 
		<td>№</td> 
		<td>Собственник</td> 
		<td>Телефон</td>
		<td>Район</td> 
		<td>Улица</td>
		<td>№ дома</td>
		<td>Тип здания</td>  
		<td>Категория</td> 
		<td>Кол-во комнат</td>
		<td>Планировка</td> 
		<td>Этаж</td>
		<td>Этажность</td>
		<td>Площадь, м. кв.</td> 
		<td>Статус</td>
		<td>Статус по времени</td>
		<td>Цена</td>
		<td>Цена с комиссией</td>
		<td>Менеджер</td>
		<td>Ремонт</td>
		<td>Окна</td>
		<td>Счетчики</td>
		<td>Дата</td>
	 </tr>';
	  while($row = $res->fetch(PDO::FETCH_ASSOC)) {
		  
		 if ($row['id_user']==$_SESSION["id_user"]) 
		 {	
			$number=$row['number'];
		 }
		 else
		 {
			$number="[скрыт]";
		 }
	echo  '<tr> 
		<td>'.$row['id_object'].'</td> 
		<td>'.$row['name_owner'].'</td>
		<td>'.$number.'</td>
		<td>'.$row['name_district'].'</td>   
		<td>'.$row['name_street'].'</td> 
		<td>'.$row['house_number'].'</td> 
		<td>'.$row['name_building'].'</td> 
		<td>'.$row['name_category'].'</td> 
		<td>'.$row['room_count'].'</td> 
		<td>'.$row['name_planning'].'</td>
		<td>'.$row['floor'].'</td>  
		<td>'.$row['number_of_floor'].'</td> 
		<td>'.$row['space'].'</td> 
		<td>'.$row['name_sell_out_status'].'</td>
		<td>'.$row['name_time_status'].'</td> 
		<td>'.$row['price'].'</td> 
		<td>'.$row['market_price'].'</td> 
		<td>'.$row['name'].'</td>
		<td>'.$row['name_renovation'].'</td>  
		<td>'.$row['name_window'].'</td>  
		<td>'.$row['name_counter'].'</td>    
		<td>'.$row['date'].'</td> 
		  </tr>';
	 }
	 echo '</table>';
	}
	if($_GET['q']==2)
	{
		$curPage = $_GET['page'];
		$rowsPerPage = $_GET['rows'];
		$sortingField = $_GET['sidx'];
		$sortingOrder = $_GET['sord'];
		$array_id = '';
	
		$qWhere = '';
		//определяем команду (поиск или просто запрос на вывод данных)
		//если поиск, конструируем WHERE часть запроса
		
	
		if (isset($_GET['_search']) && $_GET['_search'] == 'true') {
			$allowedFields = array('clname','name','number','id_category','id_planning','id_floor_status','price', 'id_time_status','id_status','date');
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
				else {
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

		$res = $dbh->prepare('SELECT c.`id_client` , c.`name` as clname, c.`number` , cat.`name_category` , p.`name_planning`,f.`name_floor_status`,t.`name_time_status`, c.`price` , s.`name_status`,c.`id_user`,u.`name`, c.`date` 
FROM `clients` c LEFT JOIN `objects_planning` p ON c.`id_planning` = p.`id_planning` LEFT JOIN `clients_status` s ON c.`id_status` = s.`id_status` LEFT JOIN `objects_category` cat ON c.`id_category` = cat.`id_category` LEFT JOIN `clients_floor_status` f ON c.`id_floor_status` = f.`id_floor_status` LEFT JOIN `clients_time_status` t ON c.`id_time_status` = t.`id_time_status` LEFT JOIN `users` u ON c.`id_user`= u.`id_user` WHERE c.`id_status` =? '.$qWhere.' ORDER BY FIELD( c.`id_user` ,'.$array_id.'), '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
		$res->execute(array(1));
		
		//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
		$response = new stdClass();
		$response->page = $curPage;
		$response->total = ceil($totalRows['count'] / $rowsPerPage);
		$response->records = $totalRows['count'];
	
	    $filename = "Данные о покупателях " . date('Y-m-d') . ".xls";
	
		header("Content-Disposition: attachment; filename=\"$filename\"");
		header("Content-Type: application/vnd.ms-excel");
		echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
		<html xmlns="http://www.w3.org/1999/xhtml"> 
		<head> 
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
		</head>';
		 echo '<table width="800" border="1"> 
		  <tr> 
			<td>№</td> 
			<td>Покупатель</td> 
			<td>Телефон</td> 
			<td>Категория</td>
			<td>Планировка</td>
			<td>Этажность</td>  
			<td>Цена</td>
			<td>Статус по времени</td>
			<td>Статус</td>
			<td>Менеджер</td>
			<td>Дата</td>
		 </tr>';
	  while($row = $res->fetch(PDO::FETCH_ASSOC)) {
		  
		if ($row['id_user']==$_SESSION["id_user"]) 
		 {	
			$number=$row['number'];
		 }
		 else
		 {
			$number="[скрыт]";
		 }
		 
		echo  '<tr> 
			<td>'.$row['id_client'].'</td> 
			<td>'.$row['clname'].'</td>
			<td>'.$number.'</td> 
			<td>'.$row['name_category'].'</td> 
			<td>'.$row['name_planning'].'</td>
			<td>'.$row['name_floor_status'].'</td> 
			<td>'.$row['price'].'</td>
			<td>'.$row['name_time_status'].'</td>  
			<td>'.$row['name_status'].'</td>
			<td>'.$row['name'].'</td>   
 			<td>'.$row['date'].'</td> 
			  </tr>';
	 }
	 echo '</table>';
		
	}
}
catch (PDOException $e) {
    echo 'Database error: '.$e->getMessage();
}
