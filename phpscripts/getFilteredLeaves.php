<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';

$postdata = trim(file_get_contents("php://input"), "\xEF\xBB\xBF");
$request = json_decode($postdata);

$name = ($request->name);
$start = ($request->start);
$end = ($request->end);
$idsup = ($request->idsup);

$return = [];
$i = 0;
$and = ";";

if (ifExist($name)) {
  $and = " and p2.name like '%'$name'%'; ";
} else {
  $and = ";";
}

$sql = "SELECT 
          DISTINCT e.reporter, 
          p2.name,
          e.idemployees, 
          h.*, 
          hr.*, 
          hr.*, 
          u.*, 
          a.name AS departmet, 
          l.*, 
          pt.idprocess_types, 
          pt.name as 'type' 
        FROM hr_processes hr
        INNER JOIN leaves l ON hr.idhr_processes = l.id_process and hr.id_type = 5
        INNER JOIN process_types pt ON pt.idprocess_types = hr.id_type 
        INNER JOIN users u ON u.idUser = hr.id_user 
        INNER JOIN accounts a ON a.idaccounts = hr.id_department 
        INNER JOIN employees e ON e.idemployees = hr.id_employee 
        INNER JOIN hires h ON h.idhires = e.id_hire 
        INNER JOIN (SELECT UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) as name, p1.idprofiles from profiles p1) p2 on (p2.idprofiles = h.id_profile)
        WHERE hr.status = 'REQUESTED'
          AND (l.`start` BETWEEN '$start' AND '$end'
          OR l.`end` BETWEEN '$start' AND '$end')
          AND e.reporter = " . $idsup .
        $and;
        
if($result = mysqli_query($con, $sql)){
  while($res = mysqli_fetch_assoc($result)){
    $return[$i]['id_process'] = $res['idhr_processes'];
    $return[$i]['id_user'] = $res['id_user'];
    $return[$i]['id_employee'] = $res['idemployees'];
    $return[$i]['id_type'] = $res['type'];
    $return[$i]['id_department'] = $res['department'];
    $return[$i]['date'] = $res['date'];
    $return[$i]['notes'] = $res['notes'];
    $return[$i]['status'] = $res['status'];
    $return[$i]['motive'] = $res['motive'];
    $return[$i]['approved_by'] = $res['approved_by'];
    $return[$i]['start'] = $res['start'];
    $return[$i]['end'] = $res['end'];
    $return[$i]['dateTime'] = $res['time'];
    $return[$i]['chequed'] = 'false';
    $return[$i]['name'] = $res['name'];
    $i++;
  }
  echo(json_encode($return));
} else {
  echo(json_encode(mysqli_error($con). "<br>" . $sql));
}

?>


