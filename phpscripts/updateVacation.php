<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_process = ($request->id_process);
$status = ($request->status);
$id_user = ($request->id_user);

$sql = "UPDATE hr_processes SET status = '$status', notes = CONCAT(notes, '" . '|| ' .$status.' By '. $id_user. "') WHERE idhr_processes = $id_process;";

if(mysqli_query($con, $sql)){
    echo(json_encode('1'));
    http_response_code(200);
} else {
    echo(json_encode($sql));
    http_response_code(404);
}
?>