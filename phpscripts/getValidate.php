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
		$sql = "SELECT `profiles`.*, `contact_details`.*, `job_histories`.*, `profile_details`.*, `emergency_details`.*, `medical_details`.*, `education_details`.*
			FROM `profiles` 
			LEFT JOIN `contact_details` ON `contact_details`.`id_profile` = `profiles`.`idprofiles` 
			LEFT JOIN `profile_details` ON `profile_details`.`id_profile` = `profiles`.`idprofiles` 
			LEFT JOIN `emergency_details` ON `emergency_details`.`id_profile` = `profiles`.`idprofiles` 
			LEFT JOIN `medical_details` ON `medical_details`.`id_profile` = `profiles`.`idprofiles` 
			LEFT JOIN `education_details` ON `education_details`.`id_profile` = `profiles`.`idprofiles` 
			WHERE
			`profiles`.`idprofiles` = {$idprofile};";


		if($result = mysqli_query($con, $sql)){
			$i = 0;
			while($row = mysqli_fetch_assoc($result)){
				$profiles['idprofiles'] = $row['idprofiles'];
				$profiles['tittle'] = $row['tittle'];
				$profiles['first_name'] = $row['first_name'];
				$profiles['second_name'] = $row['second_name'];
				$profiles['first_lastname'] = $row['first_lastname'];
				$profiles['second_lastname'] = $row['second_lastname'];
				$profiles['day_of_birth'] = $row['day_of_birth'];
				$profiles['nationality'] = $row['nationality'];
				$profiles['gender'] = $row[gender''];
				$profiles['etnia'] = $row['etnia'];
				$profiles['bank'] = $row['bank'];
				$profiles['account'] = $row['account']; 
				$profiles['account_type'] = $row['type_account'];
				$profiles['marital_status'] = $row['marital_status'];
				$profiles['dpi'] = $row['dpi'];
				$profiles['nit'] = $row['nit'];
				$profiles['iggs'] = $row['iggs'];
				$profiles['irtra'] = $row['irtra'];
				$profiles['status'] = $row['status'];
				$profiles['idcontact_details'] = $row['idcontact_details'];
				$profiles['id_profile'] = $row['id_profile'];
				$profiles['primary_phone'] = $row['primary_phone'];
				$profiles['secondary_phone'] = $row['secondary_phone'];
				$profiles['address'] = $row['address'];
				$profiles['city'] = $row['city'];
				$profiles['email'] = $row['email'];
				$profiles['idprofile_details'] = $row['idprofile_details'];
				$profiles['english_level'] = $row['english_level'];
				$profiles['transport'] = $row['transport'];
				$profiles['start_date'] = $row['start_date'];
				$profiles['unavialable_days'] = $row['unavialable_days'];
				$profiles['marketing_campaing'] = $row['marketing_campaing'];
				$profiles['first_lenguage'] = $row['first_lenguage'];
				$profiles['second_lenguage'] = $row['second_lenguage'];
				$profiles['third_lenguage'] = $row['third_lenguage'];
				$profiles['idemergency_details'] = $row['idemergency_details'];
				$profiles['emergency_first_name'] = $row['emergency_first_name'];
				$profiles['emergency_second_name'] = $row['emergency_second_name'];
				$profiles['emergency_first_lastname'] = $row['emergency_first_lastname'];
				$profiles['emergency_second_lastname'] = $row['emergency_second_lastname'];
				$profiles['phone'] = $row['phone'];
				$profiles['relationship'] = $row['relationship'];
				$profiles['idmedical_details'] = $row['idmedical_details'];
				$profiles['medical_treatment'] = $row['medical_treatment'];
				$profiles['medical_prescription'] = $row['medical_prescription'];
				$profiles['ideducation_details'] = $row['ideducation_details'];
				$profiles['current_level'] = $row['current_level'];
				$profiles['further_education'] = $row['further_education'];
				$profiles['currently_studing'] = $row['currently_studing'];
				$profiles['institution_name'] = $row['institution_name'];
				$profiles['degree'] = $row['degree'];
				$i++;
				}

			echo json_encode($profiles);
		}else{
			http_response_code(400);
		}
?>

