<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "igss.csv" . '"');
require 'database.php';

$period = $_GET['period'];

$exportRow = [];
$i = 0;

$sql = "SELECT hires.nearsol_id, CONCAT(UCASE(profiles.first_name), ' ', UCASE(profiles.second_name), ' ',UCASE(profiles.first_lastname), ' ',UCASE(profiles.second_lastname)) AS `name`,
               debits.amount, profiles.iggs, profiles.dpi, employees.hiring_date, `term`.valid_from, profiles.day_of_birth FROM `debits`
        INNER JOIN payments ON payments.idpayments = debits.id_payment
        INNER JOIN employees ON employees.idemployees = payments.id_employee
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        LEFT JOIN (SELECT valid_from, id_employee FROM terminations
           INNER JOIN hr_processes ON hr_processes.idhr_processes = terminations.id_process LIMIT 1) AS `term` ON `term`.id_employee = employees.idemployees
        WHERE id_period = $period AND debits.type = 'Descuento IGSS'";

$output = fopen("php://output", "w");
fputcsv($output, array("No.", "Codigo", "Nombre", "Monto", "Afiliacion IGSS", "DPI", "Fecha de Ingreso", "Fecha de Baja", "Fecha de Nacimiento"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $exportRow[0] = $i;
        $exportRow[1]=$row['nearsol_id'];
        $exportRow[2]=$row['name'];
        $exportRow[5]=$row['amount'];
        $exportRow[6]=$row['iggs'];
        $exportRow[7]=$row['dpi'];
        $exportRow[8]=$row['hiring_date'];
        $exportRow[9]=$row['valid_from'];
        $exportRow[10]=$row['day_of_birth'];
        fputcsv($output, $exportRow);
        $i++;
    };
}else{
    http_response_code(404);
}
fclose($output);
?>