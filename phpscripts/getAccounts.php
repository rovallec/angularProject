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
        $res[$i]['id_client'] = $row['id_client'];
        $res[$i]['correlative'] = $row['correlative'];
        $res[$i]['prefix'] = $row['prefix'];
        $i++;
    }
    echo(json_encode($res));
}
?>