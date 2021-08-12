<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$id_paymentmethod = ($request->id_paymentmethod);
$id_account = ($request->id_account_py);
$id_period = ($request->id_period);
$id_user = ($request->idpayments);

$sql = "INSERT INTO `minearsol`.`payments` (`id_employee`, `id_paymentmethod`, `id_period`, `id_account_py`) VALUES ('$id_employee', '$id_paymentmethod', '$id_period', $id_account);";
$sql1= "INSERT INTO `internal_processes` (`idinternal_processes`, `id_user`, `id_employee`, `name`, `date`, `status`, `notes`) VALUES (NULL, $id_user, $id_employee, 'Insert payment', DATE_FORMAT(NOW(), '%Y-%m-%d'), CONCAT('Manualy Deleted AT ', NOW()));"
if(mysqli_query($con,$sql)){  
  if(mysqli_query($con,$sql1)){
    echo(json_encode("1"));
  }else{
    $error = $sql . "|" . mysqli_error($con);
    echo(json_encode($error));
  }
}else{  
  $error = $sql . "|" . mysqli_error($con);
  echo(json_encode($error));
}
?>
