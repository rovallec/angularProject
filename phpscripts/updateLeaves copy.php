<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_process = ($request->id_process);
$headsets = ($request->headsets);
$accesscard = ($request->access_card);

$sql = "UPDATE `terminations` SET `headsets` = $headsets, `access_card` = $accesscard WHERE `id_process` = $id_process;";

if(mysqli_query($con, $sql)){
    http_response_code(200);
    echo("1");
}else{
    http_response_code(404);
    echo($sql);
}
?>