<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->id);


$i = 0;
$process = [];


$sql = "SELECT * FROM `hr_processes` LEFT JOIN `process_types` ON `process_types`.`idprocess_types` = `hr_processes`.`id_type` LEFT JOIN `users` ON `users`.`idUser` = `hr_processes`.`id_user` WHERE `id_employee` = '$id';";

if($result = mysqli_query($con,$sql)){
	while($row = mysqli_fetch_assoc($result)){
        $process[$i]['idprocesses'] = $result['idhr_processes'];
        $process[$i]['id_role'] = $result['id_role'];
        $process[$i]['id_profile'] = $result['id_employee'];
        $process[$i]['name'] = $result['name'];
        $process[$i]['descritpion'] = $result['description'];
        $process[$i]['prc_date'] = $result['date'];
        $process[$i]['status'] = $result['status'];
        $process[$i]['id_user'] = $result['id_user'];
        $process[$i]['user_name'] = $result['user_name'];
		$i++;
	};
	echo json_encode($process);
}else{
	http_response_code(404);
};
?>