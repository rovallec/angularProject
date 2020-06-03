<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$schedule_name = ($request->schedule_name);
$start_time = ($request->start_time);
$end_time = ($request->end_time);
$days_off = ($request->days_off);
$id_wave = ($request->id_wave);
$actual_count = ($request->actual_count);
$max_count = ($request->max_count);
$available = ($request->state);

$sql = "INSERT INTO `schedules`(`idschedules`, `schedule_name`, `start_time`, `end_time`, `days_off`, `id_wave`, `actual_count`, `max_count`, `available`) VALUES (null, '$schedule_name', '$start_time', '$end_time', '$days_off', '$id_wave', '$actual_count', '$max_count', '$available')";
if(mysqli_query($con,$sql)){
	echo(mysqli_insert_id($con));
}else{
	echo($sql);
}
?>