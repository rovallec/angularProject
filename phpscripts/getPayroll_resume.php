<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->id_period);
$id_account = ($request->id_account);

$res = [];
$i = 0;

$sql = "SELECT * FROM payroll_resume WHERE id_period = $id AND account = $id_account;";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $res[$i]['idpayroll_resume'] = $row['idpayroll_resume'];
        $res[$i]['id_employee'] = $row['id_employee'];
        $res[$i]['id_period'] = $row['id_period'];
        $res[$i]['account'] = $row['account'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['name'] = $row['name'];
        $res[$i]['vacations'] = $row['vacations'];
        $res[$i]['janp'] = $row['janp'];
        $res[$i]['jap'] = $row['jap'];
        $res[$i]['igss'] = $row['igss'];
        $res[$i]['igss_hrs'] = $row['igss_hrs'];
        $res[$i]['insurance'] = $row['insurance'];
        $res[$i]['other_hrs'] = $row['other_hrs'];
        $i++;
    }
    echo(json_encode($res));
}
?>