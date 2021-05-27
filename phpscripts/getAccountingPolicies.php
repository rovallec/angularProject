<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "reportAccountingPolicy.csv" . '"');

require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$ID_Period = ($request->idperiod);
$clientNetSuite = $request->idaccounts;
$exportRow = [];

$i = 0;
$sql1 = "SELECT COUNT(*) AS account FROM policies WHERE id_period = $ID_Period;";
$sql11 = "SELECT a.end FROM periods a WHERE a.idperiods = $ID_Period;";

  if ($result1 = mysqli_query($con,$sql1)){
    $row1 = mysqli_fetch_assoc($result1);
    if ($result11 = mysqli_query($con,$sql11)) {
      $row11 = mysqli_fetch_assoc($result11);
      $end = $row11['end'];
      $sql12 = "SELECT DAY(LAST_DAY('" . $end . "')) AS V_DAYS_ON_MONTH;";
      if ($result12 = mysqli_query($con,$sql12)) {
        $row12 = mysqli_fetch_assoc($result12);
        $V_DAYS_ON_MONTH = $row12['V_DAYS_ON_MONTH'];
      } else {
        http_response_code(412);
        echo($con->error);
        echo($sql12);
      }
    } else {
      $error = mysqli_error($con);
      http_response_code(411);
      echo($error);
      echo($sql11);
    }
  } else {
    $error =  mysqli_error($con);
    http_response_code(401);
    echo($error);
    echo($sql1);
  }
  $account = $row1['account'];

    $sql2 = "SELECT idaccounts FROM accounts;";
    $today = date("Y/m/d");
    $sql4 = "SELECT 
              ROUND(SUM(DISTINCTROW A1.amount),2) AS amount,
              A1.external_id,
              A1.department, 
              A1.class, 
              A1.site,
              A1.clientNetSuite,
              aa.clasif, aa.name FROM (
              SELECT 
                '51001' AS external_id,
                ROUND(SUM(cred.amount), 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
              WHERE pay.id_period = $ID_Period
              and (cred.type='Salario Base' 
                  or cred.type like '%Horas%Extra%' 
                  or cred.type like '%Horas%de%Asueto%')
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '51021' AS external_id,
                ROUND(SUM(cred.amount), 2) AS amount,  
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
              WHERE pay.id_period = $ID_Period
              AND (cred.type != 'Salario Base' 
                  AND cred.type not like '%Horas Extra%' 
                  AND cred.type not like '%Horas de Asueto%'
                  AND amount > 0
                  AND cred.type not like '%RAF%')
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '51004' AS external_id,
                ROUND(SUM(cred.amount) * 0.1267, 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
              WHERE pay.id_period = $ID_Period
              and (cred.type='Salario Base' 
                  or cred.type like '%Horas Extra%' 
                  or cred.type like '%Horas de Asueto%')
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '65002' AS external_id,
                ROUND(SUM(cred.amount), 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
              WHERE pay.id_period = $ID_Period
              AND cred.type LIKE'%RAF%'
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT '51010' AS external_id,
                ROUND(SUM((IF(e.hiring_date>p.start,
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                    IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                    )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2)),2)
                 AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              group by pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION
              SELECT '51017' AS external_id,
              ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.0972*(pay.base_complete/2)),2) AS amount,
                  pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION
              SELECT '51012' AS external_id,
              ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2)),2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION
              SELECT '51014' AS external_id,
              ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.04166666666*(pay.base_complete/2)),2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION
              SELECT '51013' AS external_id,
                ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
                  pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION
              SELECT '51011' AS external_id,
                ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION
              SELECT '51015' AS external_id,
                ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.041666666*(pay.productivity_complete/2)),2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION SELECT '21082' AS external_id,
              ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.0972*(pay.base_complete/2)),2) AS amount,
                  pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT '21074' AS external_id,
              ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.04166666666*(pay.base_complete/2)),2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT '21077' AS external_id,
                ROUND(SUM((IF(e.hiring_date>p.start,
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                    IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                    )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2)),2)
                 AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              group by pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT '21079' AS external_id,
              ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2)),2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT '21075' AS external_id,
                ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.041666666*(pay.productivity_complete/2)),2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT '21078' AS external_id,
                ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT '21080' AS external_id,
                ROUND(SUM((IF(e.hiring_date>p.start,
                IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
                  IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
                  )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
                  pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN employees e ON (e.idemployees = pay.id_employee)
              INNER JOIN periods p ON (p.idperiods = pay.id_period)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                          INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                          WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
              WHERE pay.id_period = $ID_Period
              GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '21086' AS external_id,
                ROUND(SUM(cred.amount) * 0.1267, 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
              WHERE pay.id_period = $ID_Period
              and (cred.type='Salario Base' 
                  or cred.type like '%Horas Extra%' 
                  or cred.type like '%Horas de Asueto%')
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '21072' AS external_id,
                ROUND(SUM(cred.amount),2) as amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)  
              INNER JOIN (select sum(coalesce(amount, 0)) AS amount, id_payment from credits group by id_payment) cred ON (pay.idpayments = cred.id_payment)
              WHERE pay.id_period = $ID_Period
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '21072' AS external_id,
                -1*ROUND(sum(deb.amount),2) as amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN (select sum(coalesce(amount, 0)) AS amount, id_payment from debits group by id_payment) deb ON (pay.idpayments = deb.id_payment)  
              WHERE pay.id_period = $ID_Period
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts  
              UNION 
              SELECT 
                '46000' AS external_id,
                ROUND(SUM(deb.amount), 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
              WHERE pay.id_period = $ID_Period
              AND deb.type like'%bus%'
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '23010' AS external_id,
                ROUND(SUM(deb.amount), 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
              WHERE pay.id_period = $ID_Period
              and deb.type='CAR PARKING'
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '23010' AS external_id,
                ROUND(SUM(deb.amount), 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
              WHERE pay.id_period = $ID_Period
              AND deb.type='MOTORCYCLE PARKING'
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '61407' AS external_id,
                ROUND(SUM(deb.amount), 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
              WHERE pay.id_period = $ID_Period
              and (deb.type IN('TARJETA DE ACCESO/PARQUEO', 'Tarjeta De Acceso')
              or deb.type like'%Headset%')
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT  
                '22030' AS external_id,
                ROUND(SUM(deb.amount), 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
              WHERE pay.id_period = $ID_Period
              AND deb.type='ISR'
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '21085' AS external_id,
                ROUND(SUM(deb.amount),2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
<<<<<<< HEAD
              INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
              WHERE pay.id_period = $ID_Period
=======
              LEFT JOIN debits deb ON (pay.idpayments = deb.id_payment)
              WHERE pay.id_period = 34 and a2.clientNetSuite = 1
>>>>>>> master
              AND deb.type='Descuento IGSS'
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '13020' AS external_id,
                ROUND(SUM(deb.amount), 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
              WHERE pay.id_period = $ID_Period
              AND (type like'%personal%'
                  OR TYPE LIKE '%ajuste%'
                  OR TYPE LIKE '%prestamo%'
                  OR TYPE LIKE '%anticipo%'
                  OR TYPE LIKE '%adelanto%')
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '22050' AS external_id,
                ROUND(SUM(deb.amount), 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
              WHERE pay.id_period = $ID_Period
              AND deb.type='Boleto de Ornato'
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              UNION 
              SELECT 
                '21083' AS external_id,
                ROUND(SUM(deb.amount), 2) AS amount,
                pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
              FROM payments pay
              INNER JOIN periods per ON (pay.id_period = per.idperiods)
              INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
              INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
              WHERE pay.id_period = $ID_Period
              and deb.type IN('Descuento Judicial')
              group BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts
            ) AS A1 
            INNER JOIN accounting_accounts aa on (A1.external_id = aa.external_id)
            WHERE ((A1.clientNetSuite = $clientNetSuite) OR ($clientNetSuite = -1))
            GROUP BY A1.external_id, A1.department, A1.class, A1.site, A1.clientNetSuite, aa.clasif, aa.name
            ORDER BY A1.clientNetSuite;";

    if ($result4 = mysqli_query($con,$sql4)) {
      while($row4 = mysqli_fetch_assoc($result4)){
        $exportRow[$i]['external_id'] = $row4['external_id'];
        $exportRow[$i]['name'] = $row4['name'];
        $exportRow[$i]['clasif'] = $row4['clasif'];
        $exportRow[$i]['department'] = ($row4['department']);
        $exportRow[$i]['class'] = ($row4['class']);
        $exportRow[$i]['site'] = ($row4['site']);
        $exportRow[$i]['amount'] = ($row4['amount']);
        $exportRow[$i]['clientNetSuite'] = ($row4['clientNetSuite']);
        $i++;
      }
      $resultF = json_encode($exportRow);
      echo($resultF);
    }
?>
