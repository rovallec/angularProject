<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'databaseSQL.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$head = json_encode($request->head);
$head2 = (json_decode($head));
$seconds = 4; // Time to sleep in each cycle.

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
$payment = $head2->payment;

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

    $sql2 = " INSERT INTO checks " .
              "(idchecks, place, date, value, name, description, negotiable, " .
              "  nearsol_id, client_id, id_account, document, bankAccount, " .
              "  printDetail, payment) " .
              "VALUES (null, '$place', str_to_date('$date','%d-%m-%Y'), $value, '$name', '$description', '$negotiable', " .
              "  '$nearsol_id', '$client_id', '$id_account', '$document', '$bankAccount', " .
              "  $printDetail, $payment);";

    if ($res2 = $transact->query($sql2) === true) {
      $id_check = $transact->insert_id;

      /* ********************* */
      /* ****** DETAILS ****** */
      /* ********************* */

      $details = ($request->detail);

      $c = 0;
      foreach ($details as $detail) {
        $c++;
        $id_detail = $detail->id_detail;
        $id_accountdet = $detail->id_account;
        $namedet = $detail->name;
        $id_movement = $detail->id_movement;
        $movement = $detail->movement;
        $debits = $detail->debits;
        $credits = $detail->credits;
        //$value = $credits + $debits;

        $sql3 = " INSERT INTO checks_details (id_detail, id_check, id_account, name, id_movement, movement, debits, credits) " .
                " VALUES ($id_detail, $id_check, '$id_accountdet', '$namedet', '$id_movement', '$movement', $debits, $credits);";

        if ($res3 = $transact->query($sql3) === true) {
          if (count($details) == $c) {
            http_response_code(200);
          }
        } else {
          $error = mysqli_error($transact);
          $error = '423 -> ' . $error . $sql3;

          echo("<br>Error : " . $error . "<br>");
          throw new Exception($error);
        } // end if SQL 3
      } // End foreach
      //
      //
      // proceso de grabado en SQL Server.
      //
      //

      try{
        $c = 0;
        $dateSQL = explode('-', $date)[2].'-'.explode('-', $date)[1].'-'.explode('-', $date)[0];
        if( $connSQL ) {
          $sqlSrv2 = " INSERT INTO MiNearsol_local.dbo.checks " .
                    "(idchecks, place, date, value, name, description, negotiable, " .
                    "  nearsol_id, client_id, id_account, document, bankAccount, " .
                    "  printDetail, payment, printed) " .
                    "VALUES ($id_check, '$place', '$dateSQL', $value, '$name', '$description', ".
                    "'$negotiable', '$nearsol_id', '$client_id', '$id_account', '$document', " .
                    "'$bankAccount', $printDetail, $payment, 0);";

          $qty = 0;
          $id = 0;
          $params = array( &$qty, &$id);
          $prepare2 = sqlsrv_prepare( $connSQL, $sqlSrv2, [0, 0]);

          if (sqlsrv_execute($prepare2) === true) {
            foreach ($details as $detailSQL) {
              $c++;

              $id_detail = $detailSQL->id_detail;
              $id_accountdet = $detailSQL->id_account;
              $namedet = $detailSQL->name;
              $id_movement = $detailSQL->id_movement;
              $movement = $detailSQL->movement;
              $debits = $detailSQL->debits;
              $credits = $detailSQL->credits;
              //$value = $credits + $debits;

              $sqlSrv3 =  "INSERT INTO MiNearsol_local.dbo.checks_details (id_detail, id_check, id_account, name, id_movement, movement, debits, credits) " .
                          "VALUES ($id_detail, $id_check, '$id_accountdet', '$namedet', $id_movement, '$movement', $debits, $credits);";


              $prepare3 = sqlsrv_prepare( $connSQL, $sqlSrv3, [0, 0]);
              if ( sqlsrv_execute($prepare3) === true) {
                if (count($details) == $c) {
                  echo(json_encode("Success | " . $id_check . " | Proceso ejecutado correctamente."));
                  http_response_code(200);
                }
              } else {
                $error = sqlsrv_errors();
                $error = '433 -> ' . $error . $sqlSrv3;
                echo("<br>Error : " . $error . "<br>");
                throw new Exception($error);
                http_response_code(433);
              }  // end if SQL 3
            } // End foreach
          } else {
            //$error = sqlsrv_errors();
              $error = '432 -> ' . $sqlSrv2 . $c;
              echo("<br>Error : " . $error . "<br>");
              throw new Exception($error);
              http_response_code(432);
          };
        } else {
          $error = sqlsrv_errors();
          $error = '431 -> ' . $error;
          echo("<br>Error : " . $error . "<br>");
          throw new Exception($error);
          http_response_code(431);
        }
      } catch(\Throwable $e) {
        $error = "Error: | Unable to insert the check in sqlsrv due to the following error: " . $e->getMessage() . "|The changes will be reversed.";
        echo(json_encode($error));
        $transact->rollback();
        http_response_code(430);
      } finally {
        sqlsrv_close($connSQL);  
        //sleep($seconds);
      }
      
      //
      //
      // proceso de grabado en SQL Server.
      //
      //

      http_response_code(200);
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
