<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "roster.csv" . '"');
require 'database.php';

$roster = [];

$sql = "SELECT services.name AS sv_name, * FROM
        employees
        INNER JOIN hires ON hires.idhires = employees.id_hire
        INNER JOIN profiles ON profiles.idprofiles = hires.id_profile
        INNER JOIN internal_processes ON internal_processes.id_employee = employees.idemployees
        INNER JOIN services ON  internal_processes.idinternal_processes = services.id_process
        WHERE services.status = 1 AND services.name IN('Car Parking', 'Motorcycle Parking', 'Monthly Bus') OR services.name LIKE '%Daily Bus%'";

$output = fopen("php://output", "w");
fputcsv($output, array("NEARSOL ID", "INGRESADO", "NOMBRE", "SERVICIO", "MONTO", "FRECUENCIA"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $roster[0] = $row['nearsol_id'];
        $roster[1] = $row['date'];
        $roster[2] = $row['first_name'] . " " . $row['second_name'] . " " . $row['first_lastname'] . " " . $row['second_lastname'];
        $roster[3] = $row['sv_name'];
        $roster[4] = $row['amount'];
        $roster[5] = $row['frecuency'];
        fputcsv($output, $roster, ",");
    };
}else{
    http_response_code(404);
}
fclose($output);
?>