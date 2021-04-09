<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_process = ($request->id_process);
$description = ($request->description);
$classification = ($request->classification);
$amount = ($request->amount);

$sql = "UPDATE advances SET
        description = '$description',
        classification = '$classification',
        amount = '$amount'
        WHERE id_process = $id_process;";

if(mysqli_query($con,$sql)){  
  echo(json_encode("1|1"));
}else{  
  $error = $sql . "|" . mysqli_error($con);
  echo(json_encode($error));
}
?>
