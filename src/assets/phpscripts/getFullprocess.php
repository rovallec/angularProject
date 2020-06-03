<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);
$sel_proc = ($request->process_name);


$i = 0;

$process = [];

$sql = "SELECT `processes`.`idprocesses`, `processes`.`name` as `processName`, `processes`.`description`, `processes`.`prc_date`, `roles`.`name`as `roleName`, `users`.`user_name` FROM `processes` LEFT JOIN `roles` ON `processes`.`id_role` = `roles`.`idroles` LEFT JOIN `users` ON `processes`.`id_user` = `users`.`idUser` where `processes`.`idprocesses`= '{$id}';";

if($result = mysqli_query($con,$sql)){
	while($row = mysqli_fetch_assoc($result)){
		$process[$i]['idprocesses'] = $row['idprocesses'];
		$process[$i]['process_name'] = $row['processName'];
		$process[$i]['description'] = $row['description'];
		$process[$i]['prc_date'] = $row['prc_date'];
		$process[$i]['role_name'] = $row['roleName'];
		$process[$i]['user_name'] = $row['user_name'];
		$i++;
	};
	echo json_encode($process);
}else{
	http_response_code(404);
};

?>