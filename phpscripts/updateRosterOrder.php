<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_old_1 = ($request->id_old_1);
$id_old_2 = ($request->id_old_1);

$transact->begin_transaction();
$error = '';

$sql = "SELECT idrosters FROM rosters ORDER BY idrosters DESC LIMIT 1;";

if($result = $transact->query($sql)){
    $row = $result->fetch_assoc();
    $id_new_1 = ((int)$row['idrosters']) + 1;
    $id_new_2 = ((int)$row['idrosters']) + 2;
    $sql2 = "UPDATE rosters SET idrosters = $id_new_1 WHERE idrosters = $id_old_1;";
    $sql3 = "UPDATE rosters SET idrosters = $id_new_2 WHERE idrosters = $id_old_2;";
    if($result = $transact->query($sql2)){
        if($result = $transact->query($sql3)){
            $sql4 = "UPDATE rosters SET idrosters = $id_old_2 WHERE idrosters = $id_new_1;";
            $sql5 = "UPDATE rosters SET idrosters = $id_old_1 WHERE idrosters = $id_new_2;";
            echo($sql . $sql2 . $sql3 . $sql4 . $sql5);
            if($result = $transact->query($sql4)){
                if($result = $transact->query($sql5)){
                    $transact->commit();
                    echo("1");
                }else{
                    $error = mysqli_error($transact);
                    $transact->rollback();
                    echo($error);
                }
            }else{
                $error = mysqli_error($transact);
                $transact->rollback();
                echo($error);
            }
        }else{
            $error = mysqli_error($transact);
            $transact->rollback();
            echo($error);
        }
    }else{
        $error = mysqli_error($transact);
        $transact->rollback();
        echo($error);
    }
}else{
    $error = mysqli_error($transact);
    $transact->rollback();
    echo($error);
}
?>