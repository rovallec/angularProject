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
'IGSS' AS `type_of_payment`, DATE_FORMAT(attendences.date, '%Y/%m/%d'), attendence_adjustemnt.start, attendence_adjustemnt.end, attendence_adjustemnt.amount, hr_processes.notes
FROM
	attendence_justifications
    INNER JOIN attendence_adjustemnt ON attendence_adjustemnt.id_justification = attendence_justifications.idattendence_justifications
    INNER JOIN attendences ON attendences.idattendences = attendence_adjustemnt.id_attendence
    INNER JOIN employees ON employees.idemployees = attendences.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
    INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process
    WHERE ((hr_processes.date BETWEEN '$start' AND '$end') OR (attendences.date BETWEEN '$start' AND '$end')) AND (hr_processes.id_department != 28 AND attendence_justifications.reason = 'IGSS')

UNION

SELECT accounts.name AS `acc_name`, hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'Seguro' AS `type_of_payment`, DATE_FORMAT(attendences.date, '%Y/%m/%d'), attendence_adjustemnt.start, attendence_adjustemnt.end, attendence_adjustemnt.amount, hr_processes.notes
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
'CERT' AS `type_of_payment`, DATE_FORMAT(attendences.date, '%Y/%m/%d'), attendence_adjustemnt.start, attendence_adjustemnt.end, attendence_adjustemnt.amount, hr_processes.notes
FROM
	attendence_justifications
    INNER JOIN attendence_adjustemnt ON attendence_adjustemnt.id_justification = attendence_justifications.idattendence_justifications
    INNER JOIN attendences ON attendences.idattendences = attendence_adjustemnt.id_attendence
    INNER JOIN employees ON employees.idemployees = attendences.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
    INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process
WHERE hr_processes.date BETWEEN '$start' AND '$end' AND hr_processes.id_department != 28 AND attendence_justifications.reason IN ('Marriage Certificate', 'Death Certificate', 'Birth Certificate') AND employees.id_account IN($accounts);

UNION

SELECT accounts.name AS `acc_name`, hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'VAC' AS `type_of_payment`, DATE_FORMAT(vacations.date, '%Y/%m/%d'), ' ', ' ', ' ', CONCAT(hr_processes.notes,' DIAS GOZADOS: ', vacations.count)
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
'JANP' AS `type_of_payment`, DATE_FORMAT(`dt`.`dates`, '%Y/%m/%d'), ' ', ' ', ' ', hr_processes.notes
FROM
	leaves
	INNER JOIN hr_processes ON hr_processes.idhr_processes = leaves.id_process
    INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
	INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    INNER JOIN (
		select date_add('$start', interval `row` day) AS `dates` from
		( 
			SELECT @rowa := @rowa + 1 as `row` FROM 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t,
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t2, 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t3, 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t4, 
			(SELECT @rowa:=-1) r
		) sequence
		where date_add('$start', interval `row` day) <= '$end'
    ) AS `dt` ON `dt`.`dates` BETWEEN leaves.start AND leaves.end
WHERE ((hr_processes.date BETWEEN '$start' AND '$end')
	  OR (leaves.start BETWEEN '$start' AND '$end')
      OR (leaves.end BETWEEN '$start' AND '$end'))
      AND (`dt`.`dates` BETWEEN '$start' AND '$end')
      AND (hr_processes.id_department != 28 AND hr_processes.id_type = 5 AND hr_processes.status = 'PENDING' AND leaves.motive = 'Others Unpaid') AND employees.id_account IN ($accounts)

UNION

SELECT accounts.name AS `acc_name`, hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'LOA' AS `type_of_payment`, 
DATE_FORMAT(`dt`.`dates`, '%Y/%m/%d'), ' ', ' ', ' ', hr_processes.notes
FROM
	leaves
	INNER JOIN hr_processes ON hr_processes.idhr_processes = leaves.id_process
    INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
	INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    INNER JOIN (
		select date_add('$start', interval `row` day) AS `dates` from
		( 
			SELECT @rowb := @rowb + 1 as `row` FROM 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t,
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t2, 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t3, 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t4, 
			(SELECT @rowb:=-1) r
		) sequence
		where date_add('$start', interval `row` day) <= '$end'
    ) AS `dt` ON `dt`.`dates` BETWEEN leaves.start AND leaves.end
WHERE ((hr_processes.date BETWEEN '$start' AND '$end')
	  OR (leaves.start BETWEEN '$start' AND '$end')
      OR (leaves.end BETWEEN '$start' AND '$end')      
      OR (leaves.end > '$start')
      OR (leaves.start < '$end'))
      AND (`dt`.`dates` between '$start' AND '$end')
      AND (hr_processes.id_department != 28 AND hr_processes.id_type = 5 AND hr_processes.status = 'PENDING' AND leaves.motive = 'Leave of Absence Unpaid') AND employees.id_account IN ($accounts)

UNION

SELECT accounts.name AS `acc_name`, hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'JAP' AS `type_of_payment`, 
DATE_FORMAT(`dt`.`dates`, '%Y/%m/%d'), ' ', ' ', ' ', hr_processes.notes
FROM
	leaves
	INNER JOIN hr_processes ON hr_processes.idhr_processes = leaves.id_process
    INNER JOIN employees ON employees.idemployees = hr_processes.id_employee
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
	INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    INNER JOIN (
		select date_add('$start', interval `row` day) AS `dates` from
		( 
			SELECT @rowc := @rowc + 1 as `row` FROM 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t,
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t2, 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t3, 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t4, 
			(SELECT @rowc:=-1) r
		) sequence
		where date_add('$start', interval `row` day) <= '$end'
    ) AS `dt` ON `dt`.`dates` BETWEEN leaves.start AND leaves.end
WHERE ((hr_processes.date BETWEEN '$start' AND '$end')
	  OR (leaves.start BETWEEN '$start' AND '$end')
      OR (leaves.end BETWEEN '$start' AND '$end')      
      OR (leaves.end > '$start')
      OR (leaves.start < '$end'))
      AND (`dt`.`dates` between '$start' AND '$end')
      AND (hr_processes.id_department != 28 AND hr_processes.id_type = 5 AND hr_processes.status = 'PENDING' AND (leaves.motive = 'Others Paid' OR leaves.motive ='Maternity'))  AND employees.id_account IN ($accounts)

UNION

SELECT accounts.name AS `acc_name`, hires.nearsol_id, `emp`.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `name`,
'SUSPENSION' AS `type_of_payment`, 
DATE_FORMAT(`dt`.`dates`, '%Y/%m/%d'), ' ', ' ', ' ', hr_processes.notes
FROM
    suspensions
    INNER JOIN disciplinary_processes ON disciplinary_processes.iddisciplinary_processes = suspensions.id_disciplinary_process
    INNER JOIN disciplinary_requests ON disciplinary_requests.iddisciplinary_requests = id_request
    INNER JOIN hr_processes ON hr_processes.idhr_processes = disciplinary_requests.id_process
	INNER JOIN (SELECT * FROM employees WHERE id_account IN($accounts)) AS `emp` ON `emp`.idemployees = hr_processes.id_employee
    INNER JOIN hires ON hires.idhires = `emp`.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
	INNER JOIN accounts ON accounts.idaccounts = `emp`.id_account
   INNER JOIN (
		select date_add('$start', interval `row` day) AS `dates` from
		( 
			SELECT @rowd := @rowd + 1 as `row` FROM 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t,
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t2, 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t3, 
			(select 0 union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) t4, 
			(SELECT @rowd:=-1) r
		) sequence
		where date_add('$start', interval `row` day) <= '$end'
    ) AS `dt` ON `dt`.`dates` = suspensions.day_1 OR `dt`.`dates` = suspensions.day_2 OR `dt`.`dates` = suspensions.day_3 OR `dt`.`dates` = suspensions.day_4
WHERE (hr_processes.date BETWEEN '$start' AND '$end') OR (`dt`.`dates` BETWEEN '$start' AND '$end') AND `emp`.id_account IN ($accounts)";

$output = fopen("php://output", "w");
fputcsv($output, array("ACCOUNT", "NERSOL ID", "CLIENT ID", "COMPLETE NAME", " TYPE OF PAYMENT", "DATE (M/D/Y)", "START", "END", "LENGTH", "NOTES"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        fputcsv($output, $row);
    };
}else{
    http_response_code(404);
}
fclose($output);

?>