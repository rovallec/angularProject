<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

	require 'database.php';

	$postdata = file_get_contents("php://input");
	if(isset($postdata) && !empty($postdata)){

		$request = json_decode($postdata);
		$idprofile = ($request->idprofiles);
	}

		$profiles = [];
		$sql = "SELECT * FROM `job_histories` WHERE `id_profile` = $idprofile";


		if($result = mysqli_query($con, $sql)){
			$i = 0;
			while($row = mysqli_fetch_assoc($result)){
				$profiles[$i]['idjob_histories'] = $row['idjob_histories'];
				$profiles[$i]['company'] = $row['idjob_histories'];
				$profiles[$i]['date_joining'] = $row['idjob_histories'];
				$profiles[$i]['date_end'] = $row['idjob_histories'];
				$profiles[$i]['position'] = $row['idjob_histories'];
				$profiles[$i]['reference_name'] = $row['idjob_histories'];
				$profiles[$i]['reference_lastname'] = $row['idjob_histories'];
				$profiles[$i]['reference_position'] = $row['idjob_histories'];
				$profiles[$i]['reference_email'] = $row['idjob_histories'];
				$profiles[$i]['reference_phone'] = $row['idjob_histories'];
				$profiles[$i]['working'] = $row['idjob_histories'];
				$i++;
				}

			echo json_encode($profiles);
		}else{
			http_response_code(400);
		}
?>

