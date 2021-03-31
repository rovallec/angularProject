<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_profile = ($request->id_profile);
$idemnization = ($request->idemnization);
$aguinaldo = ($request->aguinaldo);
$aguinaldo = ($request->aguinaldo);
$bono14 = ($request->bono14);
$igss = ($request->igss);
$taxpendingpayment = ($request->taxpendingpayment);

$sql =  "INSERT INTO formeremplorer(idformer_emplorer, id_profile, idemnization, aguinaldo, bono14, igss, taxpendingpayment) " .
        "VALUES (null, $id_profile, $idemnization, $aguinaldo, $bono14, $igss, $taxpendingpayment) ;";
if(mysqli_query($con,$sql)){
	echo(mysqli_insert_id($con));
}else{
	echo($sql);
}
?>