<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id = ($request->idemployees);

$term = [];
$sql = "SELECT * FROM `terminations` LEFT JOIN `hr_processes` ON `hr_processes`.`idhr_processes` = `terminations`.`id_process` WHERE `id_employee`=$id;";
if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $term['valid_from'] = $row['valid_from'];
    }
    echo(json_encode($term));
}
?>