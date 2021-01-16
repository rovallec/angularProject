<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_period = ($request->id_period);

$sql1 = "SET @AID_PERIOD=$id_period;";
$sql2 = "PREPARE S FROM 'CALL CLOSE_PERIODS ( ? )';";
$sql3 = "EXECUTE S USING @AID_PERIOD;";
/*$sql4 = "SELECT @V_NEW_ID_PERIOD AS NEWPERIOD"; */

if(mysqli_query($con,$sql1)){
    if(mysqli_query($con,$sql2)){
        if(mysqli_query($con,$sql3)){
            /* if($result = mysqli_query($con,$sql4)){
                $res = mysqli_fetch_assoc($result);
                $return[0]['NEWPERIOD'] = $res['NEWPERIOD'];
                echo(json_encode($return));
                */
                http_response_code(200);                
                echo("1");
                /*
            } else {
                http_response_code(404);
                echo($con->error);
                echo($sql4);
            } */
        } else {
            http_response_code(403);
            echo($con->error);
            echo($sql3);
        }
    } else {
        http_response_code(402);
        echo($con->error);
        echo($sql2);
    }
} else {
    http_response_code(401);
    echo($con->error);
    echo($sql1);
}
?>