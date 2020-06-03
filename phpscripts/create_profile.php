<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
	require 'database.php';
	$postdata = file_get_contents("php://input");
	if(isset($postdata) && !empty($postdata))
	{
		$request = json_decode($postdata);

		$idprofiles = ($request->idprofile);
		$tittle = ($request->tittle);
		$first_name = ($request->first_name);
		$second_name = ($request->second_name);
		$first_lastname = ($request->first_lastname);
		$second_lastname = ($request->second_lastname);
		$day_of_birthday = ($request->day_of_birthday);
		$nationality = ($request->nationality);
		$marital_status = ($request->marital_status);
		$dpi = ($request->dpi);
		$nit = ($request->nit);
		$igss = ($request->igss);
		$irtra = ($request->irtra);
		$status = ($request->status);


		$sql = "INSERT INTO `profiles`(`tittle`, `first_name`, `second_name`, `first_lastname`, `second_lastname`, `day_of_birth`, `nationality`, `marital_status`, `dpi`, `nit`, `iggs`, `irtra`, `status`) VALUE ('{$tittle}','{$first_name}','{$second_name}','{$first_lastname}','{$second_lastname}','{$day_of_birthday}' ,'{$nationality}','{$marital_status}','{$dpi}','{$nit}','{$igss}','{$irtra}','{$status}');";

		if(mysqli_query($con, $sql))
		{
			$id_prof = mysqli_insert_id($con);
			echo $id_prof;
		}else{
			http_response_code(422);
		}
	}
?>