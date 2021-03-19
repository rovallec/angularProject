<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "paid_attendances.csv" . '"');
require 'database.php';

$id_period = $_GET['id_period'];

$exportRow = [];
$i = 0;

$sql = "SELECT hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ',
        profiles.first_lastname, ' ', profiles.second_lastname) AS `full_name`, accounts.name,
        IF(employees.active = 1, 'ACTIVE', 'INACTIVE') AS `st`, users.user_name, 
        IF(`term`.valid_from IS NULL, 'ACTIVE', `term`.valid_from) AS `termination_date`,
        paid_attendances.date, paid_attendances.scheduled, paid_attendances.worked, paid_attendances.balance
        FROM paid_attendances
        INNER JOIN payroll_values on payroll_values.idpayroll_values = paid_attendances.id_payroll_value
        INNER JOIN payments ON payments.idpayments = payroll_values.id_payment
        INNER JOIN employees ON employees.idemployees = payments.id_employee
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        INNER JOIN accounts ON accounts.idaccounts  = payroll_values.id_account
        INNER JOIN users ON users.idUser = payroll_values.id_reporter
        INNER JOIN periods ON periods.idperiods = payroll_values.id_period
        LEFT JOIN (SELECT * FROM terminations
		   INNER JOIN hr_processes on terminations.id_process = hr_processes.idhr_processes AND hr_processes.id_type = 8) AS `term` ON `term`.id_employee = employees.idemployees
        WHERE payroll_values.id_period = $id_period";

$output = fopen("php://output", "w");
fputcsv($output, array('ID', 'Nearsol ID', 'Full Name', 'Area', 'Status', 'Supervisor', 'Termination Date', 'Date', 'Roster', 'Worked', 'Balance'));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        fputcsv($output, $row);
    };
}else{
    http_response_code(404);
}
fclose($output);
?>