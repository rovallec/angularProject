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
  $sql = "SELECT DISTINCT
    a.idemployees,
    d.nearsol_id,
    e.name,
    c.name AS account,
    a.hiring_date,
    DATEDIFF(per.end, IF(a.hiring_date>per.start, a.hiring_date, per.start)) + 1 AS days_to_pay,
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
  LEFT JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  LEFT JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN (SELECT * FROM periods WHERE type_period = 1) per on (CURDATE() BETWEEN per.start AND per.end)
  LEFT JOIN (SELECT SUM(ad.amount) AS amount, ad.id_process from advances ad group by ad.id_process) adv on (hr.idhr_processes = adv.id_process)
  ORDER BY a.hiring_date DESC
  LIMIT 50;";
} elseif ($filter === 'name') {
  $sql = "SELECT DISTINCT
    a.idemployees,
    d.nearsol_id,
    e.name,
    c.name AS account,
    a.hiring_date,
    DATEDIFF(per.end, IF(a.hiring_date>per.start, a.hiring_date, per.start)) + 1 AS days_to_pay,
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
  LEFT JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  LEFT JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN (SELECT * FROM periods WHERE type_period = 1) per on (CURDATE() BETWEEN per.start AND per.end)
  LEFT JOIN (SELECT SUM(ad.amount) AS amount, ad.id_process from advances ad group by ad.id_process) adv on (hr.idhr_processes = adv.id_process)
  WHERE e.name LIKE '%$value%'
  LIMIT 50;";  
} elseif ($filter === 'dpi') {
  $sql = "SELECT DISTINCT
    a.idemployees,
    d.nearsol_id,
    e.name,
    c.name AS account,
    a.hiring_date,
    DATEDIFF(per.end, IF(a.hiring_date>per.start, a.hiring_date, per.start)) + 1 AS days_to_pay,
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
  LEFT JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  LEFT JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN (SELECT * FROM periods WHERE type_period = 1) per on (CURDATE() BETWEEN per.start AND per.end)
  LEFT JOIN (SELECT SUM(ad.amount) AS amount, ad.id_process from advances ad group by ad.id_process) adv on (hr.idhr_processes = adv.id_process)
  WHERE e.dpi LIKE '%$value%'
  LIMIT 50;";  
} elseif ($filter === 'client_id') {
  $sql = "SELECT DISTINCT
    a.idemployees,
    d.nearsol_id,
    e.name,
    c.name AS account,
    a.hiring_date,
    DATEDIFF(per.end, IF(a.hiring_date>per.start, a.hiring_date, per.start)) + 1 AS days_to_pay,
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
  LEFT JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  LEFT JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN (SELECT * FROM periods WHERE type_period = 1) per on (CURDATE() BETWEEN per.start AND per.end)
  LEFT JOIN (SELECT SUM(ad.amount) AS amount, ad.id_process from advances ad group by ad.id_process) adv on (hr.idhr_processes = adv.id_process)
  WHERE c.id_client LIKE '%$value%'
  LIMIT 50;";  
} elseif ($filter === 'nearsol_id') {
  $sql = "SELECT DISTINCT
    a.idemployees,
    d.nearsol_id,
    e.name,
    c.name AS account,
    a.hiring_date,
    DATEDIFF(per.end, IF(a.hiring_date>per.start, a.hiring_date, per.start)) + 1 AS days_to_pay,
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
  LEFT JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  LEFT JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN (SELECT * FROM periods WHERE type_period = 1) per on (CURDATE() BETWEEN per.start AND per.end)
  LEFT JOIN (SELECT SUM(ad.amount) AS amount, ad.id_process from advances ad group by ad.id_process) adv on (hr.idhr_processes = adv.id_process)
  WHERE d.nearsol_id LIKE '%$value%'
  LIMIT 50;";  
}

function IsEmpty($adato) {
  if (empty($adato) || is_null($adato) || !isset($adato)) { 
      $adato = 0.00;
  } 
  return (double)$adato;
}

if($request = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($request)){
    $idemployees = $row['idemployees'];    

    $sql2 = "SELECT y.* FROM (
      SELECT 
        z.idemployees,
        SUM(z.base_complete) AS base_complete,
        SUM(z.productivity_complete) AS productivity_complete,
        COUNT(z.idemployees) AS counting
        FROM (   
        SELECT 
          a.idemployees,
          pay.base_complete,
          pay.productivity_complete  
        FROM employees a
        LEFT JOIN payments pay  ON (pay.id_employee = a.idemployees) 
        LEFT JOIN periods per ON (pay.id_period = per.idperiods AND per.end >= a.hiring_date 
                                    AND (pay.base_complete > 0 AND pay.productivity_complete > 0)) ".
      "  WHERE a.idemployees = " . $idemployees .
      "  AND pay.base_complete IS NOT NULL
        ORDER BY a.idemployees, per.idperiods DESC LIMIT 0, 24
      ) z GROUP BY z.idemployees
      UNION ALL
      SELECT e.idemployees, e.base_payment, e.productivity_payment, 1 FROM employees e WHERE e.idemployees = " . $idemployees .
      ") y LIMIT 0, 1;";
            
    if($request2 = mysqli_query($con,$sql2)){
      if (!isset($request2) || empty($request2) || is_null($request2)) {        
        $base_complete = 0;
        $productivity_complete = 0;
        $base_calc = 0;
      } else {
        $row2 = mysqli_fetch_assoc($request2);
        if (!empty($row2) || !is_null($row2) || isset($row2)) {
          $counting = IsEmpty($row2['counting']);
          
          if ($counting == 0) {
            $base_complete = IsEmpty($row2['base_complete']);
            $productivity_complete = IsEmpty($row2['productivity_complete']);
          } else {
            $base_complete = IsEmpty($row2['base_complete']);
            $base_complete = $base_complete / $counting;
            $productivity_complete = IsEmpty($row2['productivity_complete']) / $counting;
          } 
        } else {
          $base_complete = 0;
          $productivity_complete = 0;
        }
      }
    } else {
      $base_complete = 0;
      $productivity_complete = 0;      
    }

    $res[$i]['idemployees'] = $row['idemployees'];
    $res[$i]['nearsol_id'] = $row['nearsol_id'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['account'] = $row['account'];
    $res[$i]['hiring_date'] = $row['hiring_date'];
    $res[$i]['days_to_pay'] = $row['days_to_pay'];
    $res[$i]['base_payment'] = round($base_complete / 365 * $row['days_to_pay'], 2);
    $res[$i]['complement'] = round($productivity_complete / 365 * $row['days_to_pay'], 2);
    $res[$i]['base_calc'] = round($res[$i]['base_payment'] + $res[$i]['complement'], 2);
    $res[$i]['average'] = round($res[$i]['base_calc'], 2);
    $res[$i]['advances'] = round($row['advances'], 2);
    $res[$i]['total'] = round($res[$i]['base_calc'] - $res[$i]['advances'], 2);
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