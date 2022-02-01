<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header("Pragma: public");
header("Expires: 0");
$filename = "exportSalaryReport.xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
require 'database.php';
require 'funcionesVarias.php';

try {
  $filter = $_GET['filter'];
  $value = $_GET['value'];
} catch (exception $e) {
  $filter = 'All';
}

if ($filter=='All' || trim($filter)=='' || trim($value)=='') {
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

$i = 0;

$title = ['ID', 'Nearsol ID', 'Avaya ID', 'Name', 'Joining Date', 'Reporter', 'Account', 'Last Update / Rise', 'Base Payment', 'Productivity Payment', 'Total'];

echo "
  <table border='1'>
    <thead style='background-color: #203764; color:white;'>
      <tr>";
        foreach($title as $fila) {
          echo "<td style='font-size:14px; background-color: #203764; color:white;font-weight:normal;'>";
            echo $fila;
          echo "</td>";
        }
echo "</tr></thead>
    <tbody>";

$sql = "SELECT DISTINCT
          e.idemployees,
          h.nearsol_id,
          e.client_id, /* avaya_id */
          DATE_FORMAT(e.hiring_date,'%d-%m-%Y') AS hiring_date,
          UPPER(p2.name) AS name,
          UPPER(u.user_name) AS rep,
          a.name AS acc_name,
          DATE_FORMAT(r.approved_date,'%d-%m-%Y') AS approved_date,
          FORMAT(e.base_payment,2) AS base_payment,
          FORMAT(e.productivity_payment,2) AS productivity_payment,
          FORMAT((e.base_payment + e.productivity_payment),2) AS total
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

if($result = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($result)){
    $exportRow[0] = $row['idemployees'];
    $exportRow[1] = $row['nearsol_id'];
    $exportRow[2] = $row['client_id'];
    $exportRow[3] = $row['name'];
    $exportRow[4] = $row['hiring_date'];
    $exportRow[5] = $row['rep'];
    $exportRow[6] = $row['acc_name'];
    $exportRow[7] = $row['approved_date'];
    $exportRow[8] = $row['base_payment'];
    $exportRow[9] = $row['productivity_payment'];
    $exportRow[10] = $row['total'];
    $i++;

    // Imprime en el cuerpo de la tabla la información de los empleados.
    echo "<tr>";
    for ($i=0; $i < count($exportRow); $i++) {
      echo "<td>";
      echo $exportRow[$i];
      echo "</td>";
    }
    echo"</tr>";
  };
echo "</tbody></table>";
} else {
  echo json_decode($sql);
  http_response_code(408);
}

// exportSalaryReport.php
?>
