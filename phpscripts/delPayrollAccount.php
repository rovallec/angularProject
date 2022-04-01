<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: *');
  require 'database.php';
  $postdata = file_get_contents("php://input");

  if(isset($postdata) && !empty($postdata)){
    $request = json_decode($postdata);
    $id_period = $request->id_period;
    $id_account = $request->id_account;

    $sql = "DELETE FROM payroll_values WHERE id_period = $id_period AND id_account= $id_account;";
    
    if(mysqli_query($con,$sql)){  
      echo(json_encode(""));
      http_response_code(200);                
    } else {
      $error =  mysqli_error($con);
        echo(json_encode("<br>Error 1: " . $error . "<br>"));
        //throw new Exception($error);
      echo(json_encode($sql));

      http_response_code(422);
      
    }
  }
?> 