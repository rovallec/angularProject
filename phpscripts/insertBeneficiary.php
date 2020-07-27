<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);


$idbeneficiaries = ($request->idbeneficiaries);
$first_name = ($request->first_name);
$second_name = ($request->second_name);
$first_lastname = ($request->first_lastname);
$second_lastname = ($request->second_lastname);
$afinity = ($request->afinity);

$sql = "INSERT FROM `beneficiaries` (`idbeneficiaries`, `id_insurance`, `first_name`, `second_name`, `first_lastname`, `second_lastname`, `afinity`) VALUES (NULL, '$idbeneficiaries', '$first_name', '$second_name', '$first_lastname', '$second_lastname', '$afinity');";

if(mysqli_query($con, $sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>