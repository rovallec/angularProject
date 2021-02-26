<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_period = ($request->idperiods);
  
  $v_start = '1899-01-01';
  $v_end_day = '1899-01-01';
  $v_id_process = 0;
  $v_idattendence_adjustement = 0;
  $v_new_id_period = 0;
  
  $sql1 = "SELECT start, end, DAY(start) AS day, MONTH(start) AS month, YEAR(start) AS year, LAST_DAY(start) AS end_day FROM periods WHERE idperiods = $id_period;";
  
$transact->begin_transaction();
try {
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
      $end_day = $year . "-" . $month . "-15";

      if ($month !=12) {
        $month = $month + 1;
      } else {
        $month = 1;
        $year = $year + 1;
        $end_day = $year . "-" . $month . "-15";
      }
    }
    echo("Prueba error: " .$end_day);
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
    $sql3 =  "INSERT INTO periods (idperiods, start, end, status, tyipe_period) VALUES (NULL, $v_start, $v_end_day, 1, 0);";
    if ($result3 = $transact->query($sql3)) {
      $lastInsert = $conn->insert_id;
      $v_new_id_period = $lastInsert;
    } else {
      $error =  mysqli_error($transact);
      echo("<br>Error 2: " . $error . "<br>");
      throw new Exception($error);
    }

    $sql5 =  "SELECT " .
                "  attendence_adjustemnt.idattendence_adjustemnt, " .
                "  attendence_justifications.id_process " .
                "from attendence_adjustemnt " .
                "  INNER JOIN attendence_justifications ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification " .
                "  INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process " .
                "WHERE hr_processes.date BETWEEN $v_start AND $v_end_day " .
                "  AND attendence_adjustemnt.state = 'PENDING';";

    if ($result5 = $transact->query($sql5)) {
      while($row5 = $result->fetch_assoc()){
        $v_idattendence_adjustement = $row5['idattendence_adjustemnt'];
        $v_id_process = $row5['id_process'];

        $sql6 = "UPDATE hr_processes SET " .
                "status = 'COMPLETED', " .
                "notes = CONCAT( 'CLOSED ON END OF PERIOD ' $v_end ,' | ', notes), " .
                "id_period = $id_period " .
                "WHERE idhr_processes = $v_id_process; ";   
        $sql7 = "UPDATE attendence_adjustemnt SET  " .
                "state =  'COMPLETED' " .
                "WHERE idattendence_adjustemnt = $v_idattendence_adjustement;";
        if ($result6 = $transact->query($sql6)) {
          if ($result7 = $transact->query($sql7)) {
            // Proceso ejecutado correctamente, no es necesario hacer nada.
          } else {
            $error =  mysqli_error($transact);
            echo("<br>Error 3: " . $error . "<br>");
            throw new Exception($error);
          }
        } else {
          $error =  mysqli_error($transact);
          echo("<br>Error 4: " . $error . "<br>");
          throw new Exception($error);
        }
      };
    } else {
      $error =  mysqli_error($transact);
      echo("<br>Error 5: " . $error . "<br>");
      throw new Exception($error);
    }
    
    $sql8 = "SELECT distinct(hr_processes.idhr_processes) FROM vacations " .
            "INNER JOIN hr_processes ON hr_processes.idhr_processes = vacations.id_process " .
            "WHERE hr_processes.status = 'PENDING' " .
            "AND vacations.date <= $v_end " .
            "AND hr_processes.date <= $v_end;";
   
    if ($result8 = $transact->query($sql8)) {
      while($row8 = $result->fetch_assoc()){
        $id_process = $row8['idhr_processes'];
        $v_id_process = $id_process;
        $sql9 = "UPDATE hr_processes SET " .
                "status = 'COMPLETED', notes = CONCAT( 'CLOSED ON END OF PERIOD' ,' | ', notes), " .
                "id_period = $id_period " .
                "WHERE idhr_processes = $v_id_process;";

        if ($result9 = $transact->query($sql9)) {
          // Proceso ejecutado correctamente, no es necesario hacer nada.
        } else {
          $error =  mysqli_error($transact);
          echo("<br>Error 6: " . $error . "<br>");
          throw new Exception($error);
        }
      }
    } else {
      $error =  mysqli_error($transact);
      echo("<br>Error 7: " . $error . "<br>");
      throw new Exception($error);
    }

    $sql10 =  "INSERT INTO payments (idpayments, id_employee, id_paymentmethod, id_period, credits, debits, date) " .
              "SELECT NULL, idemployees, idpayment_methods, $v_new_id_period AS ID_PERIOD, '0.00', '0.00', null FROM payment_methods " .
              "  INNER JOIN employees ON employees.idemployees = payment_methods.id_employee " .
              "WHERE predeterm = 1 AND active = 1;";
    if ($result10 = $transact->query($sql10)) {
      // Proceso ejecutado correctamente, no es necesario hacer nada.
    } else {
      $error =  mysqli_error($transact);
      echo("<br>Error 8: " . $error . "<br>");
      throw new Exception($error);
    }
  }
  
  $sql13 = "SELECT DISTINCT Z.idhr_processes FROM ( " .
  "  SELECT a.idhr_processes FROM hr_processes a " .
  "    INNER JOIN vacations b ON (a.idhr_processes = b.id_process) " .
  "  WHERE a.date <= $v_end  " .
  "    AND b.date <= $v_end " .
  "    AND a.status NOT IN('COMPLETED', 'DISMISSED') " .
  "    AND a.id_type = 4 " .
  "  UNION                            " .
  "  SELECT a.idhr_processes FROM hr_processes a " .
  "    INNER JOIN leaves b ON (a.idhr_processes = b.id_process) " .
  "  WHERE a.date <= $v_end " .
  "    AND b.end <= $v_end " .
  "    AND a.status NOT IN('COMPLETED', 'DISMISSED') " .
  "    AND a.id_type = 5 " .
  "  UNION  " .
  "  SELECT a.idhr_processes FROM hr_processes a " .
  "    INNER JOIN disciplinary_requests b  ON ( a.idhr_processes = b.id_process ) " .
  "    INNER JOIN disciplinary_processes c ON (b.iddisciplinary_requests = c.id_request) " .
  "    INNER JOIN  suspensions d ON (c.iddisciplinary_processes = d.id_disciplinary_process) " .
  "  WHERE a.date <= $v_end " .
  "    AND (d.day_1 <= $v_end " .
  "      AND d.day_2 <= $v_end " .
  "      AND d.day_3 <= $v_end " .
  "      AND d.day_4 <= $v_end) " .
  "    AND a.status <> 'COMPLETED' " .
  "    AND a.id_type = 6 " .
  "  UNION  " .
  "  SELECT a.idhr_processes FROM hr_processes a " .
  "    INNER JOIN attendence_justifications b ON (a.idhr_processes = b.id_process) " .
  "    INNER JOIN attendence_adjustemnt c ON (b.idattendence_justifications = c.id_justification) " .
  "    INNER JOIN attendences d on (c.id_attendence = d.idattendences) " .
  "  WHERE a.status != 'COMPLETED' " .
  "    AND a.date <= $v_end " .
  "    AND d.date <= $v_end " .
  "    AND a.id_type = 2 " .
  ") AS Z;";

  if ($result13 = $transact->query($sql13)) {
    while($row13 = $result13->fetch_assoc()){
      $idhr_processes = $row13['idhr_processes'];

      $sql14 = "UPDATE `hr_processes` SET `status` = 'COMPLETED', `notes` = CONCAT( 'CLOSED ON END OF PERIOD' ,' | ', `notes`), id_period = $id_period WHERE `hr_processes`.`idhr_processes` = $idhr_processes;";
      if ($result14 = $transact->query($sql14)) {
        $message = "<br>Info:  |<br>The period was successfully reversed.";  
        echo(json_encode($message));
      } else {
        $error =  mysqli_error($transact);
        echo("<br>Error 15: " . $error . "<br>");
        throw new Exception($error);
      }
    }
    $message = "<br>Info:  |<br>The period was successfully reversed.";  
    echo(json_encode($message));
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error 12: " . $error . "<br>");
    throw new Exception($error);
  }
  
  $sql15 = "UPDATE periods SET STATUS = 0 WHERE idperiods = $id_period;";
  if ($result15 = $transact->query($sql15)) {
    $message = "<br>Info:  |<br>The period was successfully reversed.";  
    echo(json_encode($message));
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error 12: " . $error . "<br>");
    throw new Exception($error);
  }

  if(!$result1 || !$result2 || !$result3 || !$result5 || !$result6 || !$result7 ||
    !$result8 || !$result9 || !$result10 || !$result11 || !$result12 || !$result13 ||
    !$result14) 
  {
    $transact->rollback();
  } else {
    $transact->commit();
  }
} catch(\Throwable $e) {  
  $error = "<br>Error:  |<br>The period could not be closed due to the following error: |" . $e->getMessage() . "|<br>The changes will be reversed.";  
  echo(json_encode($error));
  $transact->rollback();
}

$transact->close();
?>