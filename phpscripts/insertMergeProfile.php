<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_old = ($request->id_old);
$id_new = ($request->id_new);

$sql = "EXEC MATCH_PROFILES @APROFILEO = $id_old, @APROFILEDN = $id_new";

if(mysqli_query($con,$sql)){
    http_response_code(200);
}else{
    http_response_code(400);
}
?>