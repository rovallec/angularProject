<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "payroll_values.csv" . '"');
require 'database.php';

$filter = $_GET['filter'];
$value = $_GET['value'];
$active = $_GET['active'];

$exportRow = [];
$i = 0;

$output = fopen("php://output", "w");

if($filter == 'explicit'){
    fputcsv($output, array('NEARSOL ID', 'CLIENT ID', 'PUESTO', 'CUENTA', 'NOMBRE COMPLETO', 'FECHA DE INGRESO', 'ACUMULADAS', 'GOZADAS', 'DISPONIBLES', 'TIPO'));
    $sql = "SELECT nearsol_id, client_id, job, accounts.name, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `full_name`,
    employees.hiring_date, COALESCE((DATEDIFF(COALESCE(`vac`.date, NOW()), employees.hiring_date)/365)*15,0) AS `acumulated`, COALESCE(`vac`.count,0) AS `enjoyed`,
    `vac`.date FROM
    employees
    INNER JOIN hires ON hires.idhires = employees.id_hire
    INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
    INNER JOIN accounts ON accounts.idaccounts = employees.id_account
    LEFT JOIN (SELECT vacations.*, hr_processes.id_employee FROM 
               hr_processes
               LEFT JOIN vacations ON vacations.id_process = hr_processes.idhr_processes WHERE id_type = 4) AS `vac` ON `vac`.id_employee = employees.idemployees
    WHERE `$filter` = '$value';";
}else{
    $sql = "SELECT nearsol_id, client_id, job, accounts.name, CONCAT(profiles.first_name, ' ', profiles.second_name, ' ', profiles.first_lastname, ' ', profiles.second_lastname) AS `full_name`,
        employees.hiring_date, COALESCE((DATEDIFF(NOW(), employees.hiring_date)/365)*15,0) AS `acumulated`, COALESCE(`vac`.`cnt`,0) AS `enjoyed`,
        (((DATEDIFF(NOW(), employees.hiring_date)/365)*15) - COALESCE(`vac`.`cnt`,0)) AS `available` FROM
        employees
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        INNER JOIN accounts ON accounts.idaccounts = employees.id_account
        LEFT JOIN (SELECT SUM(vacations.count) AS `cnt`, id_employee FROM vacations 
		   INNER JOIN hr_processes ON hr_processes.idhr_processes = vacations.id_process
           WHERE vacations.action = 'TAKE'
		   GROUP BY id_employee) AS `vac`
	    ON `vac`.id_employee = employees.idemployees
        WHERE `$filter` = '$value'";
    if($active == '1'){
        $sql = $sql . " AND employees.active = 1";
    }
}

if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        fputcsv($output, $row);
    };
}else{
    http_response_code(404);
}
fclose($output);
?>