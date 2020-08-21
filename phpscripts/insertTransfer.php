<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$employee = ($request->employee);
$account_id = ($request->account);

$sql = "UPDATE FROM `employees` SET `id_account` = '$account_id' WHERE `idemployees` = '$employee'";
echo($sql);
?>