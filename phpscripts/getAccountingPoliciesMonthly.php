<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$ID_Period = ($request->idperiod);
$clientNetSuite = $request->idaccounts;
$month = ($request->month);
$type = ($request->type);


/*$clientNetSuite = -1;
$month = 5;
$type = true;
*/
$exportRow = [];
$i = 0;

static $ele = array('external_id' => '', 'amount' => '', 'id_account_py' => '', 'department' => '', 'class' => '', 'site' => '', 
                    'clientNetSuite' => '', 'id_client' => 'idaccounts', 'clasif' => '', 'name' => '', 'idaccounting_accounts' => '');
static $new = array();
$new = array($ele);

function accounting($count, $clientNetSuite, $month) {
  switch ($count): 
    case '51001':
      $sql1 = "SELECT DISTINCT
        '51001' AS external_id,                 
        ROUND(SUM(cred.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('51001' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND ((cred.type='Salario Base' AND ((e.job_type != 1) OR (e.job_type is null)))
          OR cred.type like '%Horas%Extra%' 
          OR cred.type like '%Horas%de%Asueto%')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        /* NINA */
        SELECT DISTINCT
        '51001' AS external_id,                 
        ROUND(SUM(cred.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('51001' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees = 5041
        AND ((cred.type='Salario Base' AND ((e.job_type != 1) OR (e.job_type is null)))
          OR cred.type like '%Horas%Extra%' 
          OR cred.type like '%Horas%de%Asueto%')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '51021':
      $sql1 = "SELECT 
        '51021' AS external_id,  
        ROUND(sum(cred.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, 
        aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('51021' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND ((cred.type != 'Salario Base' 
          AND cred.type not like '%Horas Extra%' 
          AND cred.type not like '%Horas de Asueto%'
          AND cred.type not like '%RAF%'
          AND (e.job_type != 1 OR e.job_type IS NULL))
        OR (e.job_type = 1 AND (
              cred.type != 'Salario Base'
              AND cred.type not like '%Horas Extra%' 
              AND cred.type not like '%Horas de Asueto%'
              AND cred.type not like '%RAF%'
              AND cred.type != 'Bonificacion Productividad'
              AND cred.type != 'Bonificacion Decreto'
            )
          )
        )
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION 
        SELECT 
        '51021' AS external_id,  
        ROUND(sum(cred.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, 
        aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('51021' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees = 5041
        AND ((cred.type != 'Salario Base' 
          AND cred.type not like '%Horas Extra%' 
          AND cred.type not like '%Horas de Asueto%'
          AND cred.type not like '%RAF%'
          AND (e.job_type != 1 OR e.job_type IS NULL))
        OR (e.job_type = 1 AND (
              cred.type != 'Salario Base'
              AND cred.type not like '%Horas Extra%' 
              AND cred.type not like '%Horas de Asueto%'
              AND cred.type not like '%RAF%'
              AND cred.type != 'Bonificacion Productividad'
              AND cred.type != 'Bonificacion Decreto'
            )
          )
        )
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '51004':
      $sql1 = "SELECT 
        '51004' AS external_id,
        ROUND(SUM(cred.amount) * 0.1267, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('51004' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND ((cred.type='Salario Base' AND ((e.job_type != 1) or (e.job_type is null)))
          or cred.type like '%Horas Extra%' 
          or cred.type like '%Horas de Asueto%')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION 
        SELECT 
        '51004' AS external_id,
        ROUND(SUM(cred.amount) * 0.1267, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('51004' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees = 5041
        AND ((cred.type='Salario Base' AND ((e.job_type != 1) or (e.job_type is null)))
          or cred.type like '%Horas Extra%' 
          or cred.type like '%Horas de Asueto%')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '65002':
      $sql1 = "SELECT 
        '65002' AS external_id,
        ROUND(SUM(cred.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('65002' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND cred.type LIKE'%RAF%'
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION 
        SELECT 
        '65002' AS external_id,
        ROUND(SUM(cred.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('65002' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees = 5041
        AND cred.type LIKE'%RAF%'
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
        break;
    case '51010':
      $sql1 = "SELECT '51010' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
            IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
            )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2)),2)
        AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51010' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT '51010' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
            IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
            )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2)),2)
        AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51010' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '51017':
      $sql1 = "SELECT '51017' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.0972*(pay.base_complete/2)),2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51017' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT '51017' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.0972*(pay.base_complete/2)),2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51017' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '51012':
      $sql1 = "SELECT '51012' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51012' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT '51012' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51012' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '51014':
      $sql1 = "SELECT '51014' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.04166666666*(pay.base_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51014' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT '51014' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.04166666666*(pay.base_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51014' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '51013':
      $sql1 = "SELECT '51013' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51013' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT '51013' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51013' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '51011':
      $sql1 = "SELECT '51011' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51011' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT '51011' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51011' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '51015':
      $sql1 = "SELECT '51015' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.041666666*(pay.productivity_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51015' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT '51015' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.041666666*(pay.productivity_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('51015' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '21082':
      $sql1 = "SELECT '21082' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.0972*(pay.base_complete/2)),2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('21082' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION 
        SELECT '21082' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.0972*(pay.base_complete/2)),2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('21082' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '21074':
      $sql1 = "SELECT '21074' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.04166666666*(pay.base_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('21074' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION 
        SELECT '21074' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.04166666666*(pay.base_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('21074' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '21079':
      $sql1 = "SELECT '21079' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('21079' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION 
        SELECT '21079' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.base_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('21079' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '21075':
      $sql1 = "SELECT '21075' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.041666666*(pay.productivity_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('21075' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION 
        SELECT '21075' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.041666666*(pay.productivity_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('21075' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end
        WHERE month(p.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '21078':
      $sql1 = "SELECT '21078' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('21078' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT '21078' AS external_id,
          ROUND(SUM((IF(e.hiring_date>p.start,
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
            IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
            )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          FROM payments pay
          INNER JOIN employees e ON (e.idemployees = pay.id_employee)
          INNER JOIN periods p ON (p.idperiods = pay.id_period)
          INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
          INNER JOIN accounting_accounts aa on ('21078' = aa.external_id)
          LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                    INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                    WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
          WHERE month(p.start) = $month
          AND e.idemployees = 5041
          AND ((e.job_type != 1) or (e.job_type is null))
          GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '21080':
      $sql1 = "SELECT '21080' AS external_id,
        ROUND(SUM((IF(e.hiring_date>p.start,
        IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
          )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN periods p ON (p.idperiods = pay.id_period)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN accounting_accounts aa on ('21080' = aa.external_id)
        LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                  INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                  WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
        WHERE month(p.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT '21080' AS external_id,
          ROUND(SUM((IF(e.hiring_date>p.start,
          IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, e.hiring_date),DATEDIFF(p.end, e.hiring_date)),
            IF(term.valid_from IS NOT NULL,DATEDIFF(`term`.valid_from, p.start)+1,DATEDIFF(p.end, p.start)+1)
            )) / (DATEDIFF(p.end, p.`start`)+1) *0.08333333333*(pay.productivity_complete/2)),2) AS amount,
            pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          FROM payments pay
          INNER JOIN employees e ON (e.idemployees = pay.id_employee)
          INNER JOIN periods p ON (p.idperiods = pay.id_period)
          INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
          INNER JOIN accounting_accounts aa on ('21080' = aa.external_id)
          LEFT JOIN ( SELECT hp2.id_employee, t2.valid_from FROM hr_processes hp2
                    INNER JOIN terminations t2 ON t2.id_process = hp2.idhr_processes
                    WHERE hp2.id_type = 8 AND t2.valid_from IS NOT NULL) AS `term` ON `term`.id_employee = pay.id_employee AND term.valid_from BETWEEN p.start AND p.end 
          WHERE month(p.start) = $month
          AND e.idemployees = 5041
          AND ((e.job_type != 1) or (e.job_type is null))
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '21086':
      $sql1 = "SELECT 
        '21086' AS external_id,                
        ROUND(SUM(cred.amount) * 0.1267, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (e.idemployees = pay.id_employee)
        INNER JOIN accounting_accounts aa on ('21086' = aa.external_id)
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
          AND ((cred.type ='Salario Base' AND ((e.job_type != 1) or (e.job_type is null)))
          or cred.type LIKE '%Horas Extra%' 
          or cred.type LIKE '%Horas de Asueto%')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT
          '21086' AS external_id,                
          ROUND(SUM(cred.amount) * 0.1267, 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          FROM payments pay
          INNER JOIN periods per ON (pay.id_period = per.idperiods)
          INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
          INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
          INNER JOIN employees e ON (e.idemployees = pay.id_employee)
          INNER JOIN accounting_accounts aa on ('21086' = aa.external_id)
          WHERE month(per.start) = $month
          AND e.idemployees = 5041
          AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
            AND ((cred.type ='Salario Base' AND ((e.job_type != 1) or (e.job_type is null)))
            or cred.type LIKE '%Horas Extra%' 
            or cred.type LIKE '%Horas de Asueto%')
          GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '21072':
      $sql1 = " SELECT '21072' AS external_id,
        /* CREDITOS OPERADORES */
        ROUND(cred.amount, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21072' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1 ) OR (e.job_type is null))
        AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
        UNION
        /* DEBITOS OPERADORES */
        SELECT
        '21072' AS external_id,
        ROUND(deb.amount, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21072' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND ((e.job_type != 1 ) OR (e.job_type is null))
        AND ((a2.clientNetSuite = $clientNetSuite) OR (-1 = $clientNetSuite))
        UNION
        /* CREDITOS SUPERVISORES */
        SELECT
        '21072' AS external_id,
        ROUND(cred.amount, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts) 
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa ON ('21072' = aa.external_id)
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND (cred.`type` NOT IN('Bonificacion Productividad', 'Salario Base', 'Bonificacion Decreto') AND (e.job_type = 1))
        AND ((a2.clientNetSuite = $clientNetSuite) OR (-1 = $clientNetSuite))
        UNION
        /* DEBITOS SUPERVISORES */
        SELECT
        '21072' AS external_id,
        -1*ROUND(deb.amount, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21072' = aa.external_id)
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND ((deb.`type` NOT LIKE '%IGSS%') AND (e.job_type = 1))
        AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
        UNION
        /* IGSS */
        SELECT
        '21072' AS external_id,
        -1*ROUND(cred.amount * 0.0483, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21072' = aa.external_id)
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND (((cred.`type` LIKE'%Horas%extra%') OR (cred.`type` LIKE'%horas%de%asueto%')) AND (e.job_type = 1))
        AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
        /* NINA */
        /* CREDITOS OPERADORES */   
        UNION
        SELECT
        '21072' AS external_id,
        ROUND(cred.amount, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21072' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1 ) OR (e.job_type is null))
        AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
        UNION
        /* DEBITOS OPERADORES */
        SELECT
        '21072' AS external_id,
        ROUND(deb.amount, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21072' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees = 5041
        AND ((e.job_type != 1 ) OR (e.job_type is null))
        AND ((a2.clientNetSuite = $clientNetSuite) OR (-1 = $clientNetSuite))
        UNION
        /* CREDITOS SUPERVISORES */
        SELECT
        '21072' AS external_id,
        ROUND(cred.amount, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts) 
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa ON ('21072' = aa.external_id)
        WHERE month(per.start) = $month
        AND e.idemployees = 5041
        AND (cred.`type` NOT IN('Bonificacion Productividad', 'Salario Base', 'Bonificacion Decreto') AND (e.job_type = 1))
        AND ((a2.clientNetSuite = $clientNetSuite) OR (-1 = $clientNetSuite))
        UNION
        /* DEBITOS SUPERVISORES */
        SELECT
        '21072' AS external_id,
        -1*ROUND(deb.amount, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21072' = aa.external_id)
        WHERE month(per.start) = $month
        AND e.idemployees = 5041
        AND ((deb.`type` NOT LIKE '%IGSS%') AND (e.job_type = 1))
        AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
        UNION
        /* IGSS */
        SELECT
        '21072' AS external_id,
        -1*ROUND(cred.amount * 0.0483, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees )
        INNER JOIN accounting_accounts aa on ('21072' = aa.external_id)
        WHERE month(per.start) = $month
        AND e.idemployees = 5041
        AND (((cred.`type` LIKE'%Horas%extra%') OR (cred.`type` LIKE'%horas%de%asueto%')) AND (e.job_type = 1))
        AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite));";
      break;
    case '46000':
        $sql1 = "SELECT 
          '46000' AS external_id,
          ROUND(SUM(deb.amount), 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          FROM payments pay
          INNER JOIN periods per ON (pay.id_period = per.idperiods)
          INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
          INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
          INNER JOIN employees e ON (pay.id_employee = e.idemployees)
          INNER JOIN accounting_accounts aa on ('46000' = aa.external_id) 
          WHERE month(per.start) = $month
          AND e.idemployees <> 5041
          AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
          AND deb.type like'%bus%'
          GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          UNION 
          SELECT
          '46000' AS external_id,
          ROUND(SUM(deb.amount), 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          FROM payments pay
          INNER JOIN periods per ON (pay.id_period = per.idperiods)
          INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts)
          INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
          INNER JOIN employees e ON (pay.id_employee = e.idemployees)
          INNER JOIN accounting_accounts aa on ('46000' = aa.external_id) 
          WHERE month(per.start) = $month
            AND e.idemployees = 5041
            AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
            AND deb.type like'%bus%'
          GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '23010':
      $sql1 = "SELECT 
        '23010' AS external_id,
        ROUND(SUM(deb.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('23010' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND deb.type='CAR PARKING'
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts              
        UNION 
        SELECT 
        '23010' AS external_id,
        ROUND(SUM(deb.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('23010' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND deb.type='MOTORCYCLE PARKING'
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION 
        SELECT 
        '23010' AS external_id,
        ROUND(SUM(deb.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('23010' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND (deb.type IN('TARJETA DE ACCESO/PARQUEO', 'Tarjeta De Acceso')
        OR deb.type LIKE'%Headset%')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        /* NINA */
        UNION
        SELECT
          '23010' AS external_id,
          ROUND(SUM(deb.amount), 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('23010' = aa.external_id) 
        WHERE month(per.start) = $month
          AND e.idemployees = 5041
          AND deb.type='CAR PARKING'
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts              
        UNION 
        SELECT
          '23010' AS external_id,
          ROUND(SUM(deb.amount), 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('23010' = aa.external_id) 
        WHERE month(per.start) = $month
          AND e.idemployees = 5041
          AND deb.type='MOTORCYCLE PARKING'
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT
          '23010' AS external_id,
          ROUND(SUM(deb.amount), 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('23010' = aa.external_id) 
        WHERE month(per.start) = $month
          AND e.idemployees = 5041
          AND (deb.type IN('TARJETA DE ACCESO/PARQUEO', 'Tarjeta De Acceso')
          OR deb.type LIKE'%Headset%')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '22030':
      $sql1 = "SELECT  
        '22030' AS external_id,
        ROUND(SUM(deb.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)        
        INNER JOIN accounting_accounts aa on ('22030' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND deb.type='ISR'
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT
          '22030' AS external_id,
          ROUND(SUM(deb.amount), 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees )        
        INNER JOIN accounting_accounts aa on ('22030' = aa.external_id) 
          WHERE month(per.start) = $month
          AND e.idemployees = 5041
          AND deb.type='ISR'
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '21085':
      $sql1 = "SELECT external_id, ROUND(SUM(`temp`.amount),2), id_account_py, department, class, site, clientNetSuite, id_client, idaccounts, clasif, name, idaccounting_accounts FROM
        (
        SELECT 
        '21085' AS external_id,
        ROUND(SUM(deb.amount),2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21085' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND deb.type LIKE'%IGSS%'    
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        union
        SELECT 
        '21085' AS external_id,
        -1*ROUND(SUM(cred.amount)*0.0483, 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21085' = aa.external_id)
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND (cred.type='Salario Base' AND ((e.job_type = 1)))    
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        ) AS `temp` GROUP BY external_id, id_account_py, department, class, site, clientNetSuite, id_client, idaccounts, clasif, name, idaccounting_accounts
        /* NINA */
        UNION
        SELECT external_id, ROUND(SUM(`temp`.amount),2), id_account_py, department, class, site, clientNetSuite, id_client, idaccounts, clasif, name, idaccounting_accounts FROM
          (
          SELECT 
          '21085' AS external_id,
          ROUND(SUM(deb.amount),2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          FROM payments pay
          INNER JOIN periods per ON (pay.id_period = per.idperiods)
          INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
          INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
          INNER JOIN employees e ON (pay.id_employee = e.idemployees)
          INNER JOIN accounting_accounts aa on ('21085' = aa.external_id) 
          WHERE month(per.start) = $month
          AND e.idemployees = 5041
          AND deb.type LIKE'%IGSS%'    
          GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          union
          SELECT 
          '21085' AS external_id,
          -1*ROUND(SUM(cred.amount)*0.0483, 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          FROM payments pay
          INNER JOIN periods per ON (pay.id_period = per.idperiods)
          INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
          INNER JOIN credits cred ON (pay.idpayments = cred.id_payment)
          INNER JOIN employees e ON (pay.id_employee = e.idemployees)
          INNER JOIN accounting_accounts aa on ('21085' = aa.external_id)
          WHERE month(per.start) = $month
          AND e.idemployees = 5041
          AND (cred.type='Salario Base' AND ((e.job_type = 1)))    
          GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          ) AS `temp` GROUP BY external_id, id_account_py, department, class, site, clientNetSuite, id_client, idaccounts, clasif, name, idaccounting_accounts;";
      break;
    case '13020':
      $sql1 = "SELECT 
        '13020' AS external_id,
        ROUND(SUM(deb.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('13020' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND (type like'%personal%'
          OR TYPE LIKE '%ajuste%'
          OR TYPE LIKE '%prestamo%'
          OR TYPE LIKE '%anticipo%'
          OR TYPE LIKE '%adelanto%')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT
          '13020' AS external_id,
          ROUND(SUM(deb.amount), 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('13020' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees = 5041
        AND (type like'%personal%'
          OR TYPE LIKE '%ajuste%'
          OR TYPE LIKE '%prestamo%'
          OR TYPE LIKE '%anticipo%'
          OR TYPE LIKE '%adelanto%')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '22050':
      $sql1 = "SELECT 
        '22050' AS external_id,
        ROUND(SUM(deb.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('22050' = aa.external_id) 
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND deb.type='Boleto de Ornato'
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT
          '22050' AS external_id,
          ROUND(SUM(deb.amount), 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('22050' = aa.external_id) 
        WHERE month(per.start) = $month
          AND e.idemployees = 5041
          AND deb.type='Boleto de Ornato'
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
    case '21083':
      $sql1 = "SELECT 
        '21083' AS external_id,
        ROUND(SUM(deb.amount), 2) AS amount,
        pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21083' = aa.external_id)
        WHERE month(per.start) = $month
        AND e.idemployees <> 5041
        AND deb.type IN('Descuento Judicial')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        UNION
        SELECT
          '21083' AS external_id,
          ROUND(SUM(deb.amount), 2) AS amount,
          pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
        FROM payments pay
        INNER JOIN periods per ON (pay.id_period = per.idperiods)
        INNER JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite)))
        INNER JOIN debits deb ON (pay.idpayments = deb.id_payment)
        INNER JOIN employees e ON (pay.id_employee = e.idemployees)
        INNER JOIN accounting_accounts aa on ('21083' = aa.external_id)
        WHERE month(per.start) = $month
          AND e.idemployees = 5041
          AND deb.type IN('Descuento Judicial')
        GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";
      break;
      default: $sql1 = "SELECT 0 AS amount, " . $count . " AS external_id, '' AS id_account_py, 
                        '' AS department, '' AS class, '' AS site, '' AS clientNetSuite, 
                        '' AS id_client, '' AS idaccounts, '' AS clasif, '' AS name, '' AS idaccounting_accounts;";
      break;
    endswitch;
  return $sql1;
}

$new = array();
$i = 0;
$sql = "SELECT DISTINCT idaccounting_accounts, external_id, name, clasif FROM accounting_accounts WHERE cost = 1 ORDER BY clasif, external_id DESC;";
if($result = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($result)) {
    $return[$i]['idaccounting_accounts'] = $row['idaccounting_accounts'];
    $return[$i]['external_id'] = $row['external_id'];
    $return[$i]['name'] = $row['name'];
    $return[$i]['clasif'] = $row['clasif'];
    $i++;
  }
  $accounts = $return;    
  for ($i = 0; $i < count($accounts); $i++){
    $external_id = strval($accounts[$i]['external_id']);
    $j = 0;
    if(isset($external_id) && !is_null($external_id) ) {
      $sql2 = accounting($external_id, $clientNetSuite, $month);
      if ($result2 = mysqli_query($con,$sql2)) {
        if(count(mysqli_fetch_assoc($result2))>1) {
          $rows = true;
          while($row2 = mysqli_fetch_assoc($result2)){
            $exportRow[$j]['external_id'] = $row2['external_id'];
            $exportRow[$j]['amount'] = $row2['amount'];
            $exportRow[$j]['id_account_py'] = $row2['id_account_py'];
            $exportRow[$j]['department'] = $row2['department'];
            $exportRow[$j]['class'] = $row2['class'];
            $exportRow[$j]['site'] = $row2['site'];
            $exportRow[$j]['clientNetSuite'] = $row2['clientNetSuite'];
            $exportRow[$j]['id_client'] = $row2['id_client'];
            $exportRow[$j]['idaccounts'] = $row2['idaccounts'];
            $exportRow[$j]['clasif'] = $row2['clasif'];
            $exportRow[$j]['name'] = $row2['name'];
            $exportRow[$j]['idaccounting_accounts'] = $row2['idaccounting_accounts'];
            $j++;
          }
        } else {
          $rows = false;
        }
      } else {
        $error = "Sucedió el siguiente error: " . mysqli_error($con) . ".<br>";
        echo($error . $sql2);
        return $error . $sql2;
      }
    }
    if ($rows = true) {
      $amount = round(round(round(array_sum(array_column($exportRow, 'amount')),4),3),2);
      $ele['external_id'] = $exportRow[0]['external_id'];
      $ele['amount'] = $amount;
      $ele['id_account_py'] = $exportRow[0]['id_account_py'];
      $ele['department'] = $exportRow[0]['department'];
      $ele['class'] = $exportRow[0]['class'];
      $ele['site'] = $exportRow[0]['site'];
      $ele['clientNetSuite'] = $exportRow[0]['clientNetSuite'];
      $ele['id_client'] = $exportRow[0]['id_client'];
      $ele['idaccounts'] = $exportRow[0]['idaccounts'];
      $ele['clasif'] = $exportRow[0]['clasif'];
      $ele['name'] = $exportRow[0]['name'];
      $ele['idaccounting_accounts'] = $exportRow[0]['idaccounting_accounts'];

      //$new[] = $ele;
      array_push($new, $ele);
    }
  }
}
echo(json_encode($new));
?>