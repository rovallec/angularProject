<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

mysqli_begin_transaction($con, MYSQLI_TRANS_START_READ_ONLY);
mysqli_autocommit($con, FALSE);

$postdata = file_get_contents("php://input");
if(isset($postdata) && !empty($postdata)){	
  $request = json_decode($postdata);		
  $tittle = ($request->profile->tittle);
  $first_name = ($request->profile->first_name);
  $second_name = ($request->profile->second_name);
  $first_lastname = ($request->profile->first_lastname);
  $second_lastname = ($request->profile->second_lastname);
  $day_of_birthday = ($request->profile->day_of_birthday);
  $nationality = ($request->profile->nationality);
  $marital_status = ($request->profile->marital_status);
  $dpi = ($request->profile->dpi);
  $nit = ($request->profile->nit);
  $igss = ($request->profile->igss);
  $irtra = ($request->profile->irtra);
  $status = ($request->profile->status);
  $bank = ($request->profile->bank);
  $account = ($request->profile->account);
  $account_type = ($request->profile->account_type);
  $gender = ($request->profile->gender);
  $etnia = ($request->profile->etnia);
  $profesion = ($request->profile->profesion);
  $birth_place = ($request->profile->birth_place);
  $id_wave = ($request->hire->id_wave);
  $nearsol_id = ($request->hire->nearsol_id);
  $reports_to = ($request->hire->reports_to);
  $id_schedule = ($request->hire->id_schedule);  
  $id_account = ($request->employee->id_account);
  $reporter = ($request->employee->reporter);
  $client_id = ($request->employee->client_id);
  $hiring_date = ($request->employee->hiring_date);
  $job = ($request->employee->job);
  $base_payment = ($request->employee->base_payment);
  $productivity_payment = ($request->employee->productivity_payment);
  $platform = ($request->employee->platform);
  $first_name = ($request->emergency_first_name);
  $second_name = ($request->emergency_second_name);
  $first_lastname = ($request->emergency_first_lastname);
  $second_lastname = ($request->emergency_second_lastname);
  $phone = ($request->emergency_phone);
  $relationship = ($request->relationship);
  $medical_treatment = ($request->medical_treatment);
  $medical_prescription = ($request->medical_prescription);
	$current_level = ($request->current_level);
  $further_education = ($request->further_education);
  $currently_studing = ($request->currently_studing);
  $institution_name = ($request->institution_name);
  $degree = ($request->degree);  
  $name = ($request->name);
  $description = ($request->description);
  $date = ($request->waves->starting_date);
  $id_userpr = ($request->id_userpr);
  $id_user = ($request->id_user);
  $amount = ($request->amount);

      
  $sql =  "INSERT INTO profiles (tittle, first_name, second_name, first_lastname, second_lastname, day_of_birth, " .
          "nationality, marital_status, dpi, nit, iggs, irtra, status, bank, account, account_type, gender, etnia, " . 
          "profesion, birth_place) " .
          "VALUE ('{$tittle}','{$first_name}','{$second_name}','{$first_lastname}','{$second_lastname}', " .
          "'{$day_of_birthday}' ,'{$nationality}','{$marital_status}','{$dpi}','{$nit}','{$igss}', " .
          "'{$irtra}','{$status}', '{$bank}', '{$account}', '{$account_type}', '{$gender}', '{$etnia}', " .
          "'{$profesion}', '{$birth_place}');";
  
  if($result = mysqli_query($con, $sql))
  {
    $id_profile = mysqli_insert_id($con);
    echo $id_profile;

    $sql2 = "INSERT INTO hires (id_hires, id_profile, id_wave, nearsol_id, reports_to, id_schedule) " .
            "VALUES (null, $id_profile, $id_wave, '$nearsol_id', $reports_to, $id_schedule);";

    if($result2 = mysqli_query($con, $sql2))
    {
      $id_hire = mysqli_insert_id($con);
      echo $id_hire;
      
      $sql3 = "INSERT INTO employees(idemployees, id_hire, id_account, reporter, client_id, " .
              "hiring_date, job, base_payment, state, productivity_payment, active, platform) " .
              "VALUES (null, $id_hire, $id_account, $reporter, '$client_id', '$hiring_date', '$job', " .
              "$base_payment, 'EMPLOYEE', $productivity_payment, 1, '$platform');";

      if($result3 = mysqli_query($con, $sql3))
      {
        $id_employees = mysqli_insert_id($con);
        echo $id_employees;

        $sql4 = "INSERT INTO emergency_details(id_profile, e_first_name, e_second_name, e_first_lastname, " .
                "e_second_lastname, phone, relationship) VALUES ('{$id_profile}','{$first_name}', " . 
                "'{$second_name}', '{$first_lastname}', '{$second_lastname}', '{$phone}', '{$relationship}');";

        if($result4 = mysqli_query($con, $sql4))
        {
          $idemergency_Details = mysqli_insert_id($con);
          echo $id_employees;

          $sql5 = "INSERT INTO medical_details (idmedical_details, id_profile, medical_treatment, medical_prescription) " .
                  "VALUES (null, $id_profile, '$medical_treatment', '$medical_prescription');";

          if($result5 = mysqli_query($con, $sql5))
          {
            $idmedical_details = mysqli_insert_id($con);
            echo $id_employees;

            $sql6 = "INSERT INTO education_details(ideducation_details, id_profile, current_level, " . 
                    "further_education, currently_studing, institution_name, `degree`) ".
                    "VALUES (null, $id_profile, '$current_level', '$further_education', '$currently_studing', ". 
                    "'$institution_name', '$degree');";
                    
            if($result6 = mysqli_query($con, $sql6))
            {
              $ideducation_details = mysqli_insert_id($con);
              echo $id_employees;

              $sql7 = "INSERT INTO processes (id_role, id_profile, name, description, prc_date, id_user, status) " .
                      "VALUES (1, $id_profile, '$name', '$description', '$date', $id_userpr, 'CLOSED');";

              if($result7 = mysqli_query($con, $sql7))
              {
                $id_process = mysqli_insert_id($con);
                echo $id_process;

                $sql8 = "INSERT INTO marketing_details(id_process, source, post, referrer, about) " .
                        "VALUES ($id_process, '$source', '$post', '$referrer', '$about');";
                        
                if($result8 = mysqli_query($con, $sql8))
                {
                  $idmarketing_details = mysqli_insert_id($con);
                  echo $idmarketing_details;

                  $sql9 = "INSERT INTO process_details(id_process, name, value) " .
                          "VALUES ($id_process, 'Notes', 'Contratacion Completada'), ($id_process, 'Result', 'Aproved');";

                  if($result9 = mysqli_query($con, $sql9))
                  {
                    $idprocess_details = mysqli_insert_id($con);
                    echo $idprocess_details;

                    $sql10= "INSERT INTO internal_processes(id_user, id_employee, name, date, status, notes) " .
                            "VALUES ($id_user, $id_employee, 'hiring bonus', '$date', 'COMPLETED', 'Hiring bonus.');";

                    if($result10 = mysqli_query($con, $sql10))
                    {
                      $idinternal_processes = mysqli_insert_id($con);
                      echo $idinternal_processes;

                      $sql11= "INSERT INTO services(id_process, name, amount, max, frecuency, status, `current`) " .
                              "VALUES ($idinternal_processes, 'hiring modules', $amount, 0, 'UNIKE', 1, 0);";

                      if($result11 = mysqli_query($con, $sql11))
                      {
                        $idservices = mysqli_insert_id($con);
                        echo $idservices;
                        mysqli_commit($con);
                      }else{
                        http_response_code(411);
                        echo($sql11);
                      }
                    }else{
                      http_response_code(410);
                      echo($sql10);
                    }
                  }else{
                    http_response_code(409);
                    echo($sql9);
                  }
                }else{
                  http_response_code(408);
                  echo($sql8);
                }
              }else{
                http_response_code(407);
                echo($sql7);
              }
            }else{
              http_response_code(406);
              echo($sql6);
            }
          }else{
            http_response_code(405);
            echo($sql5);
          }
        }else{
          http_response_code(404);
          echo($sql4);
        }
      }else{
        http_response_code(403);
        echo($sql3);
      }
    }else{
      http_response_code(402);
      echo($sql2);
    }
  }else{
    http_response_code(401);
    echo($sql);
  }
}

mysqli_commit($con);

mysqli_autocommit($con, TRUE);
mysqli_free_result($result);
mysqli_free_result($result2);
mysqli_free_result($result3);
mysqli_free_result($result4);
mysqli_free_result($result5);
mysqli_free_result($result6);
mysqli_free_result($result7);
mysqli_free_result($result8);
mysqli_free_result($result9);
mysqli_free_result($result10);
mysqli_free_result($result11);
mysqli_close($con);
?>