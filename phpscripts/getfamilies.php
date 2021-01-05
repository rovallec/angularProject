<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

	require 'database.php';

	$postdata = file_get_contents("php://input");
	if(isset($postdata) && !empty($postdata)){

		$request = json_decode($postdata);
		$idprofile = ($request->idprofiles);
	}

	$families = [];
	$sql = "SELECT * FROM `families` WHERE `id_profile` = {$idprofile};";

	if($result = mysqli_query($con, $sql)){
		$i = 0;
		while($row = mysqli_fetch_assoc($result)){
			$families[$i]['affinity_idfamilies'] = $row['idfamilies'];
			$families[$i]['affinity_id_profile'] = $row['id_profile'];
			$families[$i]['affinity_first_name'] = $row['first_name'];				
			$families[$i]['affinity_second_name'] = $row['second_name'];
			$families[$i]['affinity_first_last_name'] = $row['first_last_name'];
			$families[$i]['affinity_second_last_name'] = $row['second_last_name'];        
			$families[$i]['affinity_phone'] = $row['phone'];
			$families[$i]['affinity_relationship'] = $row['relationship'];
			$families[$i]['affinity_birthdate'] = $row['birthdate'];
			$i++;
		}      
		echo json_encode($families);
	} else {      
		http_response_code(400);
	}
?>

