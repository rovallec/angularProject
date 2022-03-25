<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$res = [];
$i = 0;
$sql = "SELECT * FROM providers;";
if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['idproviders'] = $row['idproviders'];
        $res[$i]['name'] = $row['name'];
        $res[$i]['contact'] = $row['contact'];
        $res[$i]['phone'] = $row['phone'];
        $res[$i]['cel'] = $row['cel'];
        $res[$i]['email'] = $row['email'];
        $res[$i]['service'] = $row['service'];
        $res[$i]['credit'] = $row['credit'];
        $res[$i]['conditions'] = $row['conditions'];
        $res[$i]['days'] = $row['days'];
        $res[$i]['contract_start'] = $row['contract_start'];
        $res[$i]['contract_end'] = $row['contract_end'];
        $res[$i]['no_iva'] = $row['no_iva'];
        $i++;
    }
    echo(json_encode($res));
}
?>
    