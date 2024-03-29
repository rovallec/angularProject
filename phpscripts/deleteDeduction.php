<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$idcredits = ($request->iddebits);
$status = ($request->status);
$idpayments = ($request->idpayments);
$id_user = ($request->id_user);

if($status == 'Credit'){
    $sql = "DELETE FROM `credits` WHERE `idcredits` = $idcredits AND `id_payment` = $idpayments";
    $sql1 = "UPDATE `pushed_credits` SET `notes` = CONCAT(`notes`, ' | DELETED BY $id_user AT ', NOW()) WHERE `id_credit` = $idcredits;";
}else{
    $sql = "DELETE FROM `debits` WHERE `iddebits` = $idcredits AND `id_payment` = $idpayments";
    $sql1 = "UPDATE `pushed_debits` SET `notes` = CONCAT(`notes`, ' | DELETED BY $id_user AT ', NOW()) WHERE `id_debit` = $idcredits;";
}

if(mysqli_query($con,$sql1)){
    if(mysqli_query($con,$sql)){
        echo(json_encode('1'));
    }else{
        echo($sql);
    }
}else{
    echo($sql1);
}
?>