<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_employee = ($request->idemployees);

$result = [];
$i = 0;
$sql = "SELECT a.idpayment_methods,
          a.id_employee,
          a.`type`,
          a.`number`,
          a.bank,
          a.predeterm,
          b.idmodify_payment_methods,
          b.id_payment_method,
          b.id_user,
          b.`date`,          
          b.notes,
          u.user_name
        FROM payment_methods a 
          LEFT JOIN modify_payment_methods b ON (a.idpayment_methods = b.id_payment_method)
          LEFT JOIN users u on (b.id_user = u.idUser)
        WHERE a.`id_employee` = $id_employee;";

if($res = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($res)){
        $result[$i]['idpayment_methods'] = $row{'idpayment_methods'};
        $result[$i]['id_employee'] = $row{'id_employee'};
        $result[$i]['type'] = $row{'type'};
        $result[$i]['number'] = $row{'number'};
        $result[$i]['bank'] = $row{'bank'};
        $result[$i]['predeterm'] = $row{'predeterm'};
        $result[$i]['id_user'] = $row{'user_name'};
        $result[$i]['date'] = $row{'date'};
        $result[$i]['notes'] = $row{'notes'};
        $i = $i + 1;
    }
    echo(json_encode($result));
} else {
  echo(json_encode($sql));
  http_response_code(400);
}
?> 