<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$id_process = ($request->id_process);
$new_position = ($request->new_position);
$new_salary = ($request->new_salary);
$new_productivity_payment = ($request->new_productivity_payment);
$approved_by = ($request->approved_by);
$approved_date = ($request->approved_date);
$effective_date = ($request->effective_date);
$trial_start = ($request->trial_start);
$trial_end = ($request->trial_end);

$sql = "INSERT INTO `rises` (`idrises`, `id_process`, `new_position`, `new_salary`, `approved_by`, `approved_date`, `effective_date`, `trial_start`, `trial_end`) VALUES (NULL, '$id_process', '$new_position', '$new_salary', '$approved_by', '$approved_date', '$effective_date', '$trial_start', '$trial_end');";
if(mysqli_query($con,$sql)){
    $sql2 = "UPDATE `employees` SET `productivity_payment` = '$new_productivity_payment' WHERE `idemployees` = '$id_employee';";
    if(mysqli_query($con,$sql2)){
        http_response_code(200);
    }else{
        http_response_code(400);
        echo($sql2);        
    }
}else{
    http_response_code(400);
    echo($sql);
}
?>
