<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
if (!is_null($request->filter) and (!empty($request->filter))) {
  $filter = ($request->filter);
}

if (!is_null($request->value) and !empty($request->value)) {
  $value = ($request->value);
}

$nm = ($request->department);
$res = [];
$i = 0;

if($nm=='terminations'){
  $sql = "SELECT
    a.idemployees,
    d.nearsol_id,
    CONCAT(TRIM(e.first_name), ' ', TRIM(e.second_name), ' ', TRIM(e.first_lastname), ' ', TRIM(e.second_lastname)) AS name,
    c.name AS account,
    a.hiring_date,
    DATEDIFF(t.valid_from, a.hiring_date) AS days_to_pay,
    0 AS base_payment,
    0 AS complement,
    0 AS base_calc,
    0 AS average,
    (-1 * COALESCE(adv.amount, 0.00)) AS advances,
    0 AS total
  FROM employees a
  INNER JOIN users b ON (a.reporter = b.idUser)
  INNER JOIN accounts c ON (a.id_account = c.idaccounts)
  INNER JOIN hires d ON (a.id_hire = d.idhires)          
  INNER JOIN profiles e ON (e.idprofiles = d.id_profile) 
  INNER JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  INNER JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN (SELECT SUM(ad.amount) AS amount, ad.id_process from advances ad group by ad.id_process) adv on (hr.idhr_processes = adv.id_process)
  ORDER BY a.hiring_date DESC
  LIMIT 50;";
} elseif ($filter === 'name') {
  $sql = "SELECT
    a.idemployees,
    d.nearsol_id,
    e.name,
    c.name AS account,
    a.hiring_date,
    DATEDIFF(t.valid_from, a.hiring_date) AS days_to_pay,
    0 AS base_payment,
    0 AS complement,
    0 AS base_calc,
    0 AS average,
    (-1 * COALESCE(adv.amount, 0.00)) AS advances,
    0 AS total
  FROM employees a
  INNER JOIN users b ON (a.reporter = b.idUser)
  INNER JOIN accounts c ON (a.id_account = c.idaccounts)
  INNER JOIN hires d ON (a.id_hire = d.idhires)
  INNER JOIN (SELECT CONCAT(TRIM(p.first_name), ' ', TRIM(p.second_name), ' ', TRIM(p.first_lastname), ' ', TRIM(p.second_lastname)) AS name, p.idprofiles from profiles p) e ON (e.idprofiles = d.id_profile) 
  INNER JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  INNER JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN (SELECT SUM(ad.amount) AS amount, ad.id_process from advances ad group by ad.id_process) adv on (hr.idhr_processes = adv.id_process)
  WHERE e.name LIKE '%$value%'
  LIMIT 50;";  
} elseif ($filter === 'dpi') {
  $sql = "SELECT
    a.idemployees,
    d.nearsol_id,
    e.name,
    c.name AS account,
    a.hiring_date,
    DATEDIFF(t.valid_from, a.hiring_date) AS days_to_pay,
    0 AS base_payment,
    0 AS complement,
    0 AS base_calc,
    0 AS average,
    (-1 * COALESCE(adv.amount, 0.00)) AS advances,
    0 AS total
  FROM employees a
  INNER JOIN users b ON (a.reporter = b.idUser)
  INNER JOIN accounts c ON (a.id_account = c.idaccounts)
  INNER JOIN hires d ON (a.id_hire = d.idhires)
  INNER JOIN (SELECT CONCAT(TRIM(p.first_name), ' ', TRIM(p.second_name), ' ', TRIM(p.first_lastname), ' ', TRIM(p.second_lastname)) AS name, p.dpi, p.idprofiles from profiles p) e ON (e.idprofiles = d.id_profile) 
  INNER JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  INNER JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN (SELECT SUM(ad.amount) AS amount, ad.id_process from advances ad group by ad.id_process) adv on (hr.idhr_processes = adv.id_process)
  WHERE e.dpi LIKE '%$value%'
  LIMIT 50;";  
} elseif ($filter === 'client_id') {
  $sql = "SELECT
    a.idemployees,
    d.nearsol_id,
    e.name,
    c.name AS account,
    a.hiring_date,
    DATEDIFF(t.valid_from, a.hiring_date) AS days_to_pay,
    0 AS base_payment,
    0 AS complement,
    0 AS base_calc,
    0 AS average,
    (-1 * COALESCE(adv.amount, 0.00)) AS advances,
    0 AS total
  FROM employees a
  INNER JOIN users b ON (a.reporter = b.idUser)
  INNER JOIN accounts c ON (a.id_account = c.idaccounts)
  INNER JOIN hires d ON (a.id_hire = d.idhires)
  INNER JOIN (SELECT CONCAT(TRIM(p.first_name), ' ', TRIM(p.second_name), ' ', TRIM(p.first_lastname), ' ', TRIM(p.second_lastname)) AS name, p.dpi, p.idprofiles from profiles p) e ON (e.idprofiles = d.id_profile) 
  INNER JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  INNER JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN (SELECT SUM(ad.amount) AS amount, ad.id_process from advances ad group by ad.id_process) adv on (hr.idhr_processes = adv.id_process)
  WHERE c.id_client LIKE '%$value%'
  LIMIT 50;";  
} elseif ($filter === 'nearsol_id') {
  $sql = "SELECT
    a.idemployees,
    d.nearsol_id,
    e.name,
    c.name AS account,
    a.hiring_date,
    DATEDIFF(t.valid_from, a.hiring_date) AS days_to_pay,
    0 AS base_payment,
    0 AS complement,
    0 AS base_calc,
    0 AS average,
    (-1 * COALESCE(adv.amount, 0.00)) AS advances,
    0 AS total
  FROM employees a
  INNER JOIN users b ON (a.reporter = b.idUser)
  INNER JOIN accounts c ON (a.id_account = c.idaccounts)
  INNER JOIN hires d ON (a.id_hire = d.idhires)
  INNER JOIN (SELECT CONCAT(TRIM(p.first_name), ' ', TRIM(p.second_name), ' ', TRIM(p.first_lastname), ' ', TRIM(p.second_lastname)) AS name, p.dpi, p.idprofiles from profiles p) e ON (e.idprofiles = d.id_profile) 
  INNER JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  INNER JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN (SELECT SUM(ad.amount) AS amount, ad.id_process from advances ad group by ad.id_process) adv on (hr.idhr_processes = adv.id_process)
  WHERE d.nearsol_id LIKE '%$value%'
  LIMIT 50;";  
}

if($request = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($request)){
    $idemployees = $row['idemployees'];
    $sql2 = "SELECT 
                a.id_employee,
                SUM(a.base_complete) AS base_complete,
                SUM(a.productivity_complete) AS productivity_complete,
                COUNT(a.id_employee) AS counting
            FROM (
              SELECT 
                pay.id_employee,
                pay.base_complete,
                pay.productivity_complete  
              FROM payments pay 
              INNER JOIN employees a ON (pay.id_employee = a.idemployees) 
              INNER JOIN periods per ON (pay.id_period = per.idperiods AND per.end >= a.hiring_date 
                                          AND (pay.base_complete > 0 AND pay.productivity_complete > 0))
              WHERE a.idemployees = " . $idemployees . "
              ORDER BY pay.id_employee, per.idperiods DESC LIMIT 0, 24
            ) a GROUP BY A.id_employee;";

            echo($sql2);
    if($request2 = mysqli_query($con,$sql2)){
      if (isset($request2) || empty($request2)) {
        $base_complete = 100;
        $productivity_complete = 100;
        $base_calc = 100;
      } else {
        $row2 = mysqli_fetch_assoc($request2);        
        $base_complete = $row2['base_complete'] / $row2['counting'];
        $productivity_complete = $row2['productivity_complete'] / $row2['counting'];
        $base_calc = $base_complete + $productivity_complete;
      }
    } else {
      $base_complete = 0;
      $productivity_complete = 0;
      $base_calc = 0;
    }

    $res[$i]['idemployees'] = $row['idemployees'];
    $res[$i]['nearsol_id'] = $row['nearsol_id'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['account'] = $row['account'];
    $res[$i]['hiring_date'] = $row['hiring_date'];
    $res[$i]['days_to_pay'] = $row['days_to_pay'];
    $res[$i]['base_payment'] = $base_complete;
    $res[$i]['complement'] = $productivity_complete;
    $res[$i]['base_calc'] = $base_calc;
    $res[$i]['average'] = $row['average'];
    $res[$i]['advances'] = $row['advances'];
    $res[$i]['total'] = $row['total'];
    $i++;
  }
  echo(json_encode($res));
  http_response_code(200);
} else 
{
  echo($sql);
  http_response_code(400);
}
?>