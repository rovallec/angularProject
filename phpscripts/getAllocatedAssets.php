<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$res[] = [];
$i = 0;

$sql1 = "SELECT asset_type.*, asset_manufactures.*, asset_movements.*, assets.* , asset_type.name AS `type_name`, assets.status AS `st`,
        CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, profiles.second_lastname) AS `employee_name`, accounts.name AS `ac_name`
        FROM assets
        INNER JOIN asset_type ON asset_type.idasset_type = assets.id_type
        INNER JOIN asset_manufactures ON asset_manufactures.idasset_manufactures = assets.id_manufacture
        LEFT JOIN asset_movements ON asset_movements.idasset_movements = assets.idassets AND asset_movements.status = 1
        INNER JOIN employees ON employees.idemployees = asset_movements.id_employee
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        INNER JOIN accounts ON accounts.idaccounts = employees.id_account
        WHERE asset_movements.idasset_movements IS NOT NULL;";
if($result = mysqli_query($con, $sql1)){
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['idassets'] = $row['idassets'];
        $res[$i]['id_type'] = $row['id_type'];
        $res[$i]['id_manufacture'] = $row['id_manufacture'];
        $res[$i]['code'] = $row['code'];
        $res[$i]['cost'] = $row['cost'];
        $res[$i]['serial'] = $row['serial'];
        $res[$i]['status'] = $row['st'];
        $res[$i]['type_name'] = $row['type_name'];
        $res[$i]['brand'] = $row['brand'];
        $res[$i]['model'] = $row['model'];
        $res[$i]['employee_name'] = $row['employee_name'];
        $res[$i]['date'] = $row['date'];
        $res[$i]['account'] = $row['ac_name'];
        $i++;
    }
    echo(json_encode($res));
}else{
    echo(mysqli_error($con));
}
?>