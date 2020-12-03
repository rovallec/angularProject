<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

	require 'database.php';

		$profiles = [];
		$sql = "SELECT `profiles`.*, `contact_details`.*, `profile_details`.*, `emergency_details`.*, `medical_details`.*, `education_details`.* FROM `profiles` LEFT JOIN `contact_details` ON `contact_details`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `profile_details` ON `profile_details`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `emergency_details` ON `emergency_details`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `medical_details` ON `medical_details`.`id_profile` = `profiles`.`idprofiles` LEFT JOIN `education_details` ON `education_details`.`id_profile` = `profiles`.`idprofiles` WHERE `profiles`.`status` = 'VALIDATION';";


		if($result = mysqli_query($con, $sql)){
			$i = 0;
			while($row = mysqli_fetch_assoc($result)){
				$profiles[$i]['idprofiles'] = $row['idprofiles'];
				$profiles[$i]['tittle'] = $row['tittle'];
				$profiles[$i]['first_name'] = $row['first_name'];
				$profiles[$i]['second_name'] = $row['second_name'];
				$profiles[$i]['first_lastname'] = $row['first_lastname'];
				$profiles[$i]['second_lastname'] = $row['second_lastname'];
				$profiles[$i]['day_of_birth'] = $row['day_of_birth'];
				$profiles[$i]['nationality'] = $row['nationality'];
				$profiles[$i]['gender'] = $row['gender'];
				$profiles[$i]['etnia'] = $row['etnia'];
				$profiles[$i]['bank'] = $row['bank'];
				$profiles[$i]['account'] = $row['account']; 
				$profiles[$i]['account_type'] = $row['account_type'];
				$profiles[$i]['marital_status'] = $row['marital_status'];
				$profiles[$i]['dpi'] = $row['dpi'];
				$profiles[$i]['nit'] = $row['nit'];
				$profiles[$i]['iggs'] = $row['iggs'];
				$profiles[$i]['irtra'] = $row['irtra'];
				$profiles[$i]['status'] = $row['status'];
				$profiles[$i]['idcontact_details'] = $row['idcontact_details'];
				$profiles[$i]['id_profile'] = $row['id_profile'];
				$profiles[$i]['primary_phone'] = $row['primary_phone'];
				$profiles[$i]['secondary_phone'] = $row['secondary_phone'];
				$profiles[$i]['address'] = $row['address'];
				$profiles[$i]['city'] = $row['city'];
				$profiles[$i]['email'] = $row['email'];
				$profiles[$i]['idprofile_details'] = $row['idprofile_details'];
				$profiles[$i]['english_level'] = $row['english_level'];
				$profiles[$i]['transport'] = $row['transport'];
				$profiles[$i]['start_date'] = $row['start_date'];
				$profiles[$i]['unavialable_days'] = $row['unavialable_days'];
				$profiles[$i]['marketing_campaing'] = $row['marketing_campaing'];
				$profiles[$i]['first_lenguage'] = $row['first_lenguage'];
				$profiles[$i]['second_lenguage'] = $row['second_lenguage'];
				$profiles[$i]['third_lenguage'] = $row['third_lenguage'];
				$profiles[$i]['idemergency_details'] = $row['idemergency_details'];
				$profiles[$i]['emergency_first_name'] = $row['e_first_name'];
				$profiles[$i]['emergency_second_name'] = $row['e_second_name'];
				$profiles[$i]['emergency_first_lastname'] = $row['e_first_lastname'];
				$profiles[$i]['emergency_second_lastname'] = $row['e_second_lastname'];
				$profiles[$i]['phone'] = $row['phone'];
				$profiles[$i]['relationship'] = $row['relationship'];
				$profiles[$i]['idmedical_details'] = $row['idmedical_details'];
				$profiles[$i]['medical_treatment'] = $row['medical_treatment'];
				$profiles[$i]['medical_prescription'] = $row['medical_prescription'];
				$profiles[$i]['ideducation_details'] = $row['ideducation_details'];
				$profiles[$i]['current_level'] = $row['current_level'];
				$profiles[$i]['further_education'] = $row['further_education'];
				$profiles[$i]['currently_studing'] = $row['currently_studing'];
				$profiles[$i]['institution_name'] = $row['institution_name'];
				$profiles[$i]['degree'] = $row['degree'];
				$i++;
				}

			echo json_encode($profiles);
		}else{
			http_response_code(400);
		}
?>

