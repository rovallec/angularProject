<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idwaves = ($request->idwaves);
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

$sql = "UPDATE `waves` SET `state` = '$state' WHERE `idwaves` = $idwaves";
if(mysqli_query($con,$sql)){
	echo(mysqli_insert_id($con));
}else{
	echo($sql);
}
?>