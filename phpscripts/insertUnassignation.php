<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_asset = $request->idassets;

$sql = "UPDATE `asset_movements` SET status = '0' WHERE id_asset = $id_asset";
if(mysqli_query($con, $sql)){
    echo(json_encode('1'));
}else{
    echo(json_encode(mysqli_error($con)));
}
?>