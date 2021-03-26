<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';

mysqli_begin_transaction($con, MYSQLI_TRANS_START_READ_WRITE);
mysqli_autocommit($con, FALSE);
$id_profile ='';
$id_hire =''; 
$id_employees=''; 
$idemergency_Details=''; 
$idmedical_details='';
$ideducation_details='';
$id_process='';
$idmarketing_details=''; 
$idprocess_details='';
$idinternal_processes='';
$idservices='';


$postdata = file_get_contents("php://input");
if(isset($postdata) && !empty($postdata)){	
  $request = json_decode($postdata);
  $tittle = ($request->tittle);
  $first_name = ($request->first_name);
  $second_name = ($request->second_name);
  $first_lastname = ($request->first_lastname);
  $second_lastname = ($request->second_lastname);
  $day_of_birthday = formatDates($request->day_of_birth);
  $nationality = ($request->nationality);
  $marital_status = ($request->marital_status);
  $dpi = ($request->dpi);
  $nit = ($request->nit);
  $igss = ($request->igss);
  $irtra = ($request->irtra);
  $bank = ($request->bank);
  $account = ($request->account);
  $account_type = ($request->account_type);
  $gender = ($request->gender);
  $etnia = ($request->etnia);
  $profesion = ($request->profesion);
  $birth_place = ($request->birth_place);
  $emergency_first_name = ($request->emergency_first_name);
  $emergency_second_name = ($request->emergency_second_name);
  $emergency_first_lastname = ($request->emergency_first_lastname);
  $emergency_second_lastname = ($request->emergency_second_lastname);
  $emergency_phone = ($request->emergency_phone);
  $emergency_relationship = ($request->emergency_relationship);
  $medical_treatment = ($request->medical_treatment);
  $medical_prescription = ($request->medical_prescription);
	$current_level = ($request->current_level);
  $further_education = ($request->further_education);
  $currently_studing = ($request->currently_studing);
  $institution_name = ($request->institution_name);
  $degree = ($request->degree);  
  $name = ($request->name);
  $description = ($request->description);
  $waves = json_decode(json_encode($request->wave));
  $date = formatDates(($waves->starting_date));
  $id_userpr = ($request->id_userpr);
  $id_user = ($request->id_user);
  $amount = ($request->amount);
  // hires
  $id_wave = ($request->id_wave);
  $nearsol_id = ($request->nearsol_id);
  $reports_to = ($request->reports_to);
  $id_schedule = ($request->id_schedule);

  //Marketing_details
  $source = ($request->sourse);
  $post = ($request->post);
  $refer = ($request->refer);
  $about = ($request->about);
  
  $employee = json_decode(json_encode($request->employee));
  $id_account = ($employee->id_account);
  $reporter = ($employee->reporter);
  $client_id = ($employee->client_id);
  $hiring_date = formatDates(($employee->hiring_date));
  $job = ($employee->job);
  $base_payment = ($employee->base_payment);
  $productivity_payment = ($employee->productivity_payment);
  $platform = ($employee->platform);
 
  $sql =  "INSERT INTO profiles (tittle, first_name, second_name, first_lastname, second_lastname, day_of_birth, " .
          "nationality, marital_status, dpi, nit, iggs, irtra, status, bank, account, account_type, gender, etnia, " . 
          "profesion, birth_place) " .
          "VALUE ('{$tittle}', '{$first_name}','{$second_name}','{$first_lastname}','{$second_lastname}', " .
          "'{$day_of_birthday}' ,'{$nationality}','{$marital_status}','{$dpi}','{$nit}','{$igss}', " .
          "'{$irtra}','EMPLOYEE', '{$bank}', '{$account}', '{$account_type}', '{$gender}', '{$etnia}', " .
          "'{$profesion}', '{$birth_place}');";  

  try 
  {
    if(mysqli_query($con, $sql))
    {
      $id_profile = mysqli_insert_id($con);    

      $sql2 = "INSERT INTO hires (idhires, id_profile, id_wave, nearsol_id, reports_to, id_schedule) " .
              "VALUES (null, '$id_profile', '$id_wave', '$nearsol_id', '$reports_to', '$id_schedule');";

      if(mysqli_query($con, $sql2))
      {
        $id_hire = mysqli_insert_id($con);

        $sql3 = "INSERT INTO employees(idemployees, id_hire, id_account, reporter, client_id, " .
                "hiring_date, job, base_payment, state, productivity_payment, active, platform) " .
                "VALUES (null, $id_hire, $id_account, $reporter, '$client_id', '$hiring_date', '$job', " .
                "$base_payment, 'EMPLOYEE', $productivity_payment, 1, '$platform');";

        if(mysqli_query($con, $sql3))
        {
          $id_employees = mysqli_insert_id($con);

          $sql4 = "INSERT INTO emergency_details(id_profile, e_first_name, e_second_name, e_first_lastname, " .
                  "e_second_lastname, phone, relationship) VALUES ('{$id_profile}','{$emergency_first_name}', " . 
                  "'{$emergency_second_name}', '{$emergency_first_lastname}', '{$emergency_second_lastname}', '{$emergency_phone}', '{$emergency_relationship}');";

          if(mysqli_query($con, $sql4))
          {
            $idemergency_Details = mysqli_insert_id($con);
            //if ((validarDatosString($medical_treatment)!='') && (validarDatosString($medical_prescription)!='')) {
            $sql5 = "INSERT INTO medical_details (idmedical_details, id_profile, medical_treatment, medical_prescription) " .
                    "VALUES (null, $id_profile, '$medical_treatment', '$medical_prescription');";

            if(mysqli_query($con, $sql5))
            {
              $idmedical_details = mysqli_insert_id($con);

              $sql6 = "INSERT INTO education_details(ideducation_details, id_profile, current_level, " . 
                      "further_education, currently_studing, institution_name, `degree`) ".
                      "VALUES (null, $id_profile, '$current_level', '$further_education', '$currently_studing', ". 
                      "'$institution_name', '$degree');";
                      
              if(mysqli_query($con, $sql6))
              {
                $ideducation_details = mysqli_insert_id($con);

                $sql7 = "INSERT INTO processes (id_role, id_profile, name, description, prc_date, id_user, status) " .
                        "VALUES (1, $id_profile, '$name', '$description', '$date', $id_userpr, 'CLOSED');";

                if(mysqli_query($con, $sql7))
                {
                  $id_process = mysqli_insert_id($con);

                  $sql8 = "INSERT INTO marketing_details(id_process, source, post, referrer, about) " .
                          "VALUES ($id_process, '$source', '$post', '$refer', '$about');";
                          
                  if(mysqli_query($con, $sql8))
                  {
                    $idmarketing_details = mysqli_insert_id($con);
                    
                    $sql9 = "INSERT INTO process_details(id_process, name, value) " .
                            "VALUES ($id_process, 'Notes', 'Contratacion Completada'), ($id_process, 'Result', 'Aproved');";

                    if(mysqli_query($con, $sql9))
                    {
                      $idprocess_details = mysqli_insert_id($con);

                      if (validarDatosString($amount)!='') {
                        $sql10= "INSERT INTO internal_processes(id_user, id_employee, name, date, status, notes) " .
                                "VALUES ($id_user, $id_employees, 'hiring bonus', '$date', 'COMPLETED', 'Hiring bonus.');";

                        if(mysqli_query($con, $sql10))
                        {
                          $idinternal_processes = mysqli_insert_id($con);

                          $sql11= "INSERT INTO services(id_process, name, amount, max, frecuency, status, `current`) " .
                                  "VALUES ($idinternal_processes, 'hiring modules', $amount, 0, 'UNIKE', 1, 0);";

                          if(mysqli_query($con, $sql11))
                          {
                            $idservices = mysqli_insert_id($con);
                            //mysqli_commit($con);
                          }else{
                            //mysqli_rollback($con);
                            $error = mysqli_error($con);
                            echo($sql11);
                            throw new Exception($error);
                          }
                        }else{
                          $error = mysqli_error($con);
                          echo($sql10);
                          throw new Exception($error);
                        }
                      }
                    }else{
                      $error = mysqli_error($con);
                      echo($sql9);
                      throw new Exception($error);
                    }
                  }else{
                    $error = mysqli_error($con);
                    echo($sql8);
                    throw new Exception($error);
                  }
                }else{
                  $error = mysqli_error($con);
                  echo($sql7);
                  throw new Exception($error);
                }
              }else{
                $error = mysqli_error($con);
                echo($sql6);
                throw new Exception($error);
              }
            }else{
              $error = mysqli_error($con);
              echo($sql5);
              throw new Exception($error);
            }
          }else{
            $error = mysqli_error($con);
            echo($sql4);
            throw new Exception($error);
          }
        }else{
          $error = mysqli_error($con);
          echo($sql3);
          throw new Exception($error);
        }
      }else{
        $error = mysqli_error($con);
        echo($sql2);
        throw new Exception($error);
      }
    }else{
      $error = mysqli_error($con);
      echo($sql);
      throw new Exception($error);
    }
  }
  catch (Exception $e){
    mysqli_rollback($con);
    echo('Error: ' . $e->getMessage() . "\n");
  }
}  


//$data = "{id_profile: $id_profile, id_hire: $id_hire, id_employees: $id_employees, idemergency_Details: $idemergency_Details, idmedical_details: $idmedical_details, ideducation_details: $ideducation_details, id_process: $id_process, idmarketing_details: $idmarketing_details, idprocess_details: $idprocess_details, idinternal_processes: $idinternal_processes, idservices: $idservices}";
$data = [$id_profile, $id_hire, $id_employees, $idemergency_Details, $idmedical_details, $ideducation_details, $id_process, $idmarketing_details, $idprocess_details, $idinternal_processes, $idservices];
//$data = json_decode($data);
echo(json_encode($data));

//mysqli_rollback($con);
mysqli_commit($con);

mysqli_autocommit($con, TRUE);
mysqli_close($con);
?>