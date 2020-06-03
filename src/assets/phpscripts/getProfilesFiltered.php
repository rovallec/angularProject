<?php


if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
	header('Access-Control-Allow-Headers: token, Content-Type');
	header('Access-Control-Max-Age: 1728000');
	header('Content-Length: 0');
	header('Content-Type: text/plain');
	die();
}

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$filter = ($request->filter);
$text = ($request->text);

$profiles = [];
if($filter != "primary_phone" && $filter != "email"){
	$sql = "SELECT *  FROM `profiles` where `$filter` LIKE '%$text%';";
}else{
	$sql1 = "SELECT * FROM `contact_details` WHERE `$filter` LIKE '%$text%'";
	if($rs = mysqli_query($con,$sql1)){
		$sql2 = "";
		while ($rs_rw = mysqli_fetch_assoc($rs)) {
			if($sql2 == ""){
				$sql2 = $rs_rw['id_profile'];
			}else{
				$sql2 = $sql2 . " OR " . $rs_rw['id_profile'];
			}
		}
		$sql = "SELECT * FROM `profiles` WHERE `idprofiles` = $sql2 ;";
	}
}

if($result = mysqli_query($con, $sql))
{
	$i = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$profiles[$i]['idprofiles'] = $row['idprofiles'];
		$profiles[$i]['first_name'] = $row['first_name'];
		$profiles[$i]['second_name'] = $row['second_name'];
		$profiles[$i]['first_lastname'] = $row['first_lastname'];
		$profiles[$i]['second_lastname'] = $row['second_lastname'];
		$profiles[$i]['dpi'] = $row['dpi'];
		$profiles[$i]['status'] = $row['status'];
		$i++;
	}
	
	echo json_encode($profiles);
}else{
	http_response_code(200);
}
?>