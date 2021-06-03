<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    
    $date = date("YYYY-mm-dd");
    $type = ($request->type);
    $description = ($request->description);
    $id_period = ($request->id_period);
    $detail = ($request->detail);

    $sql = "INSERT INTO policies (idpolicies, id_period, id_account, close_date) VALUES (null, '$id_period', '$id_account', '$close_date');";

    if(mysqli_query($con, $sql)){
        $id_policy = mysqli_insert_id($con);
        for ($i=0; $i < count($detail); $i++) { 
            $id_account = ($detail->idaccounts);
            $amount = ($detail->amount);

            $sql2 = "INSERT INTO policy_details (idpolicy_details, id_policy, id_ccounting_account, amount) VALUES (null, '$id_policy', $id_account, $amount);";
            if(mysqli_query($con, $sql2)){
                $response = '0| Insert succefull';
                echo(mysqli_insert_id($con));
            }else{
                $response = '1| Error: ';
                echo(json_encode($response . $sql2));
                http_response_code(404);
            }
        }
    }
?>