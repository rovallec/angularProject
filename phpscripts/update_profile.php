<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
	require 'database.php';
	$postdata = file_get_contents("php://input");
	if(isset($postdata) && !empty($postdata))
	{
        $request = json_decode($postdata);
        
        $idprofiles = ($request->idprofiles);
		$tittle = ($request->tittle);
		$first_name = ($request->first_name);
		$second_name = ($request->second_name);
		$first_lastname = ($request->first_lastname);
		$second_lastname = ($request->second_lastname);
		$day_of_birthday = ($request->day_of_birth);
		$nationality = ($request->nationality);
		$marital_status = ($request->marital_status);
		$dpi = ($request->dpi);
		$nit = ($request->nit);
		$iggs = ($request->iggs);
		$irtra = ($request->irtra);
		$status = ($request->status);

		$profiles = [];

		$sql = "UPDATE `profiles` set `tittle` = '{$tittle}', `first_name` = '{$first_name}', `second_name` = '{$second_name}', `first_lastname` = '{$first_lastname}', `second_lastname` = '{$second_lastname}', `day_of_birth` = '{$day_of_birthday}', `nationality` = '{$nationality}', `marital_status` = '{$marital_status}', `dpi` = '{$dpi}', `nit` = '{$nit}', `iggs` = '{$iggs}', `irtra` = '{$irtra}', `status` = '{$status}' WHERE `idprofiles` = '$idprofiles';";

		if(mysqli_query($con,$sql)){
			$id_profile = mysqli_insert_id($con);

			$idcontact_details = ($request->idcontact_details);
			$primary_phone = ($request->primary_phone);
			$secondary_phone = ($request->secondary_phone);
			$address = ($request->address);
			$city = ($request->city);
			$email = ($request->email);

			$sql_1 = "SELECT * FROM `contact_details` WHERE id_profile = $id_profile LIMIT 1";

			if($result = mysqli_query($con, $sql_1)){
				if(mysqli_num_rows($result) > 0){
					$sql2 = "UPDATE `contact_details` set `primary_phone` = '{$primary_phone}', `secondary_phone` = '{$secondary_phone}', `address` = '{$address}', `city` = '{$city}', `email` = '{$email}' WHERE `id_profile` = '{$idprofiles}';";
				}else{
					$sql2 = "INSERT INTO `contact_details` (`idcontact_details`, `id_profile`, `primary_phone`, `secondary_phone`, `address`, `city`, `email`) VALUES (NULL, $id_profile, '$primary_phone', '$secondary_phone', '$address', '$city', '$email');";
				}
			}
			
			if(mysqli_query($con, $sql2)){
				$idprofile_details = ($request->idprofile_details);
				$english_level = ($request->english_level);
				$transport = ($request->transport);
				$start_date = ($request->start_date);
				$unavialable_days = ($request->unavialable_days);
				$marketing_campaing = ($request->marketing_campaing);
				$first_lenguage = ($request->first_lenguage);
				$second_lenguage = ($request->second_lenguage);
				$third_lenguage = ($request->third_lenguage);

				$sql3 = "UPDATE `profile_details` SET `english_level` = '{$english_level}', `transport` = '{$transport}', `start_date` = '{$start_date}', `unavialable_days` = '{$unavialable_days}', `marketing_campaing` = '{$marketing_campaing}', `first_lenguage` = '{$first_lenguage}', `second_lenguage` = '{$second_lenguage}', `third_lenguage` = '{$third_lenguage}' WHERE `id_profile` = '{$idprofiles}';";

				if(mysqli_query($con,$sql3)){

					$first_name = ($request->emergency_first_name);
					$second_name = ($request->emergency_second_name);
					$first_lastname = ($request->emergency_first_lastname);
					$second_lastname = ($request->emergency_second_lastname);
					$phone = ($request->phone);
					$relationship = ($request->relationship);

					$sql4 = "UPDATE `emergency_details` SET `e_first_name` = '{$first_name}', `e_second_name` = '{$second_name}', `e_first_lastname` = '{$first_lastname}', `e_second_lastname` = '{$second_lastname}', `phone` = '{$phone}', `relationship` = '{$relationship}' WHERE `id_profile` = '{$idprofiles}';";

					if(mysqli_query($con,$sql4)){
						$medical_treatment = ($request->medical_treatment);
						$medical_prescription = ($request->medical_prescription);

						$sql5 = "UPDATE `medical_details` SET `medical_treatment` = '{$medical_treatment}', `medical_prescription` = '{$medical_prescription}' WHERE `id_profile` = '{$idprofiles}';" ;

						if(mysqli_query($con,$sql5)){
							$sql6 = "SELECT `profiles`.*, `contact_details`.*, `job_histories`.*, `profile_details`.*, `emergency_details`.*, `medical_details`.*, `education_details`.*
							FROM `profiles` 
							LEFT JOIN `contact_details` ON `contact_details`.`id_profile` = `profiles`.`idprofiles` 
							LEFT JOIN `job_histories` ON `job_histories`.`id_profile` = `profiles`.`idprofiles` 
							LEFT JOIN `profile_details` ON `profile_details`.`id_profile` = `profiles`.`idprofiles` 
							LEFT JOIN `emergency_details` ON `emergency_details`.`id_profile` = `profiles`.`idprofiles` 
							LEFT JOIN `medical_details` ON `medical_details`.`id_profile` = `profiles`.`idprofiles` 
							LEFT JOIN `education_details` ON `education_details`.`id_profile` = `profiles`.`idprofiles` 
							WHERE
							`profiles`.`idprofiles` = {$id_profile};";
							
							if($result2 = mysqli_query($con, $sql6)){
								while($row = mysqli_fetch_assoc($result2)){
									$profiles['idprofiles'] = $row['idprofiles'];
									$profiles['tittle'] = $row['tittle'];
									$profiles['first_name'] = $row['first_name'];
									$profiles['second_name'] = $row['second_name'];
									$profiles['first_lastname'] = $row['first_lastname'];
									$profiles['second_lastname'] = $row['second_lastname'];
									$profiles['day_of_birth'] = $row['day_of_birth'];
									$profiles['nationality'] = $row['nationality'];
									$profiles['marital_status'] = $row['marital_status'];
									$profiles['dpi'] = $row['dpi'];
									$profiles['nit'] = $row['nit'];
									$profiles['iggs'] = $row['iggs'];
									$profiles['irtra'] = $row['irtra'];
									$profiles['status'] = $row['status'];
									$profiles['idcontact_details'] = $row['idcontact_details'];
									$profiles['primary_phone'] = $row['primary_phone'];
									$profiles['secondary_phone'] = $row['secondary_phone'];
									$profiles['address'] = $row['address'];
									$profiles['city'] = $row['city'];
									$profiles['email'] = $row['email'];
									$profiles['idjob_histories'] = $row['idjob_histories'];
									$profiles['company'] = $row['company'];
									$profiles['date_joining'] = $row['date_joining'];
									$profiles['date_end'] = $row['date_end'];
									$profiles['position'] = $row['position'];
									$profiles['reference_name'] = $row['reference_name'];
									$profiles['reference_lastname'] = $row['reference_lastname'];
									$profiles['reference_position'] = $row['reference_position'];
									$profiles['reference_email'] = $row['reference_email'];
									$profiles['reference_phone'] = $row['reference_phone'];
									$profiles['working'] = $row['working'];
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
									$profiles['emergency_first_name'] = $row['e_first_name'];
									$profiles['emergency_second_name'] = $row['e_second_name'];
									$profiles['emergency_first_lastname'] = $row['e_first_lastname'];
									$profiles['emergency_second_lastname'] = $row['e_second_lastname'];
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
									$profiles['iddocuments'] = 'N/A';
									$profiles['doc_type'] = 'N/A';
									$profiles['doc_path'] = 'N/A';
								}
								echo(json_encode($profiles))
							}else{
								echo($sql6);
								http_response_code(400);
							}
						}else{
							echo($sql5);
							http_response_code(400);
						}

					}else{
						echo($sql4);
						http_response_code(400);
					}
				}else{
					echo($sql3);
					http_response_code(422);
				}
			}else{
				echo($sql2);
				http_response_code(422);
			}
			
		}else{
			echo($sql);
			http_response_code(422);
		}
	}
?>