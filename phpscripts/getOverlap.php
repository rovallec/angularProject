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

$sql = "SELECT hires.nearsol_id, accounts.name AS `acc`,
profiles.idprofiles, employees.idemployees, profiles.first_name, profiles.second_name, profiles.first_lastname,
profiles.second_lastname ,employees.client_id,`tmp`.id_employee, count(`tmp`.idattendences) AS `cnt`, group_concat(`tmp`.date) AS `dates` FROM
(SELECT attendences.* FROM attendences
LEFT JOIN hr_processes ON attendences.id_employee = hr_processes.id_employee
INNER JOIN vacations ON vacations.id_process = hr_processes.idhr_processes AND attendences.date = vacations.date AND vacations.count > 0.5
WHERE attendences.worked_time > 0 AND attendences.date BETWEEN '$st' AND '$nd' AND hr_processes.status = 'PENDING'
UNION
SELECT attendences.* FROM attendences
LEFT JOIN hr_processes ON attendences.id_employee = hr_processes.id_employee
INNER JOIN leaves ON attendences.date BETWEEN leaves.start AND leaves.end AND leaves.id_process = hr_processes.idhr_processes
WHERE attendences.worked_time > 0 AND attendences.date BETWEEN '$st' AND '$nd' AND hr_processes.status = 'PENDING'
UNION
SELECT idattendences, id_employee, date, scheduled, worked_time, id_import, 0 FROM (
SELECT attendences.idattendences, attendences.id_employee, attendences.date, attendences.id_import,
attendences.scheduled, attendences.worked_time, COUNT(leaves.idleaves) AS `cnt`, COUNT(vacations.idvacations) AS `vac_count`,
`susp`.`cnt` AS `suspensions_count`
FROM attendences
LEFT JOIN hr_processes ON attendences.id_employee = hr_processes.id_employee AND (id_type = 5 OR id_type = 4 OR id_type = 6)
LEFT JOIN leaves ON hr_processes.idhr_processes = leaves.id_process AND (attendences.date >= leaves.start AND attendences.date <= leaves.end)
LEFT JOIN vacations ON hr_processes.idhr_processes = vacations.id_process AND vacations.date = attendences.date
LEFT JOIN (
SELECT attendences.*, COUNT(idsuspensions) AS `cnt` FROM
attendences
INNER JOIN hr_processes ON hr_processes.id_employee = attendences.id_employee AND hr_processes.id_type = 6
INNER JOIN disciplinary_requests ON disciplinary_requests.id_process = hr_processes.idhr_processes
INNER JOIN disciplinary_processes ON disciplinary_processes.id_request = disciplinary_requests.iddisciplinary_requests
INNER JOIN suspensions ON suspensions.id_disciplinary_process = disciplinary_processes.iddisciplinary_processes
			AND (suspensions.day_1 = attendences.date OR suspensions.day_2 = attendences.date OR suspensions.day_3 = attendences.date OR suspensions.day_4 = attendences.date)
WHERE hr_processes.status = 'DISPATCHED'
GROUP BY idattendences) AS `susp` ON `susp`.idattendences = attendences.idattendences
WHERE attendences.date BETWEEN '$st' AND '$nd' AND (hr_processes.status = 'PENDING') GROUP BY attendences.idattendences) AS `leaves`
WHERE `cnt` > 1 OR `vac_count` > 1 OR `suspensions_count` > 1 OR (`vac_count` > 0 AND `cnt` > 0) OR (`vac_count` > 0 AND `suspensions_count` > 0) OR (`suspensions_count` > 0 AND `cnt` > 0)
) as `tmp`
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
}else{
    echo(json_encode($sql));
}
?>
