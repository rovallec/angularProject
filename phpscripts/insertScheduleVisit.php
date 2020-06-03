<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");

    if(isset($postdata) && !empty($postdata)){
        $request = json_decode($postdata);
        $idprocesses = ($request->idprocesses);
        $id_role = ($request->id_role);
        $id_profile = ($request->id_profile);
        $name = ($request->name);
        $description = ($request->descritpion);
        $prc_date = ($request->prc_date);
        $status = ($request->status);
        $id_user = ($request->id_user);
        $vehicle = ($request->vehicle);
        $plate = ($request->plate);
        $dateTime = ($request->dateTime);
        $color = ($request->color);
        $brand = ($request->brand);

        $process = [];

        $sql = "INSERT INTO `processes`(`idprocesses`, `id_role`, `id_profile`, `name`, `description`, `prc_date`, `id_user`, `status`) VALUES (null, '{$id_role}', '{$id_profile}', '{$name}', '{$description}', '{$prc_date}', '{$id_user}', 'CLOSED');";

        if(mysqli_query($con, $sql)){
            $idprocesses = mysqli_insert_id($con);
            $sql2 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}','Vehicle', '{$vehicle}');";
            $sql3 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}','Plate', '{$plate}');";
            $sql4 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}','DateTime', '{$dateTime}');";
            $sql6 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}','Color', '{$color}');";
            $sql7 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}','Brand', '{$brand}');";
            $sql5 = "INSERT INTO `process_details`(`idprocess_details`, `id_process`, `name`, `value`) VALUES (null, '{$idprocesses}','Result', 'Pending');";
            if(mysqli_query($con,$sql2)){
                if(mysqli_query($con,$sql3)){
                    if(mysqli_query($con,$sql4)){
                        if(mysqli_query($con,$sql5)){
                            if(mysqli_query($con,$sql6)){
                                if(mysqli_query($con,$sql7)){
                                    echo mysqli_insert_id($con);
                            };
                        };
                    };
                };
            };
        };
    };
}
?>