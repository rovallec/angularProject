<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id_employee = $request->id_employee;

$result = [];
$sql = "SELECT first_name, second_name, first_lastname, second_lastname, nearsol_id, dpi, accounts.name AS `account`,
		assets.code, asset_type.name AS `type`, asset_manufactures.brand, asset_manufactures.model,
		assets.serial , movement_types.name AS `movement`, assets.status AS `asset_status`, asset_movements.status AS `movement_status`
		FROM asset_movements
			INNER JOIN employees ON asset_movements.id_employee = employees.idemployees
			INNER JOIN assets ON assets.idassets = asset_movements.id_asset
			INNER JOIN users ON users.idUser = asset_movements.id_user
			INNER JOIN asset_type ON asset_type.idasset_type = assets.id_type
			INNER JOIN asset_manufactures ON asset_manufactures.idasset_manufactures = assets.id_manufacture
			INNER JOIN hires ON hires.idhires = employees.id_hire
			INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
			INNER JOIN accounts ON accounts.idaccounts = employees.id_account
			INNER JOIN movement_types ON movement_types.idmovement_types = asset_movements.id_type
		WHERE idemployees = $id_employee;";
		
if($result2 = mysqli_query($con, $sql))
{
	$i = 0;
	while($row = mysqli_fetch_assoc($result2))
	{
		$result[$i]['first_name'] = $row['first_name'];
		$result[$i]['second_name'] = $row['second_name'];
		$result[$i]['first_lastname'] = $row['first_lastname'];
		$result[$i]['second_lastname'] = $row['second_lastname'];
		$result[$i]['nearsol_id'] = $row['nearsol_id'];
		$result[$i]['dpi'] = $row['dpi'];
		$result[$i]['account'] = $row['account']
		$result[$i]['code'] = $row['code'];
		$result[$i]['type'] = $row['type'];
		$result[$i]['brand'] = $row['brand']
		$result[$i]['model'] = $row['model']
		$result[$i]['serial'] = $row['serial']
		$result[$i]['movement'] = $row['movement'];
		$result[$i]['asset_status'] = $row['asset_status'];
		$result[$i]['movement_status'] = $row['movement_status'];
		$i++;
	}
	
	echo json_encode($result);
}else{
	http_response_code(404);
}
?>