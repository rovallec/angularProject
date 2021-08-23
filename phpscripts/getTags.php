<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$proccesses = [];
$i = 0;


$sql = "SELECT DISTINCT(tag) FROM roster_types;";
    if($result = mysqli_query($con,$sql)){
        while($row = mysqli_fetch_assoc($result)){
            $proccesses[$i] = $row['idprocess_types'];
            $i++;
        };
        echo json_encode($proccesses);
    }else{
        http_response_code(404);
    }

?>