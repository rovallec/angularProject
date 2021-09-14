<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

echo($request);
echo(json_encode($request));

$idattendences = ($request->idattendences);
$id_employee = ($request->id_employee);
$status = ($request->status);
$state = ($request->state);

$sql = "SELECT hp.idhr_processes
        FROM hr_processes hp
        INNER JOIN attendence_justifications aj ON (hp.idhr_processes = aj.id_process)
        INNER JOIN attendence_adjustemnt aa ON (aj.idattendence_justifications = aa.id_justification)
        INNER JOIN attendences a ON (aa.id_attendence = a.idattendences)
        WHERE hp.id_employee = $id_employee
        AND a.idattendences = $idattendences;";

try 
{
  if ($res = $transact->query($sql)) {
      $row = $res->fetch_assoc();
      $idhr_processes = $row['idhr_processes'];
      $sql2 = "UPDATE hr_processes SET 
              status = $status
              WHERE idhr_processes = $idhr_processes;";

    if ($res2 = $transact->query($sql2) === true) {
      $sql3 = "UPDATE attendence_adjustemnt SET
              state = $state
              WHERE id_attendence = $idattendences;"
      
      if ($res3 = $transact->query($sql3) === true) {
        echo("poceso ejecutado correctamente.");
      } else {
        $error =  mysqli_error($transact);
          echo("<br>Error : " . $error . "<br>");
          throw new Exception($error);
      }
    } else {
      $error =  mysqli_error($transact);
      echo("<br>Error : " . $error . "<br>");
      throw new Exception($error);
    }
  } else {
    $error =  mysqli_error($transact);
    echo("<br>Error : " . $error . "<br>");
    throw new Exception($error);
  }
} catch(\Throwable $e) {
  $error = "Error: |Unable to update the justification due to the following error: |" . $e->getMessage() . "|The changes will be reversed.";  
  echo(json_encode($error));
  $transact->rollback();
}

$transact->close();
?>