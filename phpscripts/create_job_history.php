<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
    require 'database.php';
    $postdata = file_get_contents("php://input");

    if(isset($postdata) && !empty($postdata)){
        $request = json_decode($postdata);
        foreach ($request as $val) {
            $r_val_id_profile = ($val->id_profile);
            $r_val_company = ($val->company);
            $r_val_date_joining = ($val->date_joining);
            $r_val_date_end = ($val->date_end);
            $r_val_position = ($val->position);
            $r_val_reference_name = ($val->reference_name);
            $r_val_reference_lastname = ($val->reference_lastname);
            $r_val_reference_position = ($val->reference_position);
            $r_val_reference_mail = ($val->reference_mail);
            $r_val_reference_phone = ($val->reference_phone);
            $r_val_working = ($val->working);

            $sql = "INSERT INTO `job_histories`(`id_profile`, `company`, `date_joining`,`date_end`, `position`, `reference_name`, `reference_lastname`, `reference_position`, `reference_email`, `reference_phone`, `working`) VALUES ('{$r_val_id_profile}','{$r_val_company}','{$r_val_date_joining}','{$r_val_date_end}','{$r_val_position}','{$r_val_reference_name}','{$r_val_reference_lastname}', '{$r_val_reference_position}', '{$r_val_reference_mail}', '{$r_val_reference_phone}', '{$r_val_working}');";
            
            if(mysqli_query($con,$sql)){					
                http_response_code(200);
            }else{
                http_response_code(422);                
            }
        }
    }
    
?>