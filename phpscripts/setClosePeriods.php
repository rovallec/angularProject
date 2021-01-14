<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_period = ($request->id_period);

$sql1 = "SET @AID_PERIOD=$id_period;";
$sql2 = "PREPARE S FROM 'CALL CLOSE_PERIODS ( ? , ?)';";
$sql3 = "EXECUTE S USING @AID_PERIOD, @APROFILED;";


if(mysqli_query($con,$sql1)){
    if(mysqli_query($con,$sql2)){
        if(mysqli_query($con,$sql3)){
          http_response_code(200);
                echo("1");
        } else {
            http_response_code(403);
            echo($con->error);
        }
    } else {
        http_response_code(402);
        echo($con->error);
    }
} else {
    http_response_code(401);
    echo($con->error);
}
?>