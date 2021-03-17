<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "payroll_values.csv" . '"');
require 'database.php';

$id_period = $_GET['id_period'];

$exportRow = [];
$i = 0;

$sql = "SELECT hires.nearsol_id, employees.client_id, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `full_name`, 
        users.user_name, accounts.name, employees.society, payroll_values.discounted_days, payroll_values.seventh, (payroll_values.discounted_days + payroll_values.seventh) AS `total`,
        payroll_values.discounted_hours, payroll_values.ot_hours, payroll_values.holidays_hours, payroll_values.performance_bonus, payroll_values.treasure_hunt, 
        IF(employees.active = 1, 'ACTIVE', 'INACTIVE') AS `status` FROM payroll_values
        INNER JOIN payments ON payments.idpayments = payroll_values.id_payment
        INNER JOIN employees ON employees.idemployees = payments.id_employee
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        INNER JOIN accounts ON accounts.idaccounts  = payroll_values.id_account
        INNER JOIN users ON users.idUser = payroll_values.id_reporter
        WHERE payroll_values.id_period = $id_period";

$output = fopen("php://output", "w");
fputcsv($output, array('ID', 'CLIENT ID', 'Full Name', 'Supervisor', 'Are', 'Company', 'Days to Discount', '7th', 'Total', 'Hours To Discount', 'OT', 'Holidays Hours', 'Performance Bonus', 'Treasure Hunt', 'Status'));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        fputcsv($output, $row);
    };
}else{
    http_response_code(404);
}
fclose($output);
?>