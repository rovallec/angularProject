<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->id_employee);
$number = ($request->number);
$bank = ($request->bank);
$t = 1;

$sql = "INSERT INTO `minearsol`.`payment_methods` (`idpayment_methods`, `id_employee`, `type`, `number`, `bank`) VALUES (NULL, $id_employee, 'BANK ACCOUNT', '$number', '$bank');";

$sql1 = "SELECT * FROM `payment_methods` WHERE `id_employee` = $id_employee;";
if($res=mysqli_query($con, $sql1)){
    while($row = mysqli_fetch_assoc($res)){
        $id_paymentMethod = $res['idpayment_methods'];
        $sql2 = "UPDATE `payment_methods` SET `predeterm` = 0 WHERE `idpayment_methods` = $id_paymentMethod;";
        if(mysqli_query($con, $sql2)){}else{
            http_response_code(404);
            $t = 0;
        }
    }
}
if($t == 1){
    if(mysqli_query($con, $sql)){
        echo("1");
    }
}
?>