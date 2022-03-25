<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idproviders = ($request->idproviders);
$name = ($request->name);
$contact = ($request->contact);
$phone = ($request->phone);
$cel = ($request->cel);
$email = ($request->email);
$service = ($request->service);
$credit = ($request->credit);
$conditions = ($request->conditions);
$days = ($request->days);
$contract_start = ($request->contract_start);
$contract_end = ($request->contract_end);
$no_iva = ($request->no_iva);

$sql = "INSERT INTO providers VALUES ($name, $contact, $phone, $cel, $email, $service, $credit, $conditions, $days, $contract_start, $contract_end, $no_iva)";

if(mysqli_query($con, $sql)){
    echo(json_encode('1'));
    http_response_code(200);
} else {
    echo(json_encode($sql));
    http_response_code(404);
}
?>
    