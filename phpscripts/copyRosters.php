<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_from = ($request->id_from);
$id_to = ($request->id_to);
$max = ($request->max_week);
$id_employee = '';
$count = 0;
$sql_1 = '';
$eof = 0;
$error = '';

$sql = "SELECT * FROM rosters WHERE id_period = $id_from ORDER BY id_employee, idrosters;";

$transact->begin_transaction();

if($res = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($res)){
        if($id_employee != $row['id_employee']){
            while($count <= $max){
                if($transact->query($sql_1)){
                    $count++;
                    $eof++;
                }else{
                    echo($sql_1);
                }
            }
            $count = 0;
        }
        $id_employee = $row['id_employee'];
        $id_type = $row['id_type'];
        $week_value = $row['week_value'];
        if($count <= $max){
            $sql_1 = "INSERT INTO rosters VALUES (NULL, $id_employee, $id_to, $id_type, $week_value);";
            if($transact->query($sql_1)){
                $count++;
                $eof++;
            }else{
                echo($sql_1);
            }
        }
    }
    if(mysqli_num_rows($res) == $eof){
        $transact->commit();
    }else{
        $error = mysqli_error($transact);
        $transact->rollback();
        echo($error);
    }
}else{
    http_response_code(404);
    echo($sql);
}
?>