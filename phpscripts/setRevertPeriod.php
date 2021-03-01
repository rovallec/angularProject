<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_period = ($request->id_period);
  
$v_start = '1899-01-01';  
$v_id_process = 0;
$v_idattendence_adjustement = 0;
$v_new_id_period = 0;
$result3 = true;
$result6 = true;
$result9 = true;


try 
{
  $sql2 = "SELECT COUNT(*) AS count FROM periods WHERE idperiods = $id_period AND type_period = 0";
  if ($result2 = $transact->query($sql2)) {
    $row2 = $result2->fetch_assoc();
    $count = $row2['count'];
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error 2: " . $error . "<br>");
    throw new Exception($error);
  }
  
  if ($count = 0) {
    $sql3 =  "SELECT " .
                "  attendence_adjustemnt.idattendence_adjustemnt, " .
                "  attendence_justifications.id_process " .
                "from attendence_adjustemnt " .
                "  INNER JOIN attendence_justifications ON attendence_justifications.idattendence_justifications = attendence_adjustemnt.id_justification " .
                "  INNER JOIN hr_processes ON hr_processes.idhr_processes = attendence_justifications.id_process " .
                "WHERE hr_processes.id_period = $id_period " .
                "  AND attendence_adjustemnt.state = 'CCOMPLETED';";

    if ($result3 = $transact->query($sql3)) {
      while($row3 = $result3->fetch_assoc()){
        $v_idattendence_adjustement = $row3['idattendence_adjustemnt'];
        $v_id_process = $row3['id_process'];

        $sql4 = "UPDATE hr_processes SET " .
                "status = 'PENDING', " .                
                "id_period = NULL " .
                "WHERE idhr_processes = $v_id_process; ";   
        $sql5 = "UPDATE attendence_adjustemnt SET  " .
                "state =  'PENDING' " .
                "WHERE idattendence_adjustemnt = $v_idattendence_adjustement;";
        if ($transact->query($sql4) === true) {
          if ($transact->query($sql5) === true) {
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
    
    $sql6 = "SELECT distinct(hr_processes.idhr_processes) FROM vacations " .
            "INNER JOIN hr_processes ON hr_processes.idhr_processes = vacations.id_process " .
            "WHERE hr_processes.status = 'COMPLETED' " .            
            "AND hr_processes.id_period = $id_period;";
   
    if ($result6 = $transact->query($sql6)) {
      while($row6 = $result6->fetch_assoc()){
        $id_process = $row6['idhr_processes'];
        $v_id_process = $id_process;
        $sql7 = "UPDATE hr_processes SET " .
                "status = 'PENDING', " .
                "id_period = NULL " .
                "WHERE idhr_processes = $v_id_process;";

        if ($transact->query($sql7) === true) {
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

    $sql8 = "DELETE FROM payments where id_period = $id_period;";
    if ($transact->query($sql8) === true) {
      // Proceso ejecutado correctamente, no es necesario hacer nada.
    } else {
      $error =  mysqli_error($transact);
      echo("<br>Error 8: " . $error . "<br>");
      throw new Exception($error);
    }
  }
  
  $sql9 = "SELECT DISTINCT Z.idhr_processes FROM ( " .
  "  SELECT a.idhr_processes FROM hr_processes a " .
  "    INNER JOIN vacations b ON (a.idhr_processes = b.id_process) " .
  "  WHERE a.id_period = $id_period " .
  "    AND a.status IN('COMPLETED', 'DISMISSED') " .
  "    AND a.id_type = 4 " .
  "  UNION                            " .
  "  SELECT a.idhr_processes FROM hr_processes a " .
  "    INNER JOIN leaves b ON (a.idhr_processes = b.id_process) " .
  "  WHERE a.id_period = $id_period " .
  "    AND a.status IN('COMPLETED', 'DISMISSED') " .
  "    AND a.id_type = 5 " .
  "  UNION  " .
  "  SELECT a.idhr_processes FROM hr_processes a " .
  "    INNER JOIN disciplinary_requests b  ON ( a.idhr_processes = b.id_process ) " .
  "    INNER JOIN disciplinary_processes c ON (b.iddisciplinary_requests = c.id_request) " .
  "    INNER JOIN  suspensions d ON (c.iddisciplinary_processes = d.id_disciplinary_process) " .
  "  WHERE a.id_period = $id_period " .
    "    AND a.status = 'COMPLETED' " .
  "    AND a.id_type = 6 " .
  "  UNION  " .
  "  SELECT a.idhr_processes FROM hr_processes a " .
  "    INNER JOIN attendence_justifications b ON (a.idhr_processes = b.id_process) " .
  "    INNER JOIN attendence_adjustemnt c ON (b.idattendence_justifications = c.id_justification) " .
  "    INNER JOIN attendences d on (c.id_attendence = d.idattendences) " .
  "  WHERE a.status = 'COMPLETED' " .
  "    AND a.id_period = $id_period " .  
  "    AND a.id_type = 2 " .
  ") AS Z;";

  if ($result9 = $transact->query($sql9)) {
    while($row9 = $result9->fetch_assoc()){
      $idhr_processes = $row9['idhr_processes'];

      $sql10 = "UPDATE `hr_processes` SET `status` = 'PENDING', id_period = NULL WHERE `hr_processes`.`idhr_processes` = $idhr_processes;";
      if ($transact->query($sql10) === true) {
        // No es necesario hacer nada.
      } else {
        $error =  mysqli_error($transact);
        echo("<br>Error 10: " . $error . "<br>");
        throw new Exception($error);
      }
    }    
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error 9: " . $error . "<br>");
    throw new Exception($error);
  }
  
  $sql11 = "UPDATE periods SET STATUS = 1 WHERE idperiods = $id_period;";
  if ($transact->query($sql11) === true) {
    // no es necesario hacer nada.
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error 11: " . $error . "<br>");
    throw new Exception($error);
  }

  if(!$result2 || !$result3 || !$result6 || !$result9)
  {
    $transact->rollback();
  } else {
    $transact->commit();
    $message = "Info:|The period was successfully reversed.| | ";  
    echo(json_encode($message));
  }
} catch(\Throwable $e) {
  $error = "Error:  |The period could not be reversed due to the following error: |" . $e->getMessage() . "|The changes will be reversed.";  
  echo(json_encode($error));
  $transact->rollback();
}

$transact->close();
?>