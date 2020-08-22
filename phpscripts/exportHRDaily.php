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
$exportRow = [];

$sql = "SELECT accounts.name AS acName, employees.id_account, employees.client_id, hr_processes.date AS applyedDate,  profiles.first_name, profiles.second_name, profiles.first_lastname, profiles.second_lastname, hires.nearsol_id, attendences.date, attendence_justifications.reason, attendence_adjustemnt.time_before, attendence_adjustemnt.time_after, attendence_adjustemnt.amount FROM attendence_adjustemnt LEFT JOIN attendence_justifications ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification LEFT JOIN attendences ON attendences.idattendences = attendence_adjustemnt.id_attendence LEFT JOIN employees ON employees.idemployees = attendences.id_employee LEFT JOIN hires ON hires.idhires = employees.id_hire LEFT JOIN profiles ON profiles.idprofiles = hires.id_profile LEFT JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process LEFT JOIN accounts ON accounts.idaccounts = employees.id_account WHERE";

$spliter = explode(',', $accounts);

foreach ($spliter as $key => $value) {
    $sql = $sql . " employees.id_account = '$value' OR";
}

$sql = $sql . " hr_processes.date BETWEEN '$from' AND '$to';";

$output = fopen("php://output", "w");
fputcsv($output, array("Account", "Client ID", "Date", "Name", "Nearsol ID", "Attendence", "Type", "Time Before", "Time After", "Makeup"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $exportRow[0] = $row['acName'];
        $exportRow[1] = $row['client_id'];
        $exportRow[2] = $row['applyedDate'];
        $exportRow[3] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $exportRow[4] = $row['nearsol_id'];
        $exportRow[5] = $row['date'];
        $exportRow[6] = $row['reason'];
        $exportRow[7] = $row['time_before'];
        $exportRow[8] = $row['time_after'];
        $exportRow[9] = $row['amount'];
        fputcsv($output, $exportRow, ",");
    }
}else{
    http_response_code(404);
}
fclose($output);

?>