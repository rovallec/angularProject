<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
	require 'database.php';
	$postdata = file_get_contents("php://input");
	if(isset($postdata) && !empty($postdata)){	
		$request = json_decode($postdata);		
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

		$gender = ($request->gender);
		$etnia = ($request->etnia);
		$bank = ($request->bank);
		$account = ($request->account);		

		$sql = "INSERT INTO `profiles`(`tittle`, `first_name`, `second_name`, `first_lastname`, `second_lastname`, `day_of_birth`, `nationality`, `marital_status`, `dpi`, `nit`, `iggs`, `irtra`, `status`, `gender`, `etnia`, `bank`, `account`) VALUE ('{$tittle}','{$first_name}','{$second_name}','{$first_lastname}','{$second_lastname}','{$day_of_birthday}' ,'{$nationality}','{$marital_status}','{$dpi}','{$nit}','{$igss}','{$irtra}','{$status}', '{$gender}', '{$etnia}', '{$bank}', '{$account}');";
		if(mysqli_query($con,$sql)){
			$id_profile = mysqli_insert_id($con);
			$idcontact_details = ($request->idcontact_details);
			$primary_phone = ($request->primary_phone);
			$secondary_phone = ($request->secondary_phone);
			$address = ($request->address);
			$city = ($request->city);
			$email = ($request->email);

			$sql2 = "INSERT INTO `contact_details`(`id_profile`, `primary_phone`, `secondary_phone`, `address`, `city`, `email`) VALUES ('{$id_profile}', '{$primary_phone}', '{$secondary_phone}', '{$address}', '{$city}', '{$email}');";
			
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

				$sql3 = "INSERT INTO `profile_details`(`id_profile`, `english_level`, `transport`, `start_date`, `unavialable_days`, `marketing_campaing`, `first_lenguage`, `second_lenguage`, `third_lenguage`) VALUES ('{$id_profile}', '{$english_level}', '{$transport}', '{$start_date}', '{$unavialable_days}', '{$marketing_campaing}', '{$first_lenguage}', '{$second_lenguage}', '{$third_lenguage}');";

				if(mysqli_query($con,$sql3)){

					$first_name = ($request->emergency_first_name);
					$second_name = ($request->emergency_second_name);
					$first_lastname = ($request->emergency_first_lastname);
					$second_lastname = ($request->emergency_second_lastname);
					$phone = ($request->emergency_phone);
					$relationship = ($request->relationship);

					$sql4 = "INSERT INTO `emergency_details`(`id_profile`, `e_first_name`, `e_second_name`, `e_first_lastname`, `e_second_lastname`, `phone`, `relationship`) VALUES ('{$id_profile}','{$first_name}', '{$second_name}', '{$first_lastname}', '{$second_lastname}', '{$phone}', '{$relationship}');";

					if(mysqli_query($con,$sql4)){
						$medical_treatment = ($request->medical_treatment);
						$medical_prescription = ($request->medical_prescription);

						$sql5 = "INSERT INTO `medical_details`(`id_profile`, `medical_treatment`, `medical_prescription`) VALUES ('{$id_profile}','{$medical_treatment}', '{$medical_prescription}');";

						if(mysqli_query($con,$sql5)){
							$current_level = ($request->current_level);
							$futher_education = ($request->futher_education);
							$currently_studing = ($request->currently_studing);
							$institution_name = ($request->institution_name);
							$degree = ($request->degree);
							$sql6 = "INSERT INTO `education_details`(`id_profile`, `current_level`, `further_education`, `currently_studing`, `institution_name`, `degree`) VALUES ('{$id_profile}', '{$current_level}', '{$futher_education}','{$currently_studing}', '{$institution_name}','{$degree}');";
							if(mysqli_query($con,$sql6)){
									$affinity_first_name = ($request->affinity_first_name);
									$affinity_second_name = ($request->affinity_second_name);
									$affinity_phone = ($request->affinity_phone);
									$affinity_first_lastname = ($request->affinity_first_lastname);
									$affinity_second_lastname = ($request->affinity_second_lastname);
									$affinity_relationship = ($request->affinity_relationship);

									$sql7 = "INSERT INTO `families`(`id_profile`, `first_name`, `second_name`, `first_last_name`, `second_last_name`, `phone`, `relationship`) VALUES ('{$id_profile}', '{$affinity_first_name}', '{$affinity_second_name}','{$affinity_first_lastname}', '{$affinity_second_lastname}','{$affinity_phone}','{$affinity_relationship}');";
									if(mysqli_query($con,$sql7)){
									http_response_code(200);
									echo $id_profile;
									} else {
									
								}
							}else{
								http_response_code(400);
							}
						}else{
							http_response_code(400);
						}

					}else{
						http_response_code(422);
					}
				}else{
					http_response_code(423);
				}
			}else{
				http_response_code(424);
			}
			
		}else{
			http_response_code(425);
		}
	}
?>