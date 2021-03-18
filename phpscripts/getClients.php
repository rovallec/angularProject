<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;
$sql = "SELECT * FROM clients;";
if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['idclients'] = $row['idclients'];
        $res[$i]['name'] = $row['name'];
        $res[$i]['description'] = $row['description'];
        $i++;
    }
    echo(json_encode($res));
}
?>