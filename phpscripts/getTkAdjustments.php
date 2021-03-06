<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_payment = ($request->id_payment);
$period = ($request->id_period);
$res = [];
$i = 0;

$sql = "SELECT id_payment, SUM(amount) AS `amt` FROM timekeeping_adjustments
        INNER JOIN payments ON payments.idpayments = timekeeping_adjustments.id_payment
        INNER JOIN employees ON employees.idemployees = payments.id_employee
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        WHERE id_payment = $id_payment AND id_period = $period GROUP BY id_payment;";


if($result = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($result)){
    $res['id_payment'] = $row['id_payment'];
    $res['amount'] = $row['amt'];
  };
echo json_encode($res);
} else {
  http_response_code(400);
  $error = "0|" . $sql . "|" . mysqli_error($con);
  echo(json_encode($error));
}
?>