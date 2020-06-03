<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
$postdata = file_get_contents("php://input");
if(isset($postdata) && !empty($postdata)){
    $request = json_decode($postdata);  

    $idproccess = ($request->idprocesses);
    $id_rol = ($request->id_role);
    $id_profile = ($request->id_profile);
    $name = ($request->name);
    $description = ($request->descritpion);
    $prc_date = ($request->prc_date);
    $status = ($request->status);
    $id_user = ($request->id_user);

    $sql = "INSERT INTO `processes`(`id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`,`status`) VALUES ('{$id_rol}', '{$id_profile}', '{$name}', '{$description}', '{$prc_date}', '{$id_user}','CLOSED');";
    if(mysqli_query($con,$sql)){
        $idproc = mysqli_insert_id($con);
        echo $idproc;
    }else{
        http_response_code(422);
    }
}
?>