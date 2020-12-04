<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_old = ($request->id_old);
$id_new = ($request->id_new);

$sql1 = "SET @APROFILEO=$id_old;";
$sql2 = "SET @APROFILED=$id_new;";
$sql3 = "PREPARE S FROM 'CALL MATCH_PROFILES ( ? , ?)';";
$sql4 = "EXECUTE S USING @APROFILEO, @APROFILED;";

if(mysqli_query($con,$sql1)){
    if(mysqli_query($con,$sql2)){
        if(mysqli_query($con,$sql3)){
            if(mysqli_query($con,$sql4)){
                http_response_code(200);
                echo("1");
            } else {
                http_response_code(404);
            }
        } else {
            http_response_code(403);
        }
    } else {
        http_response_code(402);
    }
} else {
    http_response_code(401);
}
?>