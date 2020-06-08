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
		$day_of_birthday = ($request->day_of_birthday);
		$nationality = ($request->nationality);
		$marital_status = ($request->marital_status);
		$dpi = ($request->dpi);
		$nit = ($request->nit);
		$igss = ($request->igss);
		$irtra = ($request->irtra);
		$status = ($request->status);

		$sql = "UPDATE `profiles` set `tittle` = '{$tittle}', `first_name` = '{$first_name}', `second_name` = '{$second_name}', `first_lastname` = '{$first_lastname}', `second_lastname` = '{$second_lastname}', `day_of_birth` = '{$day_of_birthday}', `nationality` = '{$nationality}', `marital_status` = '{$marital_status}', `dpi` = '{$dpi}', `nit` = '{$nit}', `iggs` = '{$igss}', `irtra` = '{$irtra}', `status` = '{$status}' WHERE `id_profile` = '$idprofiles';";

		if(mysqli_query($con,$sql)){
			$id_profile = mysqli_insert_id($con);

			$idcontact_details = ($request->idcontact_details);
			$primary_phone = ($request->primary_phone);
			$secondary_phone = ($request->secondary_phone);
			$address = ($request->address);
			$city = ($request->city);
			$email = ($request->email);

			$sql2 = "UPDATE `contact_details` set `primary_phone` = '{$primary_phone}', `secondary_phone` = '{$secondary_phone}', `address` = '{$address}', `city` = '{$city}', `email` = '{$email}' WHERE `id_profile` = '{$idprofiles}';";
			
			if(mysqli_query($con, $sql2)){
				$idprofile_details = ($request->idprofile_details);
				$english_level = ($request->english_level);
				$transport = ($request->transport);
				$start_date = ($request->start_date);
				$unavialable_days = ($request->unavailable_days);
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
					$phone = ($request->emergency_phone);
					$relationship = ($request->relationship);

					$sql4 = "UPDATE `emergency_details` SET `e_first_name` = '{$first_name}', `e_second_name` = '{$second_name}', `e_first_lastname` = '{$first_lastname}', `e_second_lastname` = '{$second_lastname}', `phone` = '{$phone}', `relationship` = '{$relationship}' WHERE `id_profile` = '{$idprofiles}';";

					if(mysqli_query($con,$sql4)){
						$medical_treatment = ($request->medical_treatment);
						$medical_prescription = ($request->medical_prescription);

						$sql5 = "UPDATE `medical_details` SET `medical_treatment` = '{$medical_treatment}', `medical_prescription` = '{$medical_prescription}' WHERE `id_profile` = '{$idprofiles}';" ;

						if(mysqli_query($con,$sql5)){
								http_response_code(200);
								echo json_encode($request);
						}else{
							http_response_code(400);
						}

					}else{
						http_response_code(422);
					}
				}else{
					http_response_code(422);
				}
			}else{
				http_response_code(422);
			}
			
		}else{
			http_response_code(422);
		}
	}
?>