<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->id_employee)
$term = '';
$sql = "SELECT * FROM `terminations` LEFT JOIN `hr_processes` ON `hr_processes`.`idprocesses` = `terminations`.`id_process` WHERE `id_employee`=$id;";
if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $term = $row['emition_date'];
    }
    echo($term);
}
?>