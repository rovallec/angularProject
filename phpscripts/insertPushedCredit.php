<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_debit = ($request->iddebits);
$id_user = ($request->id_user);
$id_employee = ($request->id_employee);
$date = ($request->date);
$notes = ($request->notes);

$sql = "INSERT INTO `minearsol`.`pushed_credits` (`idacc_processes`, `id_credit`, `id_user`, `id_employee`, `name`, `date`, `notes`) VALUES (NULL, $id_debit, $id_user, $id_employee, 'Credit', '$date', '$notes');";

if(mysqli_query($con,$sql)){
  echo("1");
} else {
  http_response_code(400);
  $error = "0|" . $sql . "|" . mysqli_error($con);
  echo(json_encode($error));
}
?>