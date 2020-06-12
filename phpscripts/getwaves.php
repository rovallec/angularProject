<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$waves = [];
$sql = "SELECT * FROM (SELECT `waves`.*, `accounts`.`name` AS `account_name` FROM `waves` LEFT JOIN `accounts` ON `waves`.`id_account` = `accounts`.`idaccounts`) AS `waves_view` WHERE `state` = 1";

if($result = mysqli_query($con, $sql))
{
	$i = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$waves[$i]['idwaves'] = $row['idwaves'];
		$waves[$i]['id_account'] = $row['id_account'];
		$waves[$i]['starting_date'] = $row['starting_date'];
		$waves[$i]['end_date'] = $row['end_date'];
		$waves[$i]['max_recriut'] = $row['max_recriut'];
		$waves[$i]['hires'] = $row['hires'];
		$waves[$i]['name'] = $row['name'];
		$waves[$i]['trainning_schedule'] = $row['trainning_schedule'];
		$waves[$i]['prefix'] = $row['prefix'];
		$waves[$i]['state'] = $row['state'];
		$waves[$i]['account_name'] = $row['account_name'];
		$waves[$i]['ops_start'] = $row['ops_start'];
		$waves[$i]['job'] = $row['job'];
		$waves[$i]['payment'] = $row['payment'];
		$waves[$i]['show'] = "0";
		$i++;
	}
	
	echo json_encode($waves);
}else{
	http_response_code(404);
}
?>