<?php
header('Access-CONtrol-Allow-Origin: *');
header('Access-CONtrol-Allow-Headers: *');
require 'database.php';

$postdata = file_get_cONtents("php://input");
$request = jsON_decode($postdata);

$id_debit = ($request->iddebits);
$return = [];

$sql = "SELECT * FROM pushed_credits LEFT JOIN credits ON credits.idcredits = pushed_credits.id_credit LEFT JOIN users ON users.idUser = id_user WHERE `id_credit` = $id_debit;";

if($res = mysqli_query($cON,$sql)){
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
}
?>