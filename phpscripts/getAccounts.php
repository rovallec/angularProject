<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;
$sql = "Select * from `accounts`;";
if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['idaccounts'] = $row['idaccounts'];
        $res[$i]['name'] = $row['name'];
        $i++;
    }
    echo(json_encode($res));
}
?>