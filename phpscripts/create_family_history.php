<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
    require 'database.php';
    $postdata = file_get_contents("php://input");

    if(isset($postdata) && !empty($postdata)){
        $request = json_decode($postdata);

        foreach ($request as $val2) {
            $test = json_encode($val2); 
            $val = json_decode($test); 
            $r_val_id_profile = ($val->id_profile);
            $r_val_first_name = ($val->affinity_first_name);
            $r_val_second_name = ($val->affinity_second_name);
            $r_val_first_last_name = ($val->affinity_first_last_name);
            $r_val_second_last_name = ($val->affinity_second_last_name);
            $r_val_phone = ($val->affinity_phone);
            $r_val_relationship = ($val->affinity_relationship);

            $sql = "INSERT INTO `families`(`id_profile`, `first_name`, `second_name`,`first_last_name`, `second_last_name`, `phone`, `relationship`) 
                    VALUES ('{$r_val_id_profile}','{$r_val_first_name}','{$r_val_second_name}','{$r_val_first_last_name}','{$r_val_second_last_name}','{$r_val_phone}','{$r_val_relationship}');";
            if(mysqli_query($con,$sql)){					
                http_response_code(200);
            }else{
                http_response_code(422);
                echo($sql);
            }
        }
    }
    
?>