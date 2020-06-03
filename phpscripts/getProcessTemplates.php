<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id_role = ($request->id_role);


$i = 0;
$process = [];


$sql = "SELECT * FROM `process_templates` WHERE `id_role` = '{$id_role}'";

if($result = mysqli_query($con,$sql)){
	while($row = mysqli_fetch_assoc($result)){
		$process[$i]['idprocess_templates'] = $row['idprocess_templates'];
		$process[$i]['name'] = $row['name'];
		$process[$i]['department'] = $row['department'];
		$i++;
	};
	echo json_encode($process);
}else{
	http_response_code(404);
};
?>