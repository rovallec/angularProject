<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: *');
    require 'database.php';

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $id_profile = ($request->id_profile);
    $r = [];
    $i = 0;

    $sql = "SELECT * from formerexplorer where id_profile = $id_profile;";
    if($res = mysqli_query($con, $sql)){
        while($result = mysqli_fetch_assoc($res)){
            $r[$i]['idformer_emplores'] = $result['idformer_emplores'];
            $r[$i]['id_profile'] = $result['id_profile'];
            $r[$i]['idemnization'] = $result['idemnization'];
            $r[$i]['aguinaldo'] = $result['aguinaldo'];
            $r[$i]['bono14'] = $result['bono14'];
            $r[$i]['igss'] = $result['igss'];
            $r[$i]['taxpendingpayment'] = $result['taxpendingpayment'];
            $i++;
        };
        echo(json_encode($r));
    }
?>