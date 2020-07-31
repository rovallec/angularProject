<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Origin, Content-Type, Accept, Authorization, X-Request-With');

require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$date = ($request->str);

$waves = [];

if(strpos($date, ">=") !== false){
	$sql = "SELECT * FROM (SELECT `waves`.*, `accounts`.`name` AS `account_name` FROM `waves` LEFT JOIN `accounts` ON `waves`.`id_account` = `accounts`.`idaccounts`) AS `waves_view` WHERE `end_date`  $date";
}else{
	$sql = "SELECT * FROM (SELECT `waves`.*, `accounts`.`name` AS `account_name` FROM `waves` LEFT JOIN `accounts` ON `waves`.`id_account` = `accounts`.`idaccounts`) AS `waves_view` WHERE `end_date` >= $date";
}


if($res = mysqli_query($con, $sql)){
	$i = 0;
	while($row = mysqli_fetch_assoc($res))
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
		$waves[$i]['show'] = "0";
		$waves[$i]['bse_payment'] = $row['base_payment'];
		$waves[$i]['productivity_payment'] = $row['productivity_payment'];
		$waves[$i]['job'] = $row['job'];
		$i++;
	}
	
	echo json_encode($waves);
};
?>