<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';
require 'funcionesVarias.php';
header("Pragma: public");
header("Expires: 0");
$period = $_GET['period'];
$account = $_GET['account'];
$accname = $_GET['accname'];
$filename = "exportDaylyPayrollValues_" . $accname . "_period_". $period . ".xls";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");


$i = 0;
$j = 0;
$totalEmployees = 0;
$rowExport = array();
$emp = array();
$row2 = array();
$period_days = array();
$actual_date = date("Y/m/d");
$contador2 = 0;

$sql = "SELECT start, end, (end-start) + 1 AS days FROM periods WHERE idperiods = $period;";
if($result = mysqli_query($con,$sql)){
  $row = mysqli_fetch_assoc($result);
  $start = $row['start'];
  $end = $row['end'];
  $days = $row['days'];
  $actual_date = $start;
}

$period_days[0] = $actual_date;
for ($k=1; $k < $days; $k++) {
  $sql4 = "SELECT adddate('$actual_date', 1) AS day;";
  if($result4 = mysqli_query($con,$sql4)) {
    $row4 = mysqli_fetch_assoc($result4);
    $actual_date = $row4['day'];
  }
  $period_days[$k] = $actual_date;
}

$sql2 ="SELECT DISTINCT
          e.idemployees,
          e.client_id,
          h.nearsol_id,
          p2.name,
          u.user_name AS supervisor,
          e.hiring_date,
          IF(t.valid_from IS null, IF(e.active=1,'active', t.valid_from), t.valid_from) AS terminations,
          e.termination_date,
          tr.date AS trDate,
          SUBSTRING_INDEX(tr.notes, '|', 1) AS oldep,
          SUBSTRING_INDEX(SUBSTRING_INDEX(tr.notes, '|', 2), '|', -1) AS newdep
        FROM periods p3
        INNER JOIN payments p2 ON (p3.idperiods = p2.id_period)
        INNER JOIN employees e ON (p2.id_employee = e.idemployees)
        INNER JOIN hires h ON h.idhires = e.id_hire
        INNER JOIN profiles p ON p.idprofiles = h.id_profile
        INNER JOIN (SELECT UPPER(CONCAT(TRIM(p1.first_name), ' ', TRIM(p1.second_name), ' ', TRIM(p1.first_lastname), ' ', TRIM(p1.second_lastname))) AS name, p1.idprofiles FROM profiles p1) p2 ON (p2.idprofiles = p.idprofiles)
        INNER JOIN users u ON u.idUser = e.reporter
        INNER JOIN accounts a ON a.idaccounts = e.id_account
        LEFT JOIN (SELECT * FROM hr_processes hp
              INNER JOIN terminations t1 ON (hp.idhr_processes = t1.id_process AND hp.id_type = 8)
              ) t ON (e.idemployees = t.id_employee)
        LEFT JOIN (SELECT * FROM hr_processes hp WHERE hp.id_type = 16
              ) tr ON (e.idemployees = tr.id_employee AND tr.date BETWEEN p3.`start` AND p3.`end`)
        WHERE p3.idperiods = $period
        AND a.idaccounts = $account
        /*and e.idemployees = 5367 */
        AND e.hiring_date <= p3 .`end`
        ORDER BY e.idemployees;";


$idemployees = '-1';
if ($result2 = mysqli_query($con, $sql2)) {
  while($row2 = mysqli_fetch_assoc($result2)){
    $emp[$i]['idemployees'] = $row2['idemployees'];
    $emp[$i]['client_id'] = $row2['client_id'];
    $emp[$i]['nearsol_id'] = $row2['nearsol_id'];
    $emp[$i]['name'] = $row2['name'];
    $emp[$i]['supervisor'] = $row2['supervisor'];
    $emp[$i]['hiring_date'] = $row2['hiring_date'];
    $emp[$i]['terminations'] = $row2['terminations'];
    $emp[$i]['termination_date'] = $row2['termination_date'];
    $emp[$i]['trDate'] = $row2['trDate'];
    $emp[$i]['oldep'] = $row2['oldep'];
    $emp[$i]['newdep'] = $row2['newdep'];

    $idemployees = $idemployees . ',' . $row2['idemployees'];
    $i++;
  } // end while
  $totalEmployees = $i;
} else {
    echo json_encode($sql2);
    http_response_code(422);
}

$body = array();
for ($i=0; $i < $totalEmployees; $i++) {
  for ($j=0; $j < 293; $j++) {
    $body[$i][$j] = 0;
  }
}

