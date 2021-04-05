<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_period = ($request->id_period);

$v_start = '1899-01-01';
$v_end = '1899-01-01';
$v_end_day = '1899-01-01';
$v_start_day = '1899-01-01';
$v_id_process = 0;
$v_idattendence_adjustement = 0;
$v_new_id_period = 0;

$transact->begin_transaction();
try {  
  $sql1 = "SELECT start, end, DAY(start) AS day, MONTH(start) AS month, YEAR(start) AS year, LAST_DAY(start) AS end_day FROM periods WHERE idperiods = $id_period;";
  if ($result1 = $transact->query($sql1)) {
    $row1 = $result1->fetch_assoc();
    $v_start = $row1['start'];
    $v_end = $row1['end'];
    $day = $row1['day'];
    $month = $row1['month'];
    $year = $row1['year'];

    if ($day < 16) {
      $day = 16;
    } else {
      $day = "01";
      if ($month !=12) {
        $month = $month + 1;
        $end_day = $year . "-" . $month . "-15";
      } else {
        $month = "01";
        $year = $year + 1;
        $end_day = $year . "-" . $month . "-15";
      }
    }    
    $v_start_day = $year . "-" . $month . "-" . $day;
    $v_end_day = $end_day;    
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error 1: " . $error . "<br>");
    throw new Exception($error);
  }

  $sql2 =  "SELECT COUNT(start) AS count FROM periods WHERE START = $v_start";
  if ($result2 = $transact->query($sql2)) {
    $row2 = $result2->fetch_assoc();
    $count = $row2['count'];          
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error 2: " . $error . "<br>");
    throw new Exception($error);
  }
  
  if ($count = 0) {
    $sql3 =  "INSERT INTO periods (idperiods, start, end, status, tyipe_period) VALUES (NULL, $v_start_day, $v_end_day, 1, 0);";
    if ($transact->query($sql3) === true) {
      
      $lastInsert = $conn->insert_id;
      $v_new_id_period = $lastInsert;
      
    } else {
      $error =  mysqli_error($transact);
      echo("<br>Error 2: " . $error . "<br>");
      throw new Exception($error);
    }
  }

  if ($count = 0) {
    $sql10 =  "INSERT INTO payments (idpayments, id_employee, id_paymentmethod, id_period, credits, debits, date) " .
              "SELECT NULL, idemployees, idpayment_methods, $v_new_id_period AS ID_PERIOD, '0.00', '0.00', null FROM payment_methods " .
              "  INNER JOIN employees ON employees.idemployees = payment_methods.id_employee " .
              "WHERE predeterm = 1 AND active = 1;";
    if ($transact->query($sql10) === true) {
      // Proceso ejecutado correctamente, no es necesario hacer nada.
    } else {
      $error =  mysqli_error($transact);
      echo("<br>Error 8: " . $error . "<br>" . $sql10);
      throw new Exception($error);
    }
  }
  
  $sql15 = "UPDATE periods SET STATUS = 0 WHERE idperiods = $id_period;";
  if ($transact->query($sql15) === true) {
    // No es necesario hacer nada.
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error 12: " . $error . "<br>");
    throw new Exception($error);
  }

  if(!$result1 || !$result2 || !$result13 )
  {
    $transact->rollback();
  } else {
    $transact->commit();
    $message = "Info:|The period was successfully closed.| | ";
    echo(json_encode($message));
  }
} catch(\Throwable $e) {  
  $error = "Error:  |The period could not be closed due to the following error: |" . $e->getMessage() . "|The changes will be reversed.";  
  echo(json_encode($error));
  $transact->rollback();
}

$transact->close();
?>