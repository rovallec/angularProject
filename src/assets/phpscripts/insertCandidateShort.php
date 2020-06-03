<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

	require 'database.php';
	$postdata = file_get_contents("php://input");
	if(isset($postdata) && !empty($postdata))
	{
		$request = json_decode($postdata);

		$first_name = ($request->first_name);
		$middle_name = ($request->middle_name);
		$first_lastname = ($request->first_lastname);
		$second_lastname = ($request->second_lastname);
		$phone = ($request->phone_number);
		$dpi = ($request->dpi);
		$email = ($request->email_address);
		$status = 'pending';


		$sql = "INSERT INTO `profiles`(`first_name`, `second_name`, `first_lastname`, `second_lastname`, `dpi`, `status`) VALUE ('{$first_name}','{$middle_name}','{$first_lastname}','{$second_lastname}', '{$dpi}', '{$status}');";
		if(mysqli_query($con, $sql))
		{
			$id_prof = mysqli_insert_id($con);
			$sql2 = "INSERT INTO `contact_details`(`idcontact_details`, `id_profile`, `primary_phone`, `email`) VALUES (null, '$id_prof', '$phone','$email');";
			if(mysqli_query($con,$sql2)){
				echo $id_prof;
			}else{
				http_response_code(422);
			}
		}
	}
?>