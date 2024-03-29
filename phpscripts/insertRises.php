<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$id_process = ($request->id_process);
$new_position = ($request->new_position);
$old_position = ($request->old_position);
$new_salary = ($request->new_salary);
$old_salary = ($request->old_salary);
$new_productivity_payment = ($request->new_productivity_payment);
$approved_by = ($request->approved_by);
$approved_date = ($request->approved_date);
$effective_date = ($request->effective_date);
$trial_start = ($request->trial_start);
$trial_end = ($request->trial_end);

$sql = "INSERT INTO `rises` (`idrises`, `id_process`, `new_position`, `old_position`, `new_salary`, `old_salary`, `approved_by`, `approved_date`, `effective_date`, `trial_start`, `trial_end`) VALUES (NULL, '$id_process', '$new_position', '$old_position', '$new_salary', '$old_salary', '$approved_by', '$approved_date', '$effective_date', '$trial_start', '$trial_end');";
if(mysqli_query($con,$sql)) {
  $sql2 = "UPDATE `employees` SET `productivity_payment` = $new_productivity_payment, `job` = '$new_position' WHERE `idemployees` = $id_employee;";
  if(mysqli_query($con,$sql2)) {
    echo(json_encode("1|1"));
  } else {
    $error = $sql2 . "|" . mysqli_error($con);
    echo(json_encode($error));
  }
} else {
  $error = $sql . "|" . mysqli_error($con);
  echo(json_encode($error));
}
?>
