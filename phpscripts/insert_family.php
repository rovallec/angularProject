<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)){
  $request = json_decode($postdata);
  $val2 = $request;
  echo(json_encode($val2));

  if (!empty($val2->affinity_id_profile) && isset($val2->affinity_id_profile)) {
    $r_val_id_profile = ($val2->affinity_id_profile);
    $r_val_first_name = ($val2->affinity_first_name);
    $r_val_second_name = ($val2->affinity_second_name);
    $r_val_first_last_name = ($val2->affinity_first_last_name);
    $r_val_second_last_name = ($val2->affinity_second_last_name);
    $r_val_phone = ($val2->affinity_phone);
    $r_val_relationship = ($val2->affinity_relationship);
    $r_val_birthdate = ($val2->affinity_birthdate);

    $sql = "INSERT INTO `families`(`idfamilies`, `id_profile`, `first_name`, `second_name`,`first_last_name`, `second_last_name`, `phone`, `relationship`, `birthdate`) " .
            "VALUES (null, {$r_val_id_profile},'{$r_val_first_name}','{$r_val_second_name}','{$r_val_first_last_name}','{$r_val_second_last_name}','{$r_val_phone}','{$r_val_relationship}','{$r_val_birthdate}');";

    if(mysqli_query($con,$sql)){
      echo(json_encode("1|1"));
    }else{
      $error = $sql . "|" . mysqli_error($con);
      echo(json_encode($error));
    }
  } 
}
?> 