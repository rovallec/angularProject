<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "dailyConvergent.csv" . '"');
require 'database.php';

$start = $_GET['from'];
$end = $_GET['to'];
$accounts = $_GET['accounts'];

$spliter = [];
$exportRow = [];

$sql = "SELECT accounts.name AS `acc_name`, hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'IGSS' AS `type_of_payment`, DATE_FORMAT(attendences.date, '%Y/%m/%d'), attendence_adjustemnt.start, attendence_adjustemnt.end, attendence_adjustemnt.amount
FROM
	attendence_justifications
    INNER JOIN attendence_adjustemnt ON attendence_adjustemnt.id_justification = attendence_justifications.idattendence_justifications
    INNER JOIN attendences ON attendences.idattendences = attendence_adjustemnt.id_attendence
    INNER JOIN employees ON employees.idemployees = attendences.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
    INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process
WHERE hr_processes.date BETWEEN '$start' AND '$end' AND hr_processes.id_department != 28 AND attendence_justifications.reason = 'IGSS' AND employees.id_account IN ($accounts)

UNION

SELECT accounts.name AS `acc_name`, hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'Seguro' AS `type_of_payment`, DATE_FORMAT(attendences.date, '%Y/%m/%d'), attendence_adjustemnt.start, attendence_adjustemnt.end, attendence_adjustemnt.amount
FROM
	attendence_justifications
    INNER JOIN attendence_adjustemnt ON attendence_adjustemnt.id_justification = attendence_justifications.idattendence_justifications
    INNER JOIN attendences ON attendences.idattendences = attendence_adjustemnt.id_attendence
    INNER JOIN employees ON employees.idemployees = attendences.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
    INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process
WHERE hr_processes.date BETWEEN '$start' AND '$end' AND hr_processes.id_department != 28 AND attendence_justifications.reason = 'Private Doctor' AND employees.id_account IN ($accounts)

UNION

SELECT accounts.name AS `acc_name`, hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'VAC' AS `type_of_payment`, DATE_FORMAT(vacations.date, '%Y/%m/%d'), ' ', ' ', ' '
FROM
	vacations
    INNER JOIN hr_processes ON hr_processes.idhr_processes = vacations.id_process
    INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
    INNER JOIN accounts ON accounts.idaccounts = employees.id_account
WHERE ((hr_processes.date BETWEEN '$start' AND '$end')
	  OR (vacations.date BETWEEN '$start' AND '$end'))
      AND (hr_processes.id_department != 28 AND hr_processes.id_type = 4 AND hr_processes.status = 'PENDING') AND employees.id_account IN ($accounts)

UNION

SELECT accounts.name AS `acc_name`, hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'JANP' AS `type_of_payment`, DATE_FORMAT(attendences.date, '%Y/%m/%d'), ' ', ' ', ' '
FROM
	leaves
	INNER JOIN hr_processes ON hr_processes.idhr_processes = leaves.id_process
    INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
	INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    INNER JOIN attendences ON attendences.id_employee = employees.idemployees AND attendences.date BETWEEN leaves.start AND leaves.end
WHERE ((hr_processes.date BETWEEN '$start' AND '$end')
	  OR (leaves.start BETWEEN '$start' AND '$end')
      OR (leaves.end BETWEEN '$start' AND '$end'))
      AND (hr_processes.id_department != 28 AND hr_processes.id_type = 5 AND hr_processes.status = 'PENDING' AND leaves.motive = 'Others Unpaid') AND employees.id_account IN ($accounts)

UNION

SELECT accounts.name AS `acc_name`, hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'LOA' AS `type_of_payment`, 
DATE_FORMAT(attendences.date, '%Y/%m/%d'), ' ', ' ', ' '
FROM
	leaves
	INNER JOIN hr_processes ON hr_processes.idhr_processes = leaves.id_process
    INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
	INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    INNER JOIN attendences ON attendences.id_employee = employees.idemployees AND attendences.date BETWEEN leaves.start AND leaves.end
WHERE ((hr_processes.date BETWEEN '$start' AND '$end')
	  OR (leaves.start BETWEEN '$start' AND '$end')
      OR (leaves.end BETWEEN '$start' AND '$end'))
      AND (hr_processes.id_department != 28 AND hr_processes.id_type = 5 AND hr_processes.status = 'PENDING' AND leaves.motive = 'Leave of Absence Unpaid') AND employees.id_account IN ($accounts)

UNION

SELECT accounts.name AS `acc_name`, hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'JAP' AS `type_of_payment`, 
DATE_FORMAT(attendences.date, '%Y/%m/%d'), ' ', ' ', ' '
FROM
	leaves
	INNER JOIN hr_processes ON hr_processes.idhr_processes = leaves.id_process
    INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
	INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    INNER JOIN attendences ON attendences.id_employee = employees.idemployees AND attendences.date BETWEEN leaves.start AND leaves.end
WHERE ((hr_processes.date BETWEEN '$start' AND '$end')
	  OR (leaves.start BETWEEN '$start' AND '$end')
      OR (leaves.end BETWEEN '$start' AND '$end'))
      AND (hr_processes.id_department != 28 AND hr_processes.id_type = 5 AND hr_processes.status = 'PENDING' AND (leaves.motive = 'Others Paid' OR leaves.motive ='Maternity'))  AND employees.id_account IN ($accounts)";

$spliter = explode(',', $accounts);

foreach ($spliter as $key => $value) {
    $sql = $sql . " employees.id_account = '$value' OR";
}

$sql = $sql . " hr_processes.date BETWEEN '$from' AND '$to';";

$output = fopen("php://output", "w");
fputcsv($output, array("ACCOUNT", "NERSOL ID", "CLIENT ID", "COMPLETE NAME", " TYPE OF PAYMENT", "DATE (M/D/Y)", "START", "END", "LENGTH"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        fputcsv($output, $row);
    };
}else{
    http_response_code(404);
}
fclose($output);

?>