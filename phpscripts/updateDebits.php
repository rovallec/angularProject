<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$iddebits = ($request->iddebits);
$status = ($request->status);
$type = ($request->type);
$amount = ($request->amount);
$id_user = ($request->id_user);
$id_employee = ($request->id_employee);

if($status == 'EDIT'){
    $sql = "UPDATE `debits` SET `type` = '$type', `amount` = '$amount' WHERE `iddebits` = $iddebits;";
    $sql2 = "INSERT INTO `internal_processes` (`idinternal_processes`, `id_user`, `id_employee`, `name`, `date`, `status`, `notes`) VALUES 
    (NULL, $id_user, $id_employee, 'Update debit', DATE_FORMAT(NOW(), '%Y-%m-%d'), 'COMPLETED', CONCAT('Manualy EDITED AT ', NOW(), ' | ', 
    (SELECT CONCAT_WS(',',COALESCE(iddebits, 'NULL'), COALESCE(id_payment, 'NULL'),
    COALESCE(type, 'NULL'), COALESCE(amount, 'NULL'), COALESCE(status, 'NULL')) AS `cred` FROM debits WHERE iddebits = $iddebits)));";
    if(mysqli_query($con,$sql2)){
        if(mysqli_query($con,$sql)){
            echo(json_encode('1'));
        }else{
            echo($sql);
        }
    }else{
        echo($sql2);
    }
}else{
    $sql = "UPDATE `debits` SET `status` = '$status' WHERE `iddebits` = $iddebits";
    if(mysqli_query($con,$sql)){
        echo(mysqli_insert_id($con));
    }
}
?>