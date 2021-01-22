<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "attritionReport.csv" . '"');
require 'database.php';

$date = $_GET['date'];
$i = 0;

$sql = "SELECT DISTINCT " .
        "a.idemployees, " .
        "a.client_id, " .
        "UPPER(CONCAT(TRIM(c.first_name), ' ', TRIM(c.second_name), ' ', TRIM(c.first_lastname), ' ', TRIM(c.second_lastname))) as NombreDelTrabajador, " .
        "IF (e.idclients = 2, 'ADMINISTRATION', 'OPERATIONS') AS JORNADA,   " .
        "d.name AS SECCION, " .
        "f.bank, " .
        "IF (f.type = 'BANK CHECK', 'CHEQUE', F.number) AS 'Transferencia/Cheque', " .
        "c.dpi, " .
        "c.iggs, " .
        "concat(h.start, ' - ', h.end) AS PERIODO, " .
        "g.base AS Salario, " .
        "ROUND(g.base_hours / 8, 0) AS 'DiasTrabajados', " .
        "g.base_hours AS 'HorasOrdinarias', " .
        "g.ot_hours AS 'HorasExtraordinarias', " .
        "g.holidays_hours AS 'HorasAsuetos', " .
        "(g.base_complete / 2) AS 'SalarioBase', " .
        "g.ot AS 'SalarioExtraordinario', " .
        "0.00 AS 'SalarioComisiones', " .
        "g.sevenths AS 'SalarioSeptimos', " .
        "g.holidays AS 'SalarioAsuetos', " .
        "ROUND(COALESCE(g.base_complete / 2, 0.00) + COALESCE(g.ot, 0.00) + 0.00 + (COALESCE(g.sevenths, 0.00)*(-1)) + COALESCE(g.holidays, 0.00), 2) AS 'SalarioTotal', " .
        "ROUND((COALESCE (g.base_hours, 0.00)-120)/(120/COALESCE(g.base_complete/2, 0.00)), 2) AS 'Ausencias', " .
        "ROUND(COALESCE(g.base_complete / 2, 0.00) + COALESCE(g.ot, 0.00) + 0.00 + (COALESCE(g.sevenths, 0.00)*(-1)) + COALESCE(g.holidays, 0.00), 2) + " .
          "ROUND((COALESCE (g.base_hours, 0.00)-120)/(120/COALESCE(g.base_complete/2, 0.00)), 2)AS 'SalarioNeto' " .
        "FROM employees a " .
        "INNER JOIN hires b ON (a.id_hire = b.idhires) " .
        "INNER JOIN profiles c ON (b.id_profile = c.idprofiles) " .
        "INNER JOIN accounts d ON (a.id_account = d.idaccounts) " .
        "INNER JOIN clients e ON (d.id_client = e.idclients) " .
        "INNER JOIN payment_methods f ON (f.id_employee = a.idemployees) " .
        "INNER JOIN payments g on (g.id_employee = a.idemployees and g.id_paymentmethod = f.idpayment_methods) " .
        "INNER JOIN periods h ON (g.id_period = h.idperiods) " .
        "WHERE d.idaccounts in(33, 13, 1) " .
        "AND h.idperiods = 25 " .
        "AND a.idemployees in(4251, 5831, 5379, 4735) " .
        "AND last_day(h.end) = last_day('2021-01-01') " .
        "ORDER BY c.first_lastname ASC, " .
        "c.second_lastname ASC, " .
        "c.first_name ASC, " .
        "c.second_lastname ASC, " .
        "h.end DESC;";




$title = ['idemployees','client_id','NombreDelTrabajador','JORNADA','SECCION','bank','Transferencia/Cheque','dpi','iggs','PERIODO','Salario','DiasTrabajados','HorasOrdinarias','HorasExtraordinarias','HorasAsuetos','SalarioBase','SalarioExtraordinario','SalarioComisiones','SalarioSeptimos','SalarioAsuetos','SalarioTotal','Ausencias','SalarioNeto'];
$output = fopen("php://output", "w");
fputcsv($output, $title);
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){
        $exportRow[0] = $i;
        $exportRow[1]=$row['first_name'] . " " . $row['second_name'] ." " . $row['first_lastname'] . " " . $row['second_lastname'];
        if($row['tittle'] == 'MR'){
            $exportRow[2] = 'Male';
        }else{
            $exportRow[2] = 'Female';
        }
        $exportRow[3] = $row['day_of_birth'];
        $exportRow[4] = $row['nearsol_id'];
        $exportRow[5] = $row['hiring_date'];
        $exportRow[6] = $row['job'];
        $exportRow[7] = $row['motive'];
        $exportRow[8] = $row['kind'];
        $exportRow[9] = $row['reason'];
        $exportRow[10] = $row['rehireable'];
        $exportRow[11] = $row['valid_from'];
        $exportRow[12] = $row['reciutment'];
        $exportRow[13] = $row['operations'];
        $exportRow[14] = $row['LOB'];
        $exportRow[15] = $row['sup'];
        $exportRow[16] = $row['experience'];
        $exportRow[17] = $row['current_level'];
        $exportRow[18] = $row['nearsol_experience'];
        $exportRow[19] = $row['supervisor_experience'];
        fputcsv($output, $exportRow);
        $i++;
    };
}else{
    http_response_code(404);
}
fclose($output);
?>