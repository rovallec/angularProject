<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idwaves);

$hires = [];
$sql = "SELECT * FROM (SELECT `hires`.*, `profiles`.`first_name`, `profiles`.`second_name`, `profiles`.`first_lastname`, `profiles`.`second_lastname`, `profiles`.`status` FROM `hires` LEFT JOIN `profiles` ON `hires`.`id_profile` = `profiles`.`idprofiles`) AS `hires_full` WHERE `hires_full`.`id_wave` = '$id'";

if($result = mysqli_query($con, $sql))
{
	$i = 0;
	while($row = mysqli_fetch_assoc($result))
	{
		$hires[$i]['idhires'] = $row['idhires'];
		$hires[$i]['id_profile'] = $row['id_profile'];
		$hires[$i]['id_wave'] = $row['id_wave'];
		$hires[$i]['nearsol_id'] = $row['nearsol_id'];
		$hires[$i]['schedule'] = $row['schedule'];
		$hires[$i]['first_name'] = $row['first_name'];
		$hires[$i]['second_name'] = $row['second_name'];
		$hires[$i]['first_lastname'] = $row['first_lastname'];
		$hires[$i]['second_lastname'] = $row['second_lastname'];
		$hires[$i]['status'] = $row['status'];
		$i++;
	}
	
	echo json_encode($hires);
}else{
	http_response_code(404);
}
?>