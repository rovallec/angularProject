<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "attendances.csv" . '"');
require 'database.php';

$account = $_GET['acc'];
$from = $_GET['from'];
$to = $_GET['to'];

$attendances = [];

$sql = "SELECT accounts.name, profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, hires.nearsol_id, employees.client_id ,attendences.*,
id_account, coalesce(SUM(`igss`.`amt`),0) AS `igss`,
coalesce(SUM(`aux`.`amt_aux`),0) AS `aux`, coalesce(SUM(`correction`.`amt_corrections`),0) AS `corrections`,
IF(COUNT(`vac`.idvacations) > 0, 'VAC', '-') AS `VAC`,
IF(COUNT(`JANP`.idleaves) > 0, 'JANP', '-') AS `JANP`,
IF(COUNT(`JAP`.idleaves) > 0, 'JAP', '-') AS `JAP`
FROM attendences
INNER JOIN employees ON employees.idemployees = attendences.id_employee
INNER JOIN hires ON hires.idhires = employees.id_hire
INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
INNER JOIN accounts ON accounts.idaccounts = employees.id_account
LEFT JOIN (SELECT SUM(amount) AS `amt`, id_employee, id_attendence from attendence_justifications
    INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process
    INNER JOIN attendence_adjustemnt ON attendence_adjustemnt.id_justification = attendence_justifications.idattendence_justifications
    INNER JOIN users ON users.idUser = hr_processes.id_user
    WHERE id_department = 5 GROUP BY id_employee, id_attendence) AS `igss` ON `igss`.id_attendence = attendences.idattendences
LEFT JOIN (SELECT SUM(amount) AS `amt_aux`, id_employee, id_attendence from attendence_justifications
    INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process
    INNER JOIN attendence_adjustemnt ON attendence_adjustemnt.id_justification = attendence_justifications.idattendence_justifications
    INNER JOIN users ON users.idUser = hr_processes.id_user
    WHERE id_department != 5 AND attendence_justifications.reason NOT IN('IGSS', 'WFM Attendance correction') GROUP BY id_employee, id_attendence) AS `aux` ON `aux`.id_attendence = attendences.idattendences
LEFT JOIN (SELECT SUM(amount) AS `amt_corrections`, id_employee, id_attendence from attendence_justifications
    INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process
    INNER JOIN attendence_adjustemnt ON attendence_adjustemnt.id_justification = attendence_justifications.idattendence_justifications
    INNER JOIN users ON users.idUser = hr_processes.id_user
    WHERE id_department != 5 AND attendence_justifications.reason IN('IGSS', 'WFM Attendance correction') GROUP BY id_employee, id_attendence) AS `correction` ON `correction`.id_attendence = attendences.idattendences
LEFT JOIN (SELECT vacations.*, hr_processes.id_employee, hr_processes.status FROM vacations
			INNER JOIN hr_processes ON hr_processes.idhr_processes = vacations.id_process
            WHERE action = 'TAKE' AND hr_processes.status = 'PENDING') AS `vac` ON `vac`.id_employee = attendences.id_employee AND `vac`.date = attendences.date
LEFT JOIN (SELECT leaves.*, hr_processes.id_employee, hr_processes.status from leaves
		   INNER JOIN hr_processes ON hr_processes.idhr_processes = leaves.id_process
           WHERE hr_processes.status = 'PENDING' AND (leaves.motive = 'Leave of Absence Unpaid' OR leaves.motive = 'Others Unpaid')) AS `JANP`
           ON `JANP`.id_employee = attendences.id_employee AND attendences.date BETWEEN `JANP`.start AND `JANP`.end
LEFT JOIN (SELECT leaves.*, hr_processes.id_employee, hr_processes.status from leaves
		   INNER JOIN hr_processes ON hr_processes.idhr_processes = leaves.id_process
           WHERE hr_processes.status = 'PENDING' AND (leaves.motive = 'Maternity' OR leaves.motive = 'Others Paid')) AS `JAP`
           ON `JAP`.id_employee = attendences.id_employee AND attendences.date BETWEEN `JAP`.start AND `JAP`.end
WHERE attendences.date BETWEEN '$from' AND '$to' AND id_account in($account)
GROUP BY idattendences;";

$output = fopen("php://output", "w");
fputcsv($output, array("Nearsol ID", "Client ID", "Name", "Account", "Date", "Roster", "Worked", "IGSS", "AUX", "CORRECTION", "VAC", "JANP", "JAP"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $attendances[0] = $row['nearsol_id'];
        $attendances[1] = $row['client_id'];
        $attendances[2] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $attendances[3] = $row['name'];
        $attendances[4] = str_replace("-",".",$row['date']);
        $attendances[5] = $row['scheduled'];
        $attendances[6] = $row['worked_time'];
        $attendances[7] = $row['igss'];
        $attendances[8] = $row['aux'];
        $attendances[9] = $row['corrections'];
        $attendances[10] = $row['VAC'];
        $attendances[11] = $row['JANP'];
        $attendances[12] = $row['JAP'];
        fputcsv($output, $attendances, ",");
    };
}else{
    http_response_code(404);
}
fclose($output);
?>