<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$id_period = ($request->idperiods);

$v_start = '1899-01-01';
$v_end = '1899-01-01';
$v_end_day = '1899-01-01';
$v_start_day = '1899-01-01';
$v_id_process = 0;
$v_idattendence_adjustement = 0;
$v_new_id_period = 0;

$transact->begin_transaction();
try {  
  $sql1 = "SELECT start, end, type_period, CONCAT(YEAR(start)+1, '-', LPAD(MONTH(start), 2, '0'), '-', LPAD(DAY(start), 2, '0')) AS nstart, " .
          "CONCAT(YEAR(end)+1, '-', LPAD(MONTH(end), 2, '0'), '-', LPAD(DAY(end), 2, '0')) AS nend FROM periods " .
          "WHERE idperiods = $id_period;";
  if ($result1 = $transact->query($sql1)) {
    $row1 = $result1->fetch_assoc();
    $v_start = $row1['start'];
    $v_end = $row1['end'];
    $type = $row1['type_period'];
    $nstart = $row1['nstart'];
    $nend = $row1['nend'];

    $sql2 =  "UPDATE periods SET status = '0' where idperiods = $id_period";
    if ($transact->query($sql2)) {

      $sql3 =  "INSERT INTO periods (idperiods, start, end, status, type_period) VALUES (NULL, '$nstart', '$nend', 1, $type);";
      if ($transact->query($sql3) === true) {
        // Período creado.
      } else {
        $error =  mysqli_error($transact);
        echo("<br>Error 3: " . $error . "<br>");
        throw new Exception($error);
      }    
      
    } else {
      $error =  mysqli_error($transact);
      echo("<br>Error 2: " . $error . "<br>");
      throw new Exception($error);
    }
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error 1: " . $error . "<br>" . $sql1);
    throw new Exception($error);
  }
  
  if(!$result1)
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