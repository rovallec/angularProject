<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$id_user = ($request->id_period);
$id_payment = ($request->idpayments);

$sql = "DELETE FROM payments WHERE idpayments = $id_payment;";
$sql2 = "DELETE FROM credits WHERE id_payment = $id_payment;";
$sql3 = "DELETE FROM debits WHERE id_payment = $id_payment;";
$sql4 = "DELETE FROM payroll_values WHERE id_payment = $id_payment;";
$sql1= "INSERT INTO `internal_processes` (`idinternal_processes`, `id_user`, `id_employee`, `name`, `date`, `status`, `notes`) VALUES 
(NULL, $id_user, $id_employee, 'Delete payment', DATE_FORMAT(NOW(), '%Y-%m-%d'), 'COMPLETED', 
CONCAT('Manualy Deleted AT ', NOW(), ' | ', (SELECT CONCAT_WS(",", COALESCE(idpayments, 'NULL'), 
COALESCE(id_employee, 'NULL'), COALESCE(id_paymentmethod, 'NULL'), COALESCE(id_period, 'NULL'), COALESCE(credits, 'NULL'), COALESCE(debits, 'NULL'), 
COALESCE(date, 'NULL'), COALESCE(last_seventh, 'NULL'), COALESCE(ot, 'NULL'), COALESCE(ot_hours, 'NULL'), COALESCE(base_hours, 'NULL'), 
COALESCE(productivity_hours, 'NULL'), COALESCE(base, 'NULL'), COALESCE(productivity, 'NULL'), COALESCE(sevenths, 'NULL'), COALESCE(holidays, 'NULL'), 
COALESCE(holidays_hours, 'NULL'), COALESCE(base_complete, 'NULL'), COALESCE(productivity_complete, 'NULL'), COALESCE(id_account_py, 'NULL'), 
COALESCE(job_type_py, 'NULL')) FROM payments WHERE idpayments =  $id_payment)));";

if(mysqli_query($con,$sql2)){  
  if(mysqli_query($con,$sql3)){
    if(mysqli_query($con,$sql4)){
      if(mysqli_query($con,$sql1)){
        if(mysqli_query($con,$sql)){
          echo(json_encode("1"));
        }else{
          $error = $sql . "|" . mysqli_error($con);
          echo(json_encode($error));
        }
      }else{
        $error = $sql1 . "|" . mysqli_error($con);
        echo(json_encode($error));
      }
    }else{
      $error = $sql4 . "|" . mysqli_error($con);
      echo(json_encode($error));
    }
  }else{
    $error = $sql3 . "|" . mysqli_error($con);
    echo(json_encode($error));
  }
}else{  
  $error = $sql2 . "|" . mysqli_error($con);
  echo(json_encode($error));
}
?>
