<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idcredits = ($request->iddebits);
$status = ($request->status);
$type = ($request->type);
$notes = ($request->notes);
$amount = ($request->amount);

if($status == 'EDIT'){
    $sql = "UPDATE `credits` SET `type` = '$type', `amount` = '$amount', `notes` = '$notes' WHERE `idcredits` = $idcredits;";
    $sql2 = "INSERT INTO `internal_processes` (`idinternal_processes`, `id_user`, `id_employee`, `name`, `date`, `status`, `notes`) VALUES 
    (NULL, $id_user, $id_employee, 'Update credit', DATE_FORMAT(NOW(), '%Y-%m-%d'), 'COMPLETED', CONCAT('Manualy Deleted AT ', NOW(), ' | ', 
    (SELECT CONCAT_WS(',',COALESCE(idcredits, 'NULL'), COALESCE(id_payment, 'NULL'),
    COALESCE(type, 'NULL'), COALESCE(amount, 'NULL'), COALESCE(status, 'NULL')) AS `cred` FROM credits WHERE idcredits = $idcredits)));";
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
    $sql = "UPDATE `credits` SET `status` = '$status' WHERE `idcredits` = $idcredits";
    if(mysqli_query($con,$sql)){
        echo(mysqli_insert_id($con));
    }
}
?>