<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request);

$hrproc = [];
$sql = "SELECT * FROM hr_processes WHERE idhr_processes = $id ;";
if($result = mysqli_query($con, $sql))
{
  $row = mysqli_fetch_assoc($result);

  $hrproc['idhr_processes'] = $row['idhr_processes'];
  $hrproc['id_user'] = $row['id_user'];
  $hrproc['id_employee'] = $row['id_employee'];
  $hrproc['id_type'] = $row['id_type'];
  $hrproc['id_department'] = $row['id_department'];
  $hrproc['date'] = $row['date'];
  $hrproc['notes'] = $row['notes'];
  $hrproc['status'] = $row['status'];
	$i = 0;
	/*while($row = mysqli_fetch_assoc($result))
	{
		$hrproc[$i]['idhr_processes'] = $row['idhr_processes'];
		$hrproc[$i]['id_user'] = $row['id_user'];
		$hrproc[$i]['id_employee'] = $row['id_employee'];
		$hrproc[$i]['id_type'] = $row['id_type'];
		$hrproc[$i]['id_department'] = $row['id_department'];
		$hrproc[$i]['date'] = $row['date'];
		$hrproc[$i]['notes'] = $row['notes'];
		$hrproc[$i]['status'] = $row['status'];
		$i++;
	}*/
	echo json_encode($hrproc);
}else{
  echo($sql);
	http_response_code(404);
}
?>
