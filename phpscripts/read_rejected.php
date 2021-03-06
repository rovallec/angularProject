<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$profiles = [];
$sql = "SELECT *  FROM `profiles` where `status` = 'rejected' OR `status`= 'OTHER' OR `status` = 'POSITIVE DRUG TEST' OR `status` = 'LOW ENGLISH' OR `status` = 'LOW TYPING' OR `status` = 'LOW PSICOMETRIC' OR `status` = 'LOW LISTENING' OR `status` = 'POLICE RECORDS' OR `status` = 'CRIMINAL RECORDS' LIMIT 20";

if($result = mysqli_query($con, $sql))
{
	$i = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$profiles[$i]['idprofiles'] = $row['idprofiles'];
		$profiles[$i]['first_name'] = $row['first_name'];
		$profiles[$i]['second_name'] = $row['second_name'];
		$profiles[$i]['first_lastname'] = $row['second_name'];
		$profiles[$i]['second_lastname'] = $row['second_lastname'];
		$profiles[$i]['dpi'] = $row['dpi'];
		$profiles[$i]['status'] = $row['status'];
		$i++;
	}
	
	echo json_encode($profiles);
}else{
	http_response_code(404);
}
?>