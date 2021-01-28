<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "reporteBancario.csv" . '"');
require 'database.php';

echo "\xEF\xBB\xBF";
$AID_Period = $_GET['AID_Period'];
$i = 0;

$sql =  
"SELECT  " .
"  A1.Account, " .
"  A1.dpi AS 'DPI', " .
"  A1.Name, " .
"  SUM(A1.LiquidoARecibir) AS 'Mount' " .
"FROM ( " .
"  /* CREDITOS */ " .
"  SELECT DISTINCT " .
"    a.idemployees, " .
"    g.idpayments, " .
"    f.number AS 'Account', " .
"    UPPER(CONCAT(TRIM(c.first_name), ' ', TRIM(c.second_name), ' ', TRIM(c.first_lastname), ' ', TRIM(c.second_lastname))) AS 'Name', " .
"    f.bank, " .
"    IF (f.type = 'BANK CHECK', 'CHEQUE', F.number) AS 'Transferencia/Cheque', " .
"    c.dpi,          " .
"    /* SE SUMAN TODOS LOS CREDITOS Y DEBITOS */ " .
"    ROUND( " .
"    ROUND(COALESCE(g.base_complete / 2, 0.00) + COALESCE(g.ot, 0.00) + 0.00, 2) + /* Salario Base (SalarioTotal) */  " .
"    ROUND((COALESCE(g.base_hours, 0.00)-120) / (120 / COALESCE(g.base_complete / 2, 0.00)), 2) +  /* Ausencias */ " .
"    0.00 + 0.00 + 0.00 + 0.00 + 0.00 + 0.00 + 0.00 + /* DEDUCCIONES VARIAS */  " .
"    ROUND(COALESCE(l.amount, 0.00), 2) + /* BonificacionIncentivo */  " .
"    COALESCE(k.amount, 0.00), 2) AS 'LiquidoARecibir' /* OtrasBonificacioneseincentivos */     " .
"  FROM employees a " .
"  INNER JOIN hires b ON (a.id_hire = b.idhires)  " .
"  INNER JOIN profiles c ON (b.id_profile = c.idprofiles) " .
"  INNER JOIN accounts d ON (a.id_account = d.idaccounts) " .
"  INNER JOIN clients e ON (d.id_client = e.idclients) " .
"  INNER JOIN payment_methods f ON (f.id_employee = a.idemployees and f.predeterm=1) " .
"  INNER JOIN payments g on (g.id_employee = a.idemployees and g.id_paymentmethod = f.idpayment_methods) " .
"  INNER JOIN periods h ON (g.id_period = h.idperiods) " .
"  INNER JOIN credits j on (g.idpayments = j.id_payment) " .
"  INNER JOIN (SELECT c2.id_payment, SUM(ROUND(c2.amount, 2)) AS amount FROM credits c2 where (c2.TYPE NOT IN('Bonificacion Decreto', 'Anticipo Sobre Sueldo', 'Salario Base') AND c2.TYPE NOT LIKE'%Horas%Extra%Laboradas%') GROUP BY c2.id_payment) k on (g.idpayments = k.id_payment) " .
"  INNER JOIN credits l on (g.idpayments = l.id_payment and l.type='Bonificacion Decreto') " .
"  WHERE a.active = 1 " .
"  AND h.idperiods = $AID_Period " .
"  /*AND a.idemployees = 4663*/   " .
"  UNION  " .
"  /* DEBITOS */ " .
"SELECT DISTINCT " .
"  a.idemployees,   " .
"  g.idpayments, " .
"  f.number AS 'Account', " .
"  UPPER(CONCAT(TRIM(c.first_name), ' ', TRIM(c.second_name), ' ', TRIM(c.first_lastname), ' ', TRIM(c.second_lastname))) AS 'Name',   " .
"  f.bank, " .
"  IF (f.type = 'BANK CHECK', 'CHEQUE', F.number) AS 'Transferencia/Cheque', " .
"  c.dpi,      " .
"  /* SE SUMAN TODOS LOS CREDITOS Y DEBITOS */ " .
"  ROUND(  " .
"  0.00 +  /* Salario Base (SalarioTotal) */  " .
"  0.00 + /* Ausencias */  " .
"  (-1* (COALESCE(IF(i.TYPE='Descuento IGSS',                      ROUND(i.amount, 2), 0.00), 0.00) +  /* IGSS */  " .
"    COALESCE(IF(i.type not in('Descuento IGSS', 'Descuentos Varios','Descuentos Varios','Anticipo Sobre Sueldo', 'Discount'),ROUND(i.amount, 2), 0.00), 0.00)  +  /* Otras */ " .
"    COALESCE(IF(i.TYPE in('Descuentos Varios', 'Discount'),       ROUND(i.amount, 2), 0.00), 0.00) +  /* Descuentos */ " .
"    COALESCE(IF(i.type='Anticipo Sobre Sueldo',                   ROUND(i.amount, 2), 0.00), 0.00))) +  /* TotalDeducciones En Negativo */ " .
"  0.00 + 0.00 + 0.00 + 0.00 + 0.00 +                                                  /* Otros Descuentos */ " .
"  COALESCE(IF(i.type='Ajuste Salarial',                           ROUND(i.amount, 2), 0.00), 0.00) +  /* Ajuste Salarial */  " .
"  0.00 +  /* BonificacionIncentivo */  " .
"  0.00, 2) AS 'LiquidoARecibir'  /* OtrasBonificacioneseincentivos */   " .
"FROM employees a " .
"INNER JOIN hires b ON (a.id_hire = b.idhires)  " .
"INNER JOIN profiles c ON (b.id_profile = c.idprofiles) " .
"INNER JOIN accounts d ON (a.id_account = d.idaccounts) " .
"INNER JOIN clients e ON (d.id_client = e.idclients) " .
"INNER JOIN payment_methods f ON (f.id_employee = a.idemployees and f.predeterm=1) " .
"INNER JOIN payments g on (g.id_employee = a.idemployees and g.id_paymentmethod = f.idpayment_methods) " .
"INNER JOIN periods h ON (g.id_period = h.idperiods) " .
"INNER JOIN debits i ON (g.idpayments = i.id_payment)  " .
"WHERE a.active = 1 " .
"AND h.idperiods = $AID_Period " .
"/*AND a.idemployees = 4663 */ " .
") A1   " .
"GROUP BY A1.idemployees, " .
"  A1.Account, " .
"  A1.dpi, " .
"  A1.Name  " .
"LIMIT 0, 10000; ";




$title = ['Account', 'DPI', 'Name', 'Mount'];
$output = fopen("php://output", "w");
fputcsv($output, $title);
if($result = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($result)){                
        $exportRow[0] = $row['Account'];
        $exportRow[1] = $row['DPI'];
        $exportRow[2] = $row['Name'];
        $exportRow[3] = $row['Mount'];
        fputcsv($output, $exportRow,",");
        $i++;
    };
}else{
    http_response_code(404);
}
fclose($output);
?>