$i = 0;
foreach ($emp as $element => $value) {
  $body[$i][0] = $emp[$i]['idemployees'];
  $body[$i][1] = $emp[$i]['client_id'];
  $body[$i][2] = $emp[$i]['nearsol_id'];
  $body[$i][3] = $emp[$i]['name'];
  $body[$i][4] = $emp[$i]['supervisor'];
  $body[$i][5] = $emp[$i]['terminations'];
  /*$body[$i][6] = $emp[$i]['terminations'];
  $body[$i][7] = $emp[$i]['termination_date'];
  $body[$i][8] = $emp[$i]['trDate'];
  $body[$i][9] = $emp[$i]['oldep'];
  $body[$i][10] = $emp[$i]['newdep'];
  */

  $i++;
}

$numday = 1;
$fields = 5;
foreach($period_days as $actual_day) {
  $sql3 =  "SELECT
              a.id_employee,
              aj.reason,
              a.date,
              a.scheduled AS scheduled,
              if (aj.reason='Schedule FIX', aa.amount, 0) AS scheduledFix,
              if (aj.reason='Time in Aux 0', aa.amount, 0) AS time_in_aux_0,
              if (aj.reason='Time In System Issues', aa.amount, 0) AS time_in_system_issues,
              if (aj.reason='Time in lunch', aa.amount, 0) AS time_in_lunch,
              if (aj.reason='Break Abuse', aa.amount, 0) AS break_abuse,
              if (aj.reason='IGSS', aa.amount, 0) AS IGSS,
              if (aj.reason='Exceptions Meeting & feedback', aa.amount, 0) AS exceptions_meeting_and_feedback,
              if (aj.reason='Exceptions offline Training', aa.amount, 0) AS exceptions_offline_training,
              if (aj.reason='System Issues by Sup', aa.amount, 0) AS system_issues_by_sup,
              if (aj.reason='Floor Support', aa.amount, 0) AS floor_support,
              if (aj.reason='TIME TRAINING', aa.amount, 0) AS time_training,
              /*SUM(DISTINCT aa.amount) AS total_exceptions, */
              if (aj.reason='CCR Productive', aa.amount, 0) AS ccr_productive,
              if (a.scheduled='OFF', 'OFF', if(a.scheduled='NaN', 'NaN', 'X')) AS attendance, /* corregir para que tenga NS */
              if (aj.reason='', aa.amount, 0) AS total_time_to_pay,
              if (aj.reason='', aa.amount, 0) AS missing_time,
              if (aj.reason='', aa.amount, 0) AS over_time
            FROM attendences a
            LEFT JOIN attendence_adjustemnt aa ON a.idattendences = aa.id_attendence
            LEFT JOIN attendence_justifications aj ON aj.idattendence_justifications = aa.id_justification
            LEFT JOIN hr_processes hr ON hr.idhr_processes = aj.id_process
            LEFT JOIN users u ON u.idUser = hr.id_user
            WHERE a.date = '$actual_day'
            AND a.id_employee IN(" . $idemployees . ")
            ORDER BY a.id_employee;";

  $j = 0;
  if ($result3 = mysqli_query($con, $sql3)) {
    while($row3 = mysqli_fetch_assoc($result3)){
      $rowExport[$j][0] = $row3['id_employee'];
      $rowExport[$j][1] = $row3['reason'];
      $rowExport[$j][2] = $row3['date'];
      $rowExport[$j][3] = $row3['scheduled'];
      $rowExport[$j][4] = $row3['scheduledFix'];
      $rowExport[$j][5] = $row3['time_in_aux_0'];
      $rowExport[$j][6] = $row3['time_in_system_issues'];
      $rowExport[$j][7] = $row3['time_in_lunch'];
      $rowExport[$j][8] = $row3['break_abuse'];
      $rowExport[$j][9] = $row3['IGSS'];
      $rowExport[$j][10] = $row3['exceptions_meeting_and_feedback'];
      $rowExport[$j][11] = $row3['exceptions_offline_training'];
      $rowExport[$j][12] = $row3['system_issues_by_sup'];
      $rowExport[$j][13] = $row3['floor_support'];
      $rowExport[$j][14] = $row3['time_training'];
      $rowExport[$j][15] = $row3['IGSS'] + $row3['exceptions_meeting_and_feedback'] + $row3['exceptions_offline_training'] + $row3['system_issues_by_sup'] + $row3['floor_support'] + $row3['time_training'];
      $rowExport[$j][16] = $row3['ccr_productive'];
      $rowExport[$j][17] = $row3['attendance'];
      $rowExport[$j][18] = $row3['total_time_to_pay'];
      $rowExport[$j][19] = $row3['missing_time'];
      $rowExport[$j][20] = $row3['over_time'];
      $j++;
    }

    $contador = 0;
    $totalTuplas = $j;
    for ($i = 0; $i <= $totalTuplas; $i++) {
      $fields = 5 + (18 * ($numday - 1));

      for ($j = 0; $j < $totalEmployees; $j++) {
        if (empty($rowExport[$i][0]) || is_null($rowExport[$i][0])) {
          //echo "esta vacio";
          break;
        } else {
          $eleBody = $body[$j][0];
          $eleRow = $rowExport[$i][0];

          if ($eleBody == $eleRow) {
            $body[$j][$fields + 1]  = $rowExport[$i][3];
            $body[$j][$fields + 2]  = $rowExport[$i][4];
            $body[$j][$fields + 3]  = $rowExport[$i][5];
            $body[$j][$fields + 4]  = $rowExport[$i][6];
            $body[$j][$fields + 5]  = $rowExport[$i][7];
            $body[$j][$fields + 6]  = $rowExport[$i][8];
            $body[$j][$fields + 7]  = $rowExport[$i][9];
            $body[$j][$fields + 8]  = $rowExport[$i][10];
            $body[$j][$fields + 9]  = $rowExport[$i][11];
            $body[$j][$fields + 10] = $rowExport[$i][12];
            $body[$j][$fields + 11] = $rowExport[$i][13];
            $body[$j][$fields + 12] = $rowExport[$i][14];
            $body[$j][$fields + 13] = $rowExport[$i][15];
            $body[$j][$fields + 14] = $rowExport[$i][16];
            $body[$j][$fields + 15] = $rowExport[$i][17];
            $body[$j][$fields + 16] = $rowExport[$i][18];
            $body[$j][$fields + 17] = $rowExport[$i][19];
            $body[$j][$fields + 18] = $rowExport[$i][20];
            $contador++;
          }
        }
        if ($contador == 0) {
          $body[$j][$fields + 1]  = 0;
          $body[$j][$fields + 2]  = 0;
          $body[$j][$fields + 3]  = 0;
          $body[$j][$fields + 4]  = 0;
          $body[$j][$fields + 5]  = 0;
          $body[$j][$fields + 6]  = 0;
          $body[$j][$fields + 7]  = 0;
          $body[$j][$fields + 8]  = 0;
          $body[$j][$fields + 9]  = 0;
          $body[$j][$fields + 10] = 0;
          $body[$j][$fields + 11] = 0;
          $body[$j][$fields + 12] = 0;
          $body[$j][$fields + 13] = 0;
          $body[$j][$fields + 14] = 0;
          $body[$j][$fields + 15] = 0;
          $body[$j][$fields + 16] = 0;
          $body[$j][$fields + 17] = 0;
          $body[$j][$fields + 18] = 0;
        }

      } // End for
    } // End for
  } else {
    echo json_encode($sql3);
    http_response_code(423);
  }
  $numday ++;
}// end foreach

$title = ['Avaya', 'AID', 'Name', 'Supervisor', 'Termination'];
for ($k=1; $k <= $days; $k++) {
  array_push($title, 'Scheduled');
  array_push($title, 'Schedule FIX');
  array_push($title, 'Time in Aux 0');
  array_push($title, 'Time in System Issues');
  array_push($title, 'Time in lunch');
  array_push($title, 'Break Abuse');
  array_push($title, 'IGSS');
  array_push($title, 'Exceptions Meeting & feedback');
  array_push($title, 'Exceptions offline Training');
  array_push($title, 'System Issues by Sup');
  array_push($title, 'Floor Support');
  array_push($title, 'TIME TRAINING');
  array_push($title, 'Total Exceptions');
  array_push($title, 'CCR Productive');
  array_push($title, 'Attendance');
  array_push($title, 'Total Time to pay');
  array_push($title, 'Missing Time');
  array_push($title, 'Over time');
}

echo "
  <table border='1'>
    <thead style='background-color: #203764;color:white;border:1px solid white;'>
      <tr style='border:1px solid white;'>
      <td colspan='5' style='background-color: #203764;color:white;'>
        &nbsp;
      </td>";
      // Imprime los días por empleado.
      foreach($period_days as $actual_day) {
        echo "<td colspan='18' style='text-align:center;font-family:Segoe UI Symbol;font-size:11px;web-safe-fonts:sans-serif;font-weight:bold;background-color: #203764;color:white;border:1px solid white;'>";
        echo "$actual_day";
        echo "</td>";
      }
      echo "</tr>
    </thead>
    <tbody>
      <tr style='white-space: nowrap;font-family:Segoe UI Symbol;border:1px solid white;'>";
      // Imprime los títulos de la tabla.
      foreach($title as $fila) {
        echo "<th style='font-size:11px;background-color: #203764;color:white;font-weight:normal;border:1px solid white;'>";
          echo $fila;
        echo "</th>";
      }
      echo "</tr>";

      // Imprime en el cuerpo de la tabla la información de los empleados.
      for ($i=0; $i < $totalEmployees; $i++) {
        echo "<tr>";
        for ($j=1; $j < count($title) + 1; $j++) {
          echo "<td>";
          echo $body[$i][$j];
          echo "</td>";
          }
          echo"</tr>";
      }
echo "</tbody></table>";

?>
