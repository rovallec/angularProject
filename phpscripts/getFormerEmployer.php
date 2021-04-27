<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

//    $id_employee = ($request->id_employee);
$r = [];
$i = 0;

$sql = "select 
          f.*,
          concat(trim(p.first_name), ' ', trim(p.second_name), ' ', trim(p.first_lastname), ' ', trim(p.second_lastname)) as name
        from formeremployer f 
        inner join employees e on (f.id_employee = e.idemployees)
        inner join hires h on (h.idhires = e.id_hire)
        inner join profiles p on (h.id_profile = p.idprofiles);";
if($res = mysqli_query($con, $sql)){
  while($result = mysqli_fetch_assoc($res)){
    $r[$i]['idformer_employes'] = $result['idformer_employes'];
    $r[$i]['name'] = $result['name'];
    $r[$i]['id_employee'] = $result['id_employee'];
    $r[$i]['indemnization'] = $result['indemnization'];
    $r[$i]['aguinaldo'] = $result['aguinaldo'];
    $r[$i]['bono14'] = $result['bono14'];
    $r[$i]['igss'] = $result['igss'];
    $r[$i]['taxpendingpayment'] = $result['taxpendingpayment'];
    $i++;
  };
  echo(json_encode($r));
} else {
  $r = $sql;
  echo(json_encode($r));
}
?>