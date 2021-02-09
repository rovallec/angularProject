<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$filter = ($request->filter);
$value = ($request->value);
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
    (-1 * SUM(COALESCE(ad.amount, 0.00))) AS advances,
    0 AS total
  FROM employees a
  INNER JOIN users b ON (a.reporter = b.idUser)
  INNER JOIN accounts c ON (a.id_account = c.idaccounts)
  INNER JOIN hires d ON (a.id_hire = d.idhires)          
  INNER JOIN profiles e ON (e.idprofiles = d.id_profile) 
  INNER JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  INNER JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN advances ad on (hr.idhr_processes = ad.id_process)
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
    (-1 * SUM(COALESCE(ad.amount, 0.00))) AS advances,
    0 AS total
  FROM employees a
  INNER JOIN users b ON (a.reporter = b.idUser)
  INNER JOIN accounts c ON (a.id_account = c.idaccounts)
  INNER JOIN hires d ON (a.id_hire = d.idhires)
  INNER JOIN (SELECT CONCAT(TRIM(p.first_name), ' ', TRIM(p.second_name), ' ', TRIM(p.first_lastname), ' ', TRIM(p.second_lastname)) AS name, p.idprofiles from profiles p) e ON (e.idprofiles = d.id_profile) 
  INNER JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  INNER JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN advances ad on (hr.idhr_processes = ad.id_process)
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
    (-1 * SUM(COALESCE(ad.amount, 0.00))) AS advances,
    0 AS total
  FROM employees a
  INNER JOIN users b ON (a.reporter = b.idUser)
  INNER JOIN accounts c ON (a.id_account = c.idaccounts)
  INNER JOIN hires d ON (a.id_hire = d.idhires)
  INNER JOIN (SELECT CONCAT(TRIM(p.first_name), ' ', TRIM(p.second_name), ' ', TRIM(p.first_lastname), ' ', TRIM(p.second_lastname)) AS name, p.dpi, p.idprofiles from profiles p) e ON (e.idprofiles = d.id_profile) 
  INNER JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  INNER JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN advances ad on (hr.idhr_processes = ad.id_process)
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
    (-1 * SUM(COALESCE(ad.amount, 0.00))) AS advances,
    0 AS total
  FROM employees a
  INNER JOIN users b ON (a.reporter = b.idUser)
  INNER JOIN accounts c ON (a.id_account = c.idaccounts)
  INNER JOIN hires d ON (a.id_hire = d.idhires)
  INNER JOIN (SELECT CONCAT(TRIM(p.first_name), ' ', TRIM(p.second_name), ' ', TRIM(p.first_lastname), ' ', TRIM(p.second_lastname)) AS name, p.dpi, p.idprofiles from profiles p) e ON (e.idprofiles = d.id_profile) 
  INNER JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  INNER JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN advances ad on (hr.idhr_processes = ad.id_process)
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
    (-1 * SUM(COALESCE(ad.amount, 0.00))) AS advances,
    0 AS total
  FROM employees a
  INNER JOIN users b ON (a.reporter = b.idUser)
  INNER JOIN accounts c ON (a.id_account = c.idaccounts)
  INNER JOIN hires d ON (a.id_hire = d.idhires)
  INNER JOIN (SELECT CONCAT(TRIM(p.first_name), ' ', TRIM(p.second_name), ' ', TRIM(p.first_lastname), ' ', TRIM(p.second_lastname)) AS name, p.dpi, p.idprofiles from profiles p) e ON (e.idprofiles = d.id_profile) 
  INNER JOIN hr_processes HR ON (a.idemployees = hr.id_employee)
  INNER JOIN terminations t ON (hr.idhr_processes = t.id_process)
  LEFT JOIN advances ad on (hr.idhr_processes = ad.id_process)
  WHERE d.nearsol_id LIKE '%$value%'
  LIMIT 50;";  
}

if($request = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($request)){
    $res[$i]['idemployees'] = $row['idemployees'];
    $res[$i]['nearsol_id'] = $row['nearsol_id'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['account'] = $row['account'];
    $res[$i]['hiring_date'] = $row['hiring_date'];
    $res[$i]['days_to_pay'] = $row['days_to_pay'];
    $res[$i]['base_payment'] = $row['base_payment'];
    $res[$i]['complement'] = $row['complement'];
    $res[$i]['base_calc'] = $row['base_calc'];
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