<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/core/model.php';

class journal_model extends \App\Core\Model
{
	public function admin_getdata ($page,$rows,$sidx,$sord,$search,$filters,$method)
	{
		$method='admin_'.$method;
		
		$this->$method($page,$rows,$sidx,$sord,$search,$filters);
	}
	
	private function admin_notifications ($page,$rows,$sidx,$sord,$search,$filters)
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
			$allowedFields = array('id_notification', 'text_notification', 'id_status');
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
	private function admin_events ($page,$rows,$sidx,$sord,$search,$filters)
	{
		$dbh=$this->connect();
		
		$curPage = $page;
		$rowsPerPage = $rows;
		$sortingField = $sidx;
		$sortingOrder = $sord;
		
		$qWhere = '';
			
		if (isset($search) && $search == 'true') {
			$allowedFields = array('id_event','id_user', 'name', 'id_type_event', 'time_event');
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
						
					$field='j.'.$rule->field;
						
					if ($rule->field=='name')
					{
						 $field='u.name';
					}
					if ($rule->field=='id_user')
					{
						 $field='u.id_user';
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
		$rows = $dbh->prepare('SELECT COUNT(*) AS count FROM `users_journal` '.$qWhere.'');
		$rows->execute(array());
			
		$totalRows = $rows->fetch(PDO::FETCH_ASSOC);
					
		$firstRowIndex = $curPage * $rowsPerPage - $rowsPerPage;
			//получаем список из базы
		$res = $dbh->prepare('SELECT j.`id_event`,u.`id_user`, u.`name`, j.`id_type_event`, j.`time_event` FROM `users_journal` j LEFT JOIN `users` u ON j.`id_user`= u.`id_user` '.$qWhere.' ORDER BY '.$sortingField.' '.$sortingOrder.' LIMIT '.$firstRowIndex.', '.$rowsPerPage);
		$res->execute(array());
			
		//сохраняем номер текущей страницы, общее количество страниц и общее количество записей
		$response = new stdClass();
		$response->page = $curPage;
		$response->total = ceil($totalRows['count'] / $rowsPerPage);
		$response->records = $totalRows['count'];
			
		$i=0;
		
		while($row = $res->fetch(PDO::FETCH_ASSOC)) {
					
			$response->rows[$i]['id']=$row['id_event'];
			$response->rows[$i]['cell']=array($row['id_event'],$row['id_user'],$row['name'],$row['id_type_event'],$row['time_event']);
						
			$i++;
		}
		echo json_encode($response);
	}
	public function users_online ()
	{
		$dbh=$this->connect();
		
		$html_out='';
			
		$res = $dbh->prepare('SELECT `id_user`,`login`,`name`,`online` FROM `users` WHERE `active`=? ORDER BY `online` DESC, `login` asc');
		$res->execute(array(1));
				
		while($row = $res->fetch(PDO::FETCH_ASSOC))
		{
			$html_temp = '<div id="'.$row['id_user'].'" class="user '.$row['online'].'">
							<div class="marker '.$row['online'].'"></div>
							<div class="wrap">
								<div class="login">'.$row['login'].'</div>
								<div class="name">'.$row['name'].'</div>
							</div>
						</div>';
						
			$html_out .=$html_temp;
		}
				
		echo $html_out;
	}
}