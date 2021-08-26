<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$user = [];

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$start = ($request->start);
$end = ($request->end);

$sql = "INSERT INTO `roster_times` VALUES (NULL, '$start', '$end');";

if(mysqli_query($con, $sql)){
    echo json_encode("1");
}else{
    echo(mysqli_error($con));
}

?>
