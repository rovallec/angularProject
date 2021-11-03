<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "hr_exceptions.csv" . '"');
require 'database.php';

echo "\xEF\xBB\xBF";

$idemployee = $_GET['idemployee'];
$exportRow = [];
$sql = "SELECT
          h.nearsol_id,
          e.client_id,
          (e.base_payment + e.productivity_payment) as totalsalary,
          a2.amount,
          hp.`date` AS datepetition,
          hp.`date` AS dateapproval,
          p2.`end` datepayment
        FROM employees e
        LEFT JOIN hires h ON h.idhires = e.id_hire
        INNER JOIN accounts a ON a.idaccounts = e.id_account
        LEFT JOIN profiles p ON p.idprofiles = h.id_profile
        INNER JOIN users u ON u.idUser = e.reporter
        INNER JOIN (SELECT UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) as name, p1.idprofiles from profiles p1) p2 on (p2.idprofiles = p.idprofiles)
        INNER JOIN hr_processes hp ON (e.idemployees = hp.id_employee and hp.id_type = 10)
        INNER JOIN advances a2 ON (hp.idhr_processes = a2.id_process)
        left join periods p2 ON (hp.`date` between p2.`start` and p2.`end` and p2.type_period = 0)
        WHERE e.idemployees = $idemployee;";

$output = fopen("php://output", "w");
fputcsv($output, array("NERSOL ID", "CLIENT ID", "SALARIO TOTAL", "MONTO DEL ADELANTO", "FECHA DE PETICION", "FECHA DE APROVACIÓN", "FECHA DE DESEMBOLSO"));
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        fputcsv($output, $row);
    };
}else{
    http_response_code(404);
}
fclose($output);
?>