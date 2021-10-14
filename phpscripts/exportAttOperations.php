<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "dailyConvergent.csv" . '"');
require 'database.php';

$wave = $_GET['wave'];

$i = 1;

$sql = "SELECT hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname, users.user_name, users.user_name) AS `name`,
        IF(employees.active = '1', 'ACTIVE', employees.termination_date) AS `termination`, SUM(`justifications`.`sch`) `schedule`,
        SUM(CASE WHEN `justifications`.reason = 'Schedule FIX' THEN `justifications`.`time` ELSE 0 END) AS `schedule_fix`, SUM(IF(reason = 'Time in Aux 0', `justifications`.`time`, '0')) AS `aux_0`,
        SUM(CASE WHEN `justifications`.reason = 'Time in System Issues' THEN `justifications`.`time` ELSE '0' END) AS `system_issues`,
        SUM(CASE WHEN `justifications`.reason = 'Time in lunch' THEN `justifications`.`time` ELSE '0' END) AS `lunch`,
        SUM(CASE WHEN `justifications`.reason = 'Break Abuse' THEN `justifications`.`time` ELSE '0' END) AS `abuse`,
        SUM(CASE WHEN (`justifications`.id_department = 5 OR `justifications`.id_department = 27) THEN `justifications`.`time` ELSE '0' END) AS `igss`,
        SUM(CASE WHEN `justifications`.reason = 'Exceptions Meeting & feedback' THEN `justifications`.`time` ELSE '0' END) AS `feedback`,
        SUM(CASE WHEN `justifications`.reason = 'Exceptions offline Training' THEN `justifications`.`time` ELSE '0' END) AS `offline_training`,
        SUM(CASE WHEN `justifications`.reason = 'System Issues by Sup' THEN `justifications`.`time` ELSE '0' END) AS `sup_system_issues`,
        SUM(CASE WHEN `justifications`.reason = 'Floor Support' THEN `justifications`.`time` ELSE '0' END) AS `floor_support`,
        SUM(CASE WHEN `justifications`.reason = 'TIME TRAINING' THEN `justifications`.`time` ELSE '0' END) AS `training_time`
        FROM employees
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        INNER JOIN accounts ON accounts.idaccounts = employees.id_account
        INNER JOIN attendences ON attendences.id_employee = employees.idemployees
        INNER JOIN users ON users.idUser = employees.reporter
        LEFT JOIN (SELECT id_attendence, IF(reason = 'Schedule FIX', SUM(time_after), SUM(amount)) AS `time`, IF(reason = 'Schedule FIX', SUM(time_before), '0') AS `sch`, id_department, reason FROM attendence_adjustemnt
            INNER JOIN attendence_justifications ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification
            INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process
            GROUP BY id_attendence, reason, id_department) AS `justifications` ON `justifications`.id_attendence = attendences.idattendences
        WHERE attendences.date BETWEEN '$start' AND '$end' AND accounts.id_account = $account
        GROUP BY employees.idemployees;";
$output = fopen("php://output", "w");
fputcsv($output, array("No.", "First Name", "Middle Name", "First Last Name", "Second Last Name", "Recruiter", "Operations", "Hired Date", "DPI/ID", "Phone Number"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $row['id_wave'] = $i;
        fputcsv($output, $row, ",");
        $i = $i++;
    };
}else{
    http_response_code(404);
}
fclose($output);
?>