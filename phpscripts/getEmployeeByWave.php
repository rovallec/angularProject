<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require  'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id_wave = ($request->idwaves);

$res = [];
$i = 0;

$sql =  "select p.idprofiles, " .
        "e.idemployees, " .
        "e.id_account, " .
        "a.name as account, " .
        "e.job, " .
        "p2.name, " .
        "p.day_of_birth, " .
        "e.client_id, " .
        "h.nearsol_id, " .
        "e.hiring_date, " .
        "e.state, " .
        "e.active " .
        "from employees e " .
        "inner join hires h ON e.id_hire = h.idhires " .
        "inner join profiles p on h.id_profile = p.idprofiles " .
        "inner join (select c.idprofiles, (UPPER(CONCAT(TRIM(c.first_name), ' ', TRIM(c.second_name), ' ', TRIM(c.first_lastname), ' ', TRIM(c.second_lastname)))) as name from profiles c) p2 on (p.idprofiles = p2.idprofiles) " .
        "inner join accounts a on (e.id_account=a.idaccounts) " .
        "where h.id_wave = $id_wave " .
        "and e.active = 1;";

if($request = mysqli_query($con,$sql)){
  while($row = mysqli_fetch_assoc($request)){
    $res[$i]['id_profile'] = $row['idprofiles'];
    $res[$i]['idemployees'] = $row['idemployees'];    
    $res[$i]['id_account'] = $row['id_account'];
    $res[$i]['account'] = $row['account'];
    $res[$i]['job'] = $row['job'];
    $res[$i]['name'] = $row['name'];
    $res[$i]['day_of_birth'] = $row['day_of_birth'];
    $res[$i]['client_id'] = $row['client_id'];
    $res[$i]['nearsol_id'] = $row['nearsol_id'];
    $res[$i]['hiring_date'] = $row['hiring_date'];
    $res[$i]['state'] = $row['state'];
    $res[$i]['active'] = $row['active'];
    $i++;
  }
  echo(json_encode($res));
} else {
  echo(json_encode($sql));
}
?>