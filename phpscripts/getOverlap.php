<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$st = ($request->start);
$nd = ($request->end);
$res = [];
$i = 0;

$sql = "SELECT hires.nearsol_id, accounts.name AS `acc`, profiles.idprofiles, employees.idemployees, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname ,employees.client_id,`tmp`.id_employee, count(`tmp`.idattendences) AS `cnt`, group_concat(`tmp`.date) AS `dates` FROM 
(SELECT attendences.* FROM attendences
LEFT JOIN hr_processes ON attendences.id_employee = hr_processes.id_employee
INNER JOIN vacations ON vacations.id_process = hr_processes.idhr_processes AND attendences.date = vacations.date AND vacations.count > 0.5
WHERE attendences.worked_time > 0 AND attendences.date BETWEEN '$st' AND '$nd' AND hr_processes.status = 'PENDING'
UNION
SELECT attendences.* FROM attendences
LEFT JOIN hr_processes ON attendences.id_employee = hr_processes.id_employee
INNER JOIN leaves ON attendences.date BETWEEN leaves.start AND leaves.end AND leaves.id_process = hr_processes.idhr_processes
WHERE attendences.worked_time > 0 AND attendences.date BETWEEN '$st' AND '$nd' AND hr_processes.status = 'PENDING') as `tmp`
INNER JOIN employees ON employees.idemployees = `tmp`.id_employee
INNER JOIN hires ON hires.idhires = employees.id_hire
INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
INNER JOIN accounts ON accounts.idaccounts = employees.id_account
GROUP BY `tmp`.id_employee;";

if($request = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($request)){
        $res[$i]['id_profile'] = $row['idprofiles'];
        $res[$i]['idemployees'] = $row['idemployees'];
        $res[$i]['account'] = $row['acc'];
        $res[$i]['name'] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $res[$i]['client_id'] = $row['client_id'];
        $res[$i]['nearsol_id'] = $row['nearsol_id'];
        $res[$i]['job'] = $row['dates'];
        $res[$i]['state'] = $row['cnt'];
        $i++;
    }
    echo(json_encode($res));
}
?>