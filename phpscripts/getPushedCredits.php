<?php
header('Access-control-Allow-Origin: *');
header('Access-control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_debit = ($request->iddebits);
$return = [];

$sql = "SELECT * FROM pushed_credits LEFT JOIN credits on credits.idcredits = pushed_credits.id_credit LEFT JOIN users on users.idUser = id_user WHERE `id_credit` = $id_debit;";

if($res = mysqli_query($con,$sql)){
    while($row = mysqli_fetch_assoc($res)){
        $return['id_process'] = $row['idacc_processes'];
        $return['id_user'] = $row['user_name'];
        $return['id_employee'] = $row['id_employee'];
        $return['date'] = $row['date'];
        $return['notes'] = $row['notes'];
        $return['iddebits'] = $row['idcredits'];
        $return['idpayments'] = $row['id_payment'];
        $return['type'] = $row['type'];
        $return['amount'] = $row['amount'];
    }
echo(json_encode($return));
}
?>