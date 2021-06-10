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
$month = ($request->month);
$type = ($request->type);

$i = 0;
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
          AND ((cred.type='Salario Base' AND ((e.job_type != 1) OR (e.job_type is null)))
            OR cred.type like '%Horas%Extra%' 
            OR cred.type like '%Horas%de%Asueto%')
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
          AND ((cred.type='Salario Base' AND ((e.job_type != 1) or (e.job_type is null)))
            or cred.type like '%Horas Extra%' 
            or cred.type like '%Horas de Asueto%')
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
          AND cred.type LIKE'%RAF%'
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
          AND ((e.job_type != 1) or (e.job_type is null))
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
          AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
            AND ((cred.type ='Salario Base' AND ((e.job_type != 1) or (e.job_type is null)))
            or cred.type LIKE '%Horas Extra%' 
            or cred.type LIKE '%Horas de Asueto%')
          GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          UNION 
          /* CREDITOS OPERADORES */
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
          AND ((e.job_type != 1 ) OR (e.job_type is null))
          AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
          /*GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts */
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
          AND ((e.job_type != 1 ) OR (e.job_type is null))
          AND ((a2.clientNetSuite = $clientNetSuite) OR (-1 = $clientNetSuite))
          /*GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts */
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
          AND (cred.`type` NOT IN('Bonificacion Productividad', 'Salario Base', 'Bonificacion Decreto') AND (e.job_type = 1))
          AND ((a2.clientNetSuite = $clientNetSuite) OR (-1 = $clientNetSuite))
          /* GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts */
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
          AND ((deb.`type` NOT LIKE '%IGSS%') AND (e.job_type = 1))
          AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
          /*GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts */
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
          AND (((cred.`type` LIKE'%Horas%extra%') OR (cred.`type` LIKE'%horas%de%asueto%')) AND (e.job_type = 1))
          AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
          /*GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts*/
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
          AND ((a2.clientNetSuite = $clientNetSuite) or (-1 = $clientNetSuite))
          AND deb.type like'%bus%'
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
          AND (deb.type IN('TARJETA DE ACCESO/PARQUEO', 'Tarjeta De Acceso')
          OR deb.type LIKE'%Headset%')
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
          INNER JOIN employees e ON (pay.id_employee = e.idemployees)        
          INNER JOIN accounting_accounts aa on ('22030' = aa.external_id) 
          WHERE month(per.start) = $month
          AND deb.type='ISR'
          GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
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
          AND (cred.type='Salario Base' AND ((e.job_type = 1)))    
          GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts
          ) AS `temp` GROUP BY external_id, id_account_py, department, class, site, clientNetSuite, id_client, idaccounts, clasif, name, idaccounting_accounts
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
          AND (type like'%personal%'
            OR TYPE LIKE '%ajuste%'
            OR TYPE LIKE '%prestamo%'
            OR TYPE LIKE '%anticipo%'
            OR TYPE LIKE '%adelanto%')
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
          AND deb.type='Boleto de Ornato'
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
          AND deb.type IN('Descuento Judicial')
          GROUP BY pay.id_account_py, a2.department, a2.class, a2.site, a2.clientNetSuite, a2.id_client, a2.idaccounts, aa.clasif, aa.name, aa.idaccounting_accounts;";

    if ($result1 = mysqli_query($con,$sql1)) {
      while($row1 = mysqli_fetch_assoc($result1)){
        $exportRow[$i]['external_id'] = $row1['external_id'];
        $exportRow[$i]['amount'] = ($row1['amount']);
        $exportRow[$i]['id_account_py'] = $row1['id_account_py'];
        $exportRow[$i]['department'] = ($row1['department']);
        $exportRow[$i]['class'] = ($row1['class']);
        $exportRow[$i]['site'] = ($row1['site']);
        $exportRow[$i]['clientNetSuite'] = ($row1['clientNetSuite']);
        $exportRow[$i]['id_client'] = ($row1['id_client']);
        $exportRow[$i]['idaccounts'] = ($row1['idaccounts']);
        $exportRow[$i]['clasif'] = $row1['clasif'];
        $exportRow[$i]['name'] = $row1['name'];
        $exportRow[$i]['idaccounting_accounts'] = $row1['idaccounting_accounts'];
        $i++;
      }
      $resultF = json_encode($exportRow);
      echo($resultF);
    }else{
      $error = "Sucedió el siguiente error: " . mysqli_error($con) . ".<br>";
      echo($error . $sql1);
    }
?>