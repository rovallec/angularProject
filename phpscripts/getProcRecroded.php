<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);


$i = 0;
$process = [];


$sql = "SELECT * FROM `hr_processes` LEFT JOIN `process_types` ON `process_types`.`idprocess_types` = `hr_processes`.`id_type` LEFT JOIN `users` ON `users`.`idUser` = `hr_processes`.`id_user` WHERE `id_employee` = '$id' && `idprocess_types` > 7;";

if($result = mysqli_query($con,$sql)){
	while($row = mysqli_fetch_assoc($result)){
        $process[$i]['idprocesses'] = $row['idhr_processes'];
        $process[$i]['id_role'] = $row['id_role'];
        $process[$i]['id_profile'] = $row['id_employee'];
        $process[$i]['name'] = $row['name'];
        $process[$i]['descritpion'] = $row['notes'];
        $process[$i]['prc_date'] = $row['date'];
        $process[$i]['status'] = $row['status'];
        $process[$i]['id_user'] = $row['id_user'];
        $process[$i]['user_name'] = $row['user_name'];
        $process[$i]['notes'] = $row['notes'];
		$i++;
	};
	echo json_encode($process);
}else{
	http_response_code(404);
};
?>