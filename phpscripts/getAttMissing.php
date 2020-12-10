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

$sql = "SELECT distinct *  FROM (select `tmp2`.*, `tmp2`.state as `status`, hr_processes.date, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, hires.id_profile, hires.nearsol_id FROM (select employees.*, coalesce(`tmp`.idattendences, 0) as `exist` from employees 
left join (SELECT * from attendences where date = '$date') as `tmp` on `tmp`.id_employee = employees.idemployees WHERE id_account = $account) as `tmp2`
left join hires on hires.idhires = `tmp2`.id_hire left join profiles on profiles.idprofiles = hires.id_profile
left join hr_processes on hr_processes.id_employee = `tmp2`.idemployees  AND id_type = 8) AS `fin`
where (`fin`.`exist` = 0 AND `fin`.hiring_date <= '$date') AND (`fin`.active = 1 OR `fin`.date >= '$date');";

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
        $res[$i]['status'] = $row['status'];
        $i++;
    }
    echo(json_encode($res));
}
?>