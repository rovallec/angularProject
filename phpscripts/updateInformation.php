<?php
require 'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$sql = ($request->query);

if(mysqli_query($con,$sql)){
    echo('1');
}else{
    http_response_code(404);
}
?>