<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$id = ($request->idprocesses);

$user = [];

$sql = "SELECT `reports`.*, `solutions`.`idsolutions`, `solutions`.`id_report`, `solutions`.`description` AS `s_description`, `solutions`.`approved_by` FROM `reports` LEFT JOIN `solutions` ON `solutions`.`id_report` = `reports`.`idreports` WHERE `id_process` = '$id';";

if($result = mysqli_query($con, $sql)){
    while($row = mysqli_fetch_assoc($result)){
        $user['idreports'] = $row['idreports'];
        $user['id_process'] = $row['id_process'];
        $user['tittle'] = $row['tittle'];
        $user['description'] = $row['description'];
        $user['classification'] = $row['classification'];
        $user['idsolutions'] = $row['idsolutions'];
        $user['id_report'] = $row['id_report'];
        $user['s_description'] = $row['s_description'];
        $user['approved_by'] = $row['approved_by'];
    };
    echo json_encode($user);
}else{
    http_response_code(404);
}

?>
