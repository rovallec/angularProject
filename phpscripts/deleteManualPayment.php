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
$sql1= "INSERT INTO `internal_processes` (`idinternal_processes`, `id_user`, `id_employee`, `name`, `date`, `status`, `notes`) VALUES (NULL, $id_user, $id_employee, 'Delete payment', DATE_FORMAT(NOW(), '%Y-%m-%d'), CONCAT('Manualy Deleted AT ', NOW()));";

echo($sql);
echo($sql1);
echo($sql2);
echo($sql3);
echo($sql4);
?>
