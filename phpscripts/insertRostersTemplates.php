<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$user = [];

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$tag = ($request->tag);
$name = ($request->name);
$id_mon = ($request->id_time_mon);
$id_tue = ($request->id_time_tue);
$id_wed = ($request->id_time_wed);
$id_thur = ($request->id_time_thur);
$id_fri = ($request->id_time_fri);
$id_sat = ($request->id_time_sat);
$id_sun = ($request->id_time_sun);

$sql = "INSERT INTO roster_types VALUES (NULL, '$tag', '$name', $id_mon, $id_tue, $id_wed, $id_thur, $id_fri, $id_sat, $id_sun);";

if($result = mysqli_query($con, $sql)){
    echo("1");
}else{
    echo(mysqli_error($con) . " | " . $sql);
    http_response_code(404);
}

?>
