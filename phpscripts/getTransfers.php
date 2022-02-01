<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id_employee = ($request->id_employee);
$start = ($request->start);
$end = ($request->end);
$hrproc = [];

if($id_employee == "exp"){
	$ns = $start.explode('|')[0];
	$start = $start.explode('|')[1];
	$sql = "SELECT * FROM hr_processes WHERE notes like '%$ns%' AND id_type = 16 AND date BETWEEN '$start' AND '$end';";
}else{
	$sql = "SELECT * FROM hr_processes WHERE id_employee = $id_employee AND id_type = 16 AND date BETWEEN '$start' AND '$end';";
}

if($result = mysqli_query($con, $sql))
	{
	while($row = mysqli_fetch_assoc($result)){
		$hrproc['idhr_processes'] = $row['idhr_processes'];
		$hrproc['id_user'] = $row['id_user'];
		$hrproc['id_employee'] = $row['id_employee'];
		$hrproc['id_type'] = $row['id_type'];
		$hrproc['id_department'] = $row['id_department'];
		$hrproc['date'] = $row['date'];
		$hrproc['notes'] = $row['notes'];
		$hrproc['status'] = $row['status'];
	}
	echo json_encode($hrproc);
	}else{
  		echo($sql);
		http_response_code(404);
	}
?>
