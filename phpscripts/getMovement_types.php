<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$res[] = [];
$i = 0;

$sql1 = "SELECT * FROM movement_types;";
if($result = mysqli_query($con, $sql1)){
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['idmovement_types'] = $row['idmovement_types'];
        $res[$i]['name'] = $row['name'];
        $res[$i]['description'] = $row['description'];
        $i++;
    }
    echo(json_encode($res));
}else{
    echo(mysqli_error($con));
}
?>