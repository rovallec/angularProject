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
    $end_day = $row1['end_day'];

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

  $sql2 =  "SELECT COUNT(start) AS count FROM periods WHERE start = $v_start_day AND type_period=0;";
  if ($result2 = $transact->query($sql2)) {
    $row2 = $result2->fetch_assoc();
    $count = $row2['count'];          
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error 2: " . $error . "<br>");
    throw new Exception($error);
  }
  
  if ($count == 0) {
    $sql3 =  "INSERT INTO periods (idperiods, start, end, status, type_period) VALUES (NULL, '$v_start_day', '$v_end_day', 1, 0);";
    if ($transact->query($sql3) === true) {
      
      $lastInsert = $transact->insert_id;
      $v_new_id_period = $lastInsert;
      
    } else {
      $error =  mysqli_error($transact);
      echo("<br>Error 3: " . $error . "<br>" . $sql3);
      throw new Exception($error);
    }
  }

  if ($count == 0) {
    $sql10 = "INSERT INTO payments (idpayments, id_employee, id_paymentmethod, id_period, credits, debits, date) SELECT DISTINCT NULL, e.idemployees, p.idpayment_methods, $lastInsert AS ID_PERIOD, '0.00', '0.00', null AS 'Date' 
              FROM payment_methods p
              INNER JOIN employees e ON e.idemployees = p.id_employee
              INNER JOIN accounts a on (e.id_account = a.idaccounts)
              LEFT join payments pay on (e.idemployees = pay.id_employee and pay.id_period = $lastInsert and (e.id_account is not null and pay.id_account_py is null))
              LEFT JOIN hr_processes hp ON e.idemployees = hp.id_employee 
              LEFT JOIN terminations t on hp.idhr_processes = t.id_process 
                                        AND (t.valid_from >= (select DATE_ADD(p2.`end`, INTERVAL 1 DAY) AS end FROM periods p2 WHERE p2.idperiods = $lastInsert))
              WHERE p.predeterm = 1 AND (e.active = 1 or t.valid_from IS NOT NULL)
              AND pay.id_employee is NULL
              AND a.id_client not in(2,3,7,8,9); ";
              
    if ($transact->query($sql10) === true) {
      // Proceso ejecutado correctamente, no es necesario hacer nada.
    } else {
      $error = mysqli_error($transact);
      echo("<br>Error 8: " . $error . "<br>" . $sql10);
      throw new Exception($error);
    }
  }
  
  $sql15 = "UPDATE periods SET STATUS = 0 WHERE idperiods = $id_period;";
  if ($transact->query($sql15) === true) {
    // No es necesario hacer nada.
  } else {
    $error = mysqli_error($transact);
    echo("<br>Error 12: " . $error . "<br>");
    throw new Exception($error);
  }

  if(!$result1 || !$result2 )
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