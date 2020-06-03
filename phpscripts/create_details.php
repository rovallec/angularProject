<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
	require 'database.php';
	$postdata = file_get_contents("php://input");
	if(isset($postdata) && !empty($postdata))
	{
		$request = json_decode($postdata);

		$idprofile_details = ($request->idprofile_details);
		$id_profile = ($request->id_profile);
		$english_level = ($request->english_level);
		$transport = ($request->transport);
		$start_date = ($request->start_date);
		$unavialable_days = ($request->unavialable_days);
		$marketing_campaing = ($request->marketing_campaing);
		$first_lenguage = ($request->first_lenguage);
		$second_lenguage = ($request->second_lenguage);
		$third_lenguage = ($request->third_lenguage);

		$sql = "INSERT INTO `profile_details`(`idprofile_details`, `id_profile`, `english_level`, `transport`, `start_date`, `unavialable_days`, `marketing_campaing`, `first_lenguage`, `second_lenguage`, `third_lenguage`) VALUES ('{$id_profile}', '{$english_level}', '{$transport}', '{$start_date}', '{$unavialable_days}', '{$marketing_campaing}', '{$first_lenguage}', '{$second_lenguage}', '{$third_lenguage}');";

echo $sql;

		if(mysqli_query($con, $sql))
		{
			$id_contact = mysqli_insert_id($con);
			echo $id_profile_details;
		}else{
			http_response_code(422);
		}
	}
?>