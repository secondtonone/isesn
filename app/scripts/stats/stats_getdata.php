<?php
session_start();

require_once '../../models/stats_model.php';

try {
	switch ($_POST['q']) {
		case 'yearsellsobjects':
		{
			$stats = new stats_model();
			$stats->yearSellsObjects($_POST['year']);
			break;
		}
		case 'yearsellsobjectspie':
		{
			$stats = new stats_model();
			$stats->yearSellsObjectsPie($_POST['year']);
			break;
		}
		case 'yearsellsobjectsradar':
		{
			$stats = new stats_model();
			$stats->yearSellsObjectsRadar($_POST['year']);
			break;
		}
		case 'monthsellsobjectspie':
		{
			$stats = new stats_model();
			$stats->monthSellsObjectsPie($_POST['year'],$_POST['month']);
			break;
		}
		case 'yearpriceobjects':
		{
			$stats = new stats_model();
			$stats->yearPriceObjects($_POST['year']);
			break;
		}
		case 'yeardynamicdb':
		{
			$stats = new stats_model();
			$stats->yearDynamicDB($_POST['year']);
			break;
		}
		case 'systemstats':
		{
			$stats = new stats_model();
			$stats->systemStats();
			break;
		}
	}
}
catch (Exception $e) {
    echo json_encode(array('errMess'=>'Error: '.$e->getMessage()));
}