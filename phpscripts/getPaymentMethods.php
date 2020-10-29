<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->idemployees);

$result = [];
$i = 0;
$sql = "SELECT * FROM payment_methods WHERE `id_employee` = $id_employee;";

if($res = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($res)){
        $result[$i]['idpayment_methods'] = $row{'idpayment_methods'};
        $result[$i]['id_employee'] = $row{'id_employee'};
        $result[$i]['type'] = $row{'type'};
        $result[$i]['number'] = $row{'number'};
        $result[$i]['bank'] = $row{'bank'};
        $result[$i]['predeterm'] = $row{'predeterm'};
    }
    echo(json_encode($result));
}
?>