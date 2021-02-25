<?php
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Headers: *');
  require 'database.php';
  $postdata = file_get_contents("php://input");

  if(isset($postdata) && !empty($postdata)){
    $request = json_decode($postdata);
    $id = $request->affinity_idfamilies;

    $sql = "DELETE FROM families WHERE idfamilies = {$id};";
    if(mysqli_query($con,$sql)){					                
      http_response_code(200);                
    }else{
      http_response_code(422);
      echo($sql);            
    }
  }
?> 