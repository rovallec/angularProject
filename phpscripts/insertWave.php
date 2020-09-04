<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_account = ($request->id_account);
$starting_date = ($request->starting_date);
$end_date = ($request->end_date);
$max_recriut = ($request->max_recriut);
$hires = ($request->hires);
$name = ($request->name);
$training_schedule = ($request->trainning_schedule);
$prefix = ($request->prefix);
$ops_start = ($request->ops_start);
$state = ($request->state);
$base_payment = ($request->base_payment);
$job = ($request->job);
$productivity_payment = ($request->productivity_payment);

$sql = "INSERT INTO `waves`(`idwaves`, `id_account`, `starting_date`, `end_date`, `max_recriut`, `hires`, `name`, `trainning_schedule`, `prefix`, `ops_start`, `state`, `base_payment`, `position`, `productivity_payment`) VALUES (null, '$id_account', '$starting_date', '$end_date', '$max_recriut', '0', '$name', '$training_schedule', '$prefix', '$ops_start', '$state', '$base_payment', '$job', '$productivity_payment');";
if(mysqli_query($con,$sql)){
	echo(mysqli_insert_id($con));
}else{
	echo($sql);
}
?>