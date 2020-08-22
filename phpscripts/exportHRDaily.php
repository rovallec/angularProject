<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "dailyConvergent.csv" . '"');
require 'database.php';

$from = $_GET['from'];
$to = $_GET['to'];
$accounts = $_GET['accounts'];

$spliter = [];

$sql = "SELECT employees.id_account, employees.client_id, hr_processes.date,  profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, hires.nearsol_id, attendences.date, attendence_justifications.reason, attendence_adjustemnt.time_before, attendence_adjustemnt.time_after FROM attendence_adjustemnt LEFT JOIN attendence_justifications ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification LEFT JOIN attendences ON attendences.idattendences = attendence_adjustemnt.id_attendence LEFT JOIN employees ON employees.idemployees = attendences.id_employee LEFT JOIN hires ON hires.idhires = employees.id_hire LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile LEFT JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process WHERE";

$spliter = explode(',', $accounts);

for ($i=0; $i < count($spliter) ; $i++) {
    $account = $accounts[$i];
    $sql = $sql . " employees.id_account = '$account' AND"
}

$sql = $sql . " hr_processes.date BETWEEN '$from' AND '$to';"
echo($sql);
?>