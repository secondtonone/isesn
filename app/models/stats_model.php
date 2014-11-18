<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/app/core/model.php';

class stats_model extends \App\Core\Model
{
	public function yearSellsObjects($current_year)
	{
		$response=array();
		$rowJSON=array();
		$connection=$this->connect();

		for ($i=1;$i<15;$i++)
		{
			$j = 0;

			if ($i==12 or $i==13) {
				continue;
			}

			$row=$connection->prepare('SELECT (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=1 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS jan,(SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=2 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS feb, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=3 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS mar, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=4 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS apr, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=5 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS may, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=6 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS jun, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=7 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS jul, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=8 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS aug, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=9 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS sep,(SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=10 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS oct, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=11 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS nov, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=12 AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS decem FROM objects LIMIT 1');
			$row->execute(array(':id_category'=>$i,':year'=>$current_year));
			$row = $row->fetch(PDO::FETCH_ASSOC);

			foreach ($row as $key=>$value)
			{
				$rowJSON[$j]=$value;
				$j++;
			}


			$response[]=$rowJSON;
		}

		echo json_encode($response);
	}

	public function yearSellsObjectsPie($current_year)
	{
		$response=array();
		$connection=$this->connect();

		for ($i=1;$i<15;$i++)
		{
			if ($i==12 or $i==13) {
				continue;
			}

			$row=$connection->prepare('SELECT (SELECT COUNT(id_object) FROM  objects WHERE id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS jan FROM objects LIMIT 1');
			$row->execute(array(':id_category'=>$i,':year'=>$current_year));

			$value=$row->fetch(PDO::FETCH_ASSOC);

			$response[]=$value['jan'];
		}

		echo json_encode($response);
	}
	public function yearSellsObjectsRadar($current_year)
	{
		$response=array();
		$rowJSON=array();
		$connection=$this->connect();

		$row=$connection->prepare('SELECT (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=1 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS jan,(SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=2 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS feb, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=3 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS mar, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=4 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS apr, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=5 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS may, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=6 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS jun, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=7 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS jul, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=8 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS aug, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=9 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS sep,(SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=10 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS oct, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=11 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS nov, (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=12 AND id_sell_out_status<>3 AND YEAR(date)=:year ) AS decem FROM objects LIMIT 1');
		$row->execute(array(':year'=>$current_year));
		$row = $row->fetch(PDO::FETCH_ASSOC);

		$j=0;

		foreach ($row as $key=>$value)
		{
			$rowJSON[$j]=$value;
			$j++;
		}
		$response[]=$rowJSON;

		$row=$connection->prepare('SELECT (SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=1 AND id_status=1 AND YEAR(date)=:year ) AS jan,(SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=2 AND id_status=1 AND YEAR(date)=:year ) AS feb, (SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=3 AND id_status=1 AND YEAR(date)=:year ) AS mar, (SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=4 AND id_status=1 AND YEAR(date)=:year ) AS apr, (SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=5 AND id_status=1 AND YEAR(date)=:year ) AS may, (SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=6 AND id_status=1 AND YEAR(date)=:year ) AS jun, (SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=7 AND id_status=1 AND YEAR(date)=:year ) AS jul, (SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=8 AND id_status=1 AND YEAR(date)=:year ) AS aug, (SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=9 AND id_status=1 AND YEAR(date)=:year ) AS sep,(SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=10 AND id_status=1 AND YEAR(date)=:year ) AS oct, (SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=11 AND id_status=1 AND YEAR(date)=:year ) AS nov, (SELECT COUNT(id_client) FROM  clients WHERE MONTH(date)=12 AND id_status=1 AND YEAR(date)=:year ) AS decem FROM clients LIMIT 1');

		$row->execute(array(':year'=>$current_year));
		$row = $row->fetch(PDO::FETCH_ASSOC);

		$j=0;

		foreach ($row as $key=>$value)
		{
			$rowJSON[$j]=$value;
			$j++;
		}
		$response[]=$rowJSON;


		echo json_encode($response);
	}
	public function monthSellsObjectsPie($current_year,$current_month)
	{
		$response=array();
		$connection=$this->connect();


		for ($i=1;$i<15;$i++)
		{
			if ($i==12 or $i==13) {
				continue;
			}

			$row=$connection->prepare('SELECT (SELECT COUNT(id_object) FROM  objects WHERE MONTH(date)=:month AND id_sell_out_status=2 AND YEAR(date)=:year AND id_category=:id_category) AS jan FROM objects LIMIT 1');
			$row->execute(array(':month'=>$current_month,':year'=>$current_year,':id_category'=>$i));
			$value = $row->fetch(PDO::FETCH_ASSOC);


			$response[]=$value['jan'];
		}

		echo json_encode($response);
	}
	public function yearPriceObjects($current_year)
	{
		$response=array();
		$rowJSON=array();
		$connection=$this->connect();

		for ($i=1;$i<15;$i++)
		{
			$j=0;

			if ($i==12 or $i==13) {
				continue;
			}

			$row=$connection->prepare('SELECT (SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=1 AND YEAR(date)=:year AND id_category=:id_category) AS jan,(SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=2 AND YEAR(date)=:year AND id_category=:id_category) AS feb, (SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=3 AND YEAR(date)=:year AND id_category=:id_category) AS mar, (SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=4 AND YEAR(date)=:year AND id_category=:id_category) AS apr, (SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=5 AND YEAR(date)=:year AND id_category=:id_category) AS may, (SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=6 AND YEAR(date)=:year AND id_category=:id_category) AS jun, (SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=7 AND YEAR(date)=:year AND id_category=:id_category) AS jul, (SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=8 AND YEAR(date)=:year AND id_category=:id_category) AS aug, (SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=9 AND YEAR(date)=:year AND id_category=:id_category) AS sep,(SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=10 AND YEAR(date)=:year AND id_category=:id_category) AS oct, (SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=11 AND YEAR(date)=:year AND id_category=:id_category) AS nov, (SELECT ROUND(AVG(price)) FROM  objects WHERE MONTH(date)=12 AND YEAR(date)=:year AND id_category=:id_category) AS decem FROM objects LIMIT 1');
			$row->execute(array(':year'=>$current_year,':id_category'=>$i));
			$row = $row->fetch(PDO::FETCH_ASSOC);

			foreach ($row as $value)
			{
				$rowJSON[$j]=$value;
				++$j;
			}


			$response[]=$rowJSON;
		}

		echo json_encode($response);
	}
	public function yearDynamicDB($current_year)
	{
		$response=array();
		$rowJSON=array();
		$connection=$this->connect();
		$j=0;

		$row=$connection->prepare('SELECT ((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=1 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=1 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=2 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=2 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=3 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=3 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=4 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=4 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=5 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=5 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=6 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=6 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=7 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=7 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=8 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=8 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=9 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=9 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=10 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=10 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=11 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=11 AND YEAR(date)=:year)),((SELECT COUNT(id_object) FROM objects WHERE MONTH(date)=12 AND YEAR(date)=:year)+(SELECT COUNT(id_client) FROM clients WHERE MONTH(date)=12 AND YEAR(date)=:year)) FROM objects LIMIT 1');
		$row->execute(array(':year'=>$current_year));
		$row = $row->fetch(PDO::FETCH_ASSOC);

		$response[]=$row;


		echo json_encode($response);
	}
	/*public function systemStats()
	{
		$response=new stdClass();
		$rowJSON=array();
		$connection=$this->connect();


		$sql = 'SELECT (SELECT COUNT(id_object) FROM  objects) as count_all,(SELECT COUNT(id_object) FROM  objects WHERE id_sell_out_status=1) as selling,(SELECT COUNT(id_object) FROM  objects WHERE id_sell_out_status=2) as sells_out,(SELECT COUNT(id_object) FROM  objects WHERE id_sell_out_status=3 AND id_sell_out_status=4) as hide_out,(SELECT COUNT(id_object) FROM  objects o JOIN users u ON o.id_user=u.id_user WHERE id_sell_out_status=1 AND u.active=2) as unattached FROM  objects LIMIT 1';
		$row=$connection->query($sql);
		$row = $row->fetch(PDO::FETCH_ASSOC);
		$response->objects=$row;


		$sql = 'SELECT (SELECT COUNT(id_client) FROM  clients) as count_all,(SELECT COUNT(id_client) FROM  clients WHERE id_status=1) as active,(SELECT COUNT(id_client) FROM  clients WHERE id_status=2) as disactive,(SELECT COUNT(id_client) FROM  clients c JOIN users u ON c.id_user=u.id_user WHERE c.id_status=1 AND u.active=2) as unattached FROM  clients LIMIT 1';
		$row=$connection->query($sql);
		$row = $row->fetch(PDO::FETCH_ASSOC);
		$response->clients=$row;


		$sql = 'SELECT (SELECT COUNT(id_user) FROM  users) as count_all,(SELECT COUNT(id_user) FROM  users WHERE active=1) as active,(SELECT COUNT(id_user) FROM  users WHERE active=2) as disactive FROM  users LIMIT 1';
		$row=$connection->query($sql);
		$row = $row->fetch(PDO::FETCH_ASSOC);
		$response->users=$row;


		$sql = 'SELECT (SELECT COUNT(id_user) FROM  users) as count_all,(SELECT COUNT(id_user) FROM  users WHERE active=1) as active,(SELECT COUNT(id_user) FROM  users WHERE active=2) as disactive FROM  users LIMIT 1';
		$row=$connection->query($sql);
		$row = $row->fetch(PDO::FETCH_ASSOC);
		$response->users=$row;


		$sql = 'SELECT (SELECT COUNT(id_event)/DAYOFMONTH(NOW()) FROM  users_journal WHERE MONTH(time_event)=MONTH(NOW()) AND (id_type_event=6 OR id_type_event=5)) as count_all,(SELECT COUNT(id_event)/DAYOFMONTH(NOW()) FROM  users_journal WHERE id_type_event=5 AND MONTH(time_event)=MONTH(NOW())) as objects,(SELECT COUNT(id_event)/DAYOFMONTH(NOW()) FROM  users_journal WHERE id_type_event=6 AND MONTH(time_event)=MONTH(NOW())) as clients FROM users_journal LIMIT 1';
		$row=$connection->query($sql);
		$row = $row->fetch(PDO::FETCH_ASSOC);
		$response->records=$row;


		$sql = 'SELECT (SELECT COUNT(id_event)/7 FROM  users_journal WHERE DAYOFYEAR(time_event)>DAYOFYEAR(NOW())-7 AND id_type_event=3) as week,(SELECT COUNT(id_event)/30 FROM  users_journal WHERE id_type_event=3 AND DAYOFYEAR(time_event)>DAYOFYEAR(NOW())-30) as month,(SELECT COUNT(id_event)/60 FROM  users_journal WHERE id_type_event=3 AND DAYOFYEAR(time_event)>DAYOFYEAR(NOW())-60) as monthplus FROM users_journal LIMIT 1';
		$row=$connection->query($sql);
		$row = $row->fetch(PDO::FETCH_ASSOC);
		$response->sellouts=$row;


		$sql = 'SELECT (SELECT COUNT(id_event) FROM  users_journal WHERE DAYOFYEAR(time_event)=DAYOFYEAR(NOW()) AND (id_type_event=2 OR id_type_event=4)) as today,(SELECT COUNT(id_event) FROM  users_journal WHERE (id_type_event=2 OR id_type_event=4) AND DAYOFYEAR(time_event)>DAYOFYEAR(NOW())-7) as week,(SELECT COUNT(id_event) FROM  users_journal WHERE (id_type_event=2 OR id_type_event=4) AND DAYOFYEAR(time_event)>DAYOFYEAR(NOW())-30) as month FROM users_journal LIMIT 1';
		$row=$connection->query($sql);
		$row = $row->fetch(PDO::FETCH_ASSOC);
		$response->visits=$row;

		echo json_encode($response);
	}*/
}