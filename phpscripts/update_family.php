<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$postdata = file_get_contents("php://input");

if(isset($postdata) && !empty($postdata)){
  $request = json_decode($postdata);
  $val2 = $request;
  if (!empty($val2->affinity_idfamilies) && isset($val2->affinity_idfamilies)) {
    $r_val_idfamilies = ($val2->affinity_idfamilies);
    $r_val_first_name = ($val2->affinity_first_name);
    $r_val_second_name = ($val2->affinity_second_name);
    $r_val_first_last_name = ($val2->affinity_first_last_name);
    $r_val_second_last_name = ($val2->affinity_second_last_name);
    $r_val_phone = ($val2->affinity_phone);
    $r_val_relationship = ($val2->affinity_relationship);
    $r_val_birthdate = ($val2->affinity_birthdate);

    $sql = "update families set first_name = '{$r_val_first_name}', second_name = '{$r_val_second_name}', first_last_name = '{$r_val_first_last_name}', second_last_name = '{$r_val_second_last_name}', phone = '{$r_val_phone}', relationship = '{$r_val_relationship}', birthdate = '{$r_val_birthdate}' where idfamilies = {$r_val_idfamilies};";

    if(mysqli_query($con,$sql)){
      echo(json_encode("1|1"));
    }else{
      $error = $sql . "|" . mysqli_error($con);
      echo(json_encode($error));
    }
  }
} else {
  echo("Empty Object");
}
?>