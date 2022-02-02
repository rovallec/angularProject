<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$head = json_encode($request->head);
$head2 = (json_decode($head));


/* ********************* */
/* **** checkbooks ***** */
/* ********************* */
$place = $head2->place;
$date = $head2->date;
$value = $head2->value;
$name = $head2->name;
$description = $head2->description;
$negotiable = $head2->negotiable;
$nearsol_id = $head2->nearsol_id;
$client_id = $head2->client_id;
$id_account = $head2->id_account;
$document = $head2->document;
$bankAccount = $head2->bankAccount;
$printDetail = $head2->printDetail;

if ($printDetail=='True') {
  $printDetail = 1;
} else {
  $printDetail = 0;
}


if ($printDetail=='True') {
  $printDetail = 1;
} else {
  $printDetail = 0;
}

$next_correlative = $document + 1;
$sql1 = "UPDATE checkbook SET " .
        "next_correlative = $next_correlative " .
        "WHERE account_bank = '$bankAccount';";
try 
{
  if ($res1 = $transact->query($sql1) === true) {
    //$row1 = $res1->fetch_assoc();

    $sql2 = " INSERT INTO checks " .
              "(idchecks, place, date, value, name, description, negotiable, " .
              "  nearsol_id, client_id, id_account, document, bankAccount, " .
              "  printDetail) " .
              "VALUES (null, '$place', STR_TO_DATE('$date','%d-%m-%Y'), $value, '$name', '$description', '$negotiable', " . 
              "  '$nearsol_id', '$client_id', '$id_account', '$document', '$bankAccount', " .
              "  $printDetail);";


    if (($res2 = $transact->query($sql2))) {
          $id_check = $transact->insert_id;
          echo($id_check);
          

      /* ********************* */
      /* ****** DETAILS ****** */
      /* ********************* */
      $detail1 = json_encode($request->detail);
      $details = json_decode($detail1); /* {"detail": [{"id_detail": "1", "name": "richard"}]} */
      echo($details);
      foreach ($details as $detail) {
        $id_detail = $detail->id_detail;
        $id_account = $detail->id_account;
        $name = $detail->name;
        $id_movement = $detail->id_movement;
        $movement = $detail->movement;
        $debits = $detail->debits;
        $credits = $detail->credits;
        $value = $credits + $debits;
        
        $sql3 = " INSERT INTO checks_details (id_detail, id_check, id_account, name, id_movement, movement, debits, credits) " .
                " VALUES ($id_detail, $id_check, '$id_account', '$name', '$id_movement', '$movement', $debits, $credits);";
        echo($sql3);
        if ($res3 = $transact->query($sql3) === true) {
          echo("Success | $id_check | Proceso ejecutado correctamente.");
          http_response_code(200);
        } else {
          $error = mysqli_error($transact);
          $error = '423 -> ' . $error . $sql3;

          echo("<br>Error : " . $error . "<br>");
          throw new Exception($error);
          http_response_code(423);
        } // end if SQL 3
      } // End foreach      
    } else {
      $error = mysqli_error($transact);
      $error = '422 -> ' . $error . $sql2;
      echo("<br>Error : " . $error . "<br>");
      throw new Exception($error);
      http_response_code(422);
    } // end if SQL 2
  } else {
    $error = mysqli_error($transact);
    $error = '421 -> ' . $error . $sql1;
    echo("<br>Error : " . $error . "<br>");
    throw new Exception($error);
    http_response_code(421);
  }
} catch(\Throwable $e) {
  $error = "Error: |Unable to update the check due to the following error: |" . $e->getMessage() . "|The changes will be reversed.";  
  echo(json_encode($error));
  $transact->rollback();
  http_response_code(424);
}

$transact->close();

?>