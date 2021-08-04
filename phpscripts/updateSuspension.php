<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");

$request = json_decode($postdata);

$id = ($request->iddp);
$day_1 = addQuotes(validarDatos($request->day_1));
$day_2 = addQuotes(validarDatos($request->day_2));
$day_3 = addQuotes(validarDatos($request->day_3));
$day_4 = addQuotes(validarDatos($request->day_4));


$sql = "UPDATE `suspensions` SET `day_1`=$day_1, `day_2`=$day_2, `day_3`=$day_3, `day_4`=$day_4 WHERE `id_disciplinary_process`='{$id}';";

if(mysqli_query($con, $sql)){
	http_response_code(200);
}else{
	echo($sql);
	http_response_code(400);
}
?>