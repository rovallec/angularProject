<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idcredits = ($request->iddebits);
$status = ($request->status);

if($status == 'EDIT'){
    $sql = "UPDATE `credits` SET `type` = $type, `amount` = $amount;";
    $sql2 = "INSERT INTO `internal_processes` (`idinternal_processes`, `id_user`, `id_employee`, `name`, `date`, `status`, `notes`) VALUES 
    (NULL, $id_user, $id_employee, 'Update credit', DATE_FORMAT(NOW(), '%Y-%m-%d'), 'COMPLETED', CONCAT('Manualy Deleted AT ', NOW(), ' | ', 
    (SELECT CONCAT_WS(',',COALESCE(idcredits, 'NULL'), COALESCE(id_payment, 'NULL'),
    COALESCE(type, 'NULL'), COALESCE(amount, 'NULL'), COALESCE(status, 'NULL')) AS `cred` FROM credits WHERE idcredits = $idcredits)));";
    if(mysqli_query($con,$sql2)){
        if(mysqli_query($con,$sql)){
            echo(json_encode('1'));
        }else{
            echo(json_encode(mysqli_error($con)));
        }
    }else{
        echo(json_encode(mysqli_error($con)));
    }
}else{
    $sql = "UPDATE `credits` SET `status` = '$status' WHERE `idcredits` = $idcredits";
    if(mysqli_query($con,$sql)){
        echo(mysqli_insert_id($con));
    }
}
?>