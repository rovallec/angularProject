<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';

$res = [];

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

try {
  if (null !==$request) {
    $filter = ($request->filter);
    $value = ($request->value);
  }
} catch (exception $e) {
  $filter = 'All';
}

if ($filter=='All' || trim($filter)=='') {
  $and = "";
} else if ($filter=='name') {
  $and = " AND p2.name LIKE '%" . $value . "%'";
} else if ($filter=='account') {
  $and = " AND a.idaccounts = " . $value;
} else if ($filter=='client_id') {
  $and = " AND e.client_id LIKE '%" . $value . "%'";
} else if ($filter=='nearsol_id') {
  $and = " AND h.nearsol_id LIKE '%" . $value . "%'";
} else if ($filter=='nearsol_id') {
  $and = " AND $filter = str_to_date('$value', '%d-%m-%Y')";
}

$sql = "SELECT DISTINCT
          e.idemployees,
          h.nearsol_id,
          e.client_id, /* avaya_id */
          e.hiring_date,
          p2.name,
          u.user_name AS rep,
          a.name AS acc_name,
          r.approved_date,
          e.base_payment,
          e.productivity_payment,
          (e.base_payment + e.productivity_payment) as total
        FROM employees e
        INNER JOIN hires h ON h.idhires = e.id_hire
        INNER JOIN accounts a ON a.idaccounts = e.id_account
        INNER JOIN profiles p ON p.idprofiles = h.id_profile
        INNER JOIN users u ON u.idUser = e.reporter
        INNER JOIN (SELECT UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) as name, p1.idprofiles from profiles p1) p2 on (p2.idprofiles = p.idprofiles)
        LEFT JOIN hr_processes hp ON (e.idemployees = hp.id_employee)
        LEFT JOIN rises r ON (hp.idhr_processes = r.id_process)
        WHERE e.active  = 1
        $and
        AND e.termination_date IS null;";


if($result = mysqli_query($con, $sql)){
  $i = 0;
  while($row = mysqli_fetch_assoc($result)){
    $res[$i]['id'] = $row['idemployees'];
    $res[$i]['nearsol_id'] = $row['nearsol_id'];
    $res[$i]['avaya_id'] = $row['client_id'];
    $res[$i]['hiring_date'] = $row['hiring_date'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['reporter'] = $row['rep'];
    $res[$i]['account'] = $row['acc_name'];
    $res[$i]['approved_date'] = $row['approved_date'];
    $res[$i]['base_payment'] = $row['base_payment'];
    $res[$i]['productivity_payment'] = $row['productivity_payment'];
    $res[$i]['total'] = $row['total'];
    $i++;
  };
  echo json_encode($res);
} else {
  echo(json_encode($con->error));
  echo(json_encode($sql));
}


?>
