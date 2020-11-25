<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$date = ($request->date);
$account = ($request->account);


$res = [];
$i = 0;

$sql = "select * FROM (select employees.*, coalesce(`tmp`.idattendences, 0) as `exist` from employees left join (SELECT * from attendences where date = '$date') as `tmp` on `tmp`.id_employee = employees.idemployees WHERE id_account = $account) as `tmp2` left join hires on hires.idhires = `tmp2`.id_hire left join profiles on profiles.idprofiles = hires.id_profile where `exist` = 0 AND hiring_date <= '$date';";

if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['id_profile'] = $row['id_profile'];
        $res[$i]['idemployees'] = $row['idemployees'];
        $res[$i]['id_hire'] = $row['id_hire'];
        $res[$i]['name'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $res[$i]['id_account'] = $row['id_account'];
        $res[$i]['reporter'] = $row['reporter'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $i++;
    }
    echo(json_encode($res));
}
?>