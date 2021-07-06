<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$date = ($request->date);
$type = ($request->type);
$description = ($request->description);
$id_period = ($request->id_period);
$close_date = $date;
$detail = json_encode($request->detail);

$sql = "INSERT INTO policies (idpolicies, id_period, close_date, " .
                              "correlative, date, type, description) " .
        "VALUES (null, '$id_period', '$close_date', " .
                              "null, '$date', '$type', '$description');";

$transact->begin_transaction();
try {

  if($result1 = mysqli_query($transact, $sql)){
    $id_policy = mysqli_insert_id($transact);
    $detail = json_decode($detail);
    for ($i=0; $i < count($detail); $i++) { 
      $detaildecode = ($detail[$i]);
      $idaccounting_accounts = ($detaildecode->idaccounting_accounts);
      $amount = ($detaildecode->amount);
      $external_id = ($detaildecode->external_id);
      $clasif = ($detaildecode->clasif);
      $idaccounts = ($detaildecode->idaccounts);
      $id_client = ($detaildecode->id_client);
      $clientNetSuite = ($detaildecode->clientNetSuite);


      $sql2 = "INSERT INTO policy_details " .
                "(idpolicy_details, id_policy, id_ccounting_account, amount, " .
                "external_id, clasif, idaccounts, id_client, clientNetSuite) " .
              "VALUES (null, $id_policy, $idaccounting_accounts, $amount, '$external_id', " .
                "'$clasif', $idaccounts, $id_client, $clientNetSuite);";
      if($result2 = mysqli_query($transact, $sql2)){
        $response = '0| Insert succefull| |';
        echo($response);
      } else {
        $response = '1| Error: ';
        echo(json_encode($response . $sql2));
        http_response_code(404);
      }
    }
  } else {
    $error =  mysqli_error($transact);
    $response = '1| Error: ' . $sql;      
    throw new Exception($error);
  }

  if(!$result1 || !$result2 )
  {
    $transact->rollback();
  } else {
    $transact->commit();
    
    $sql3 = "select correlative from policies where idpolicies = $id_policy;";
    if($result3 = mysqli_query($con, $sql3)){   
      $row3 = $result3->fetch_assoc(); 
      $correlative = $row3['correlative'];
    } else {
      $response = '1| Error: ';
      echo(json_encode($response . $sql3));
      http_response_code(404);
    }

    $message = "0|The police was successfully saved.| $correlative | ";
    echo(json_encode($message));
  }
} catch(\Throwable $e) {  
  $error = "Error:  |The police not be saved due to the following error: |" . $e->getMessage() . "|The changes will be reversed.";
  echo(json_encode($error));
  $transact->rollback();
}

$transact->close();

?>