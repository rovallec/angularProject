<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idjudicials = ($request->idjudicials);
$amount = ($request->amount);
$max = ($request->max);
$current = ($request->current);
$idhr_process = ($request->id_process);
$user_name = ($request->user_name);
$time = ($request->time);
$notes = ($request->notes);
$status = ($request->status);

$notes = $notes . ' | edit by: ' . $user_name . ' time: ' . $time;


$sql = "UPDATE judicials SET 
        amount = $amount,
        max = $max,
        current = $current
        WHERE idjudicials = $idjudicials;";

$sql1 = " UPDATE hr_processes SET
            status = '$status',
            notes = '$notes'          
          WHERE idhr_processes = $idhr_process;";

if (mysqli_query($con, $sql)) {
  if (mysqli_query($con, $sql1)) {
    echo (json_encode("1|"));
  } else {
    $r = json_encode("0| SQL1: " . $sql1 . " Error: " . mysqli_error($con));
    echo ($r); 
  }
  //echo (json_encode("1|"));
} else {
  $r = json_encode("0| SQL: " . $sql . " Error: " . mysqli_error($con));
  echo ($r);
}
?>