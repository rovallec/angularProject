<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "reportAccountingPolicy.csv" . '"');

require 'database.php';
require 'funcionesVarias.php';

echo "\xEF\xBB\xBF";
$AID_Period = $_GET['AID_Period'];
$i = 0;

$sql1 = "SELECT COUNT(*) AS account FROM policies WHERE id_period = $AID_Period;";
$sql11 = "SELECT a.end FROM periods a WHERE a.idperiods = $AID_Period;";

if ($result1 = mysqli_query($con,$sql1)){
  $row1 = mysqli_fetch_assoc($result1);
  if ($result11 = mysqli_query($con,$sql11)) {
    $row11 = mysqli_fetch_assoc($result11);
    $end = $row11['end'];
    $sql12 = "SELECT DAY(LAST_DAY('" . $end . "')) AS V_DAYS_ON_MONTH";
    if ($result12 = mysqli_query($con,$sql12)) {
      $row12 = mysqli_fetch_assoc($result12);
      $V_DAYS_ON_MONTH = $row12['V_DAYS_ON_MONTH'];
    } else {
      http_response_code(412);
      echo($con->error);
      echo($sql12);
    }
  } else {
    http_response_code(411);
    echo($con->error);
    echo($sql11);
  }
} else {
  http_response_code(401);
  echo($sql1);
}

$account = $row1['account'];

if ($account == 0) {
  $sql2 = "SELECT idaccounts FROM accounts;";
  $today = date("Y/m/d");

  if($result2 = mysqli_query($con,$sql2)){
    while($row2 = mysqli_fetch_assoc($result2)){
      $v_account = $row2['idaccounts'];
      $sql3 = "INSERT INTO policies (idpolicies, id_period, id_account, close_date) VALUES (NULL, $AID_Period, $v_account, '$today');";
      if(mysqli_query($con,$sql3)){
        $sql4 = "SELECT LAST_INSERT_ID() AS V_ID_POLICIES;";
        if($result4 = mysqli_query($con,$sql4)){
          $row4 = mysqli_fetch_assoc($result4);
          $V_ID_POLICIES = $row4['V_ID_POLICIES'];
          $sql5 = "SELECT DISTINCT idaccounting_accounts, external_id FROM accounting_accounts;";
          if($result5 = mysqli_query($con,$sql5)){
            while($row5 = mysqli_fetch_assoc($result5)) {
              $V_ID_ACCOUNTING = $row5['idaccounting_accounts'];
              $V_EXTERNAL_ID = $row5['external_id'];

              $sql51 = "SET @V_EXTERNAL_ID = '$V_EXTERNAL_ID';";
              if ($result51 = mysqli_query($con,$sql51)) {
                 // $row51 = mysqli_fetch_assoc($result51); no es necesario ya que solo setea la variable.

              } else {
                http_response_code(451);
                echo($con->error);
                echo($sql51);
              }
              $sql6 =
                "SELECT DISTINCT " .
                  "COALESCE( " .
                  "CASE " .
                    "WHEN @V_EXTERNAL_ID IN('5.1.01.01.01.', '6.1.01.01.01.') THEN ROUND((SUM(pay.base_complete) / 2), 2) " .
                    "WHEN @V_EXTERNAL_ID IN('5.1.01.01.02.', '6.1.01.01.02.') THEN ROUND(SUM(pay.productivity_complete) / 2, 2) " .
                    "WHEN @V_EXTERNAL_ID IN('5.1.01.01.03.', '6.1.01.01.03.') THEN ROUND((SUM(pay.base_complete) * 0.1267) / 2, 2) " .
                    "WHEN @V_EXTERNAL_ID IN('5.1.01.01.04.', '6.1.01.01.04.') THEN ROUND(SUM(IF(cred.type='aguinaldo',cred.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID IN('5.1.01.01.05.', '6.1.01.01.05.') THEN ROUND(SUM(IF(cred.type='idemnizacion',cred.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID IN('5.1.01.01.06.', '6.1.01.01.06.') THEN ROUND(SUM(IF(cred.type='bono14',cred.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID IN('5.1.01.01.07.', '6.1.01.01.07.') THEN ROUND(SUM(IF(cred.type='HR Vacations Payed',cred.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '6.1.01.01.10.' THEN ROUND(SUM(IF(cred.type='bonificacion RAF',cred.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID IN('5.1.01.01.12.', '6.1.01.01.12.') THEN ROUND(SUM((pay.base_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) + ROUND(SUM((pay.productivity_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) " .
                    "WHEN @V_EXTERNAL_ID IN('5.1.01.01.13.', '6.1.01.01.13.') THEN ROUND(SUM((pay.base_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) + ROUND(SUM((pay.productivity_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) " .
                    "WHEN @V_EXTERNAL_ID IN('5.1.01.01.14.', '6.1.01.01.14.') THEN ROUND(SUM((pay.base_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.0417), 2) + ROUND(SUM((pay.productivity_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.0417), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.2.01.01.00.' THEN ROUND(SUM(IF(cred.type='idemnizacion',cred.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.2.01.02.00.' THEN ROUND(SUM(IF(cred.type='HR Vacations Payed',cred.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.2.01.03.00.' THEN ROUND(SUM(IF(cred.type='aguinaldo',cred.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.2.01.04.00.' THEN ROUND(SUM(IF(cred.type='bono14',cred.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.2.01.05.02.' THEN ROUND(SUM((pay.base_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.0417), 2) + ROUND(SUM((pay.productivity_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.0417), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.2.01.05.03.' THEN ROUND(SUM((pay.base_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) + ROUND(SUM((pay.productivity_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.2.01.05.04.' THEN ROUND(SUM((pay.base_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) + ROUND(SUM((pay.productivity_complete / " . $V_DAYS_ON_MONTH . " * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.2.02.06.00.' THEN ROUND(SUM(pay.base_complete) * 0.1267, 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.1.01.03.00.' THEN ROUND((SUM(pay.base_complete) / 2), 2) + ROUND(SUM(pay.productivity_complete) / 2, 2) + ROUND(SUM(IF(cred.type='aguinaldo',cred.amount, 0)), 2) + " .
                    "                        ROUND(SUM(IF(cred.type='bonificacion RAF',cred.amount, 0)), 2) + " .
                    "            (-1  * (ROUND(SUM(IF(deb.type='BUS TRANSPORTATION',deb.amount, 0)), 2) + ROUND(SUM(IF(deb.type='CAR PARKING',deb.amount, 0)), 2) + ROUND(SUM(IF(deb.type='MOTORCYCLE PARKING',deb.amount, 0)), 2) + " .
                    "            ROUND(SUM(IF(deb.type IN('HEADSET', 'Descuento Por Servicio de Active Parking', 'TARJETA DE ACCESO/PARQUEO', 'Tarjeta De Acceso'),deb.amount, 0)), 2) + ROUND(SUM(IF(deb.type='ISR',deb.amount, 0)), 2) + " .
                    "            ROUND(SUM(IF(deb.type='Descuento IGSS',deb.amount, 0)), 2) + ROUND(SUM(IF(deb.type='PERSONAL LOAN',deb.amount, 0)), 2) + ROUND(SUM(IF(deb.type='Boleto de Ornato',deb.amount, 0)), 2) + " .
                    "            ROUND(SUM(IF(deb.type IN('Descuento Judicial', 'Acuerdo Judicial'),deb.amount, 0)), 2) )) " .
                    "WHEN @V_EXTERNAL_ID = '1.1.04.02.01.' THEN ROUND(SUM(IF(deb.type='BUS TRANSPORTATION',deb.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '1.1.04.02.02.' THEN ROUND(SUM(IF(deb.type='CAR PARKING',deb.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '1.1.04.02.02.' THEN ROUND(SUM(IF(deb.type='MOTORCYCLE PARKING',deb.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '4.1.09.01.00.' THEN ROUND(SUM(IF(deb.type IN('HEADSET', 'Descuento Por Servicio de Active Parking', 'TARJETA DE ACCESO/PARQUEO', 'Tarjeta De Acceso'),deb.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.1.02.03.00.' THEN ROUND(SUM(IF(deb.type='ISR',deb.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.1.02.05.00.' THEN ROUND(SUM(IF(deb.type='Descuento IGSS',deb.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '1.1.04.09.99.' THEN ROUND(SUM(IF(deb.type='PERSONAL LOAN',deb.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.1.02.07.00.' THEN ROUND(SUM(IF(deb.type='Boleto de Ornato',deb.amount, 0)), 2) " .
                    "WHEN @V_EXTERNAL_ID = '2.1.01.08.00.' THEN ROUND(SUM(IF(deb.type IN('Descuento Judicial', 'Acuerdo Judicial'),deb.amount, 0)), 2) " .
                  "ELSE 0.00 " .
                "END, 0.00) AS amount " .
                "FROM payments pay " .
                "INNER JOIN periods per ON (pay.id_period = per.idperiods) " .
                "LEFT JOIN debits deb ON (pay.idpayments = deb.id_payment) " .
                "LEFT JOIN credits cred ON (pay.idpayments = cred.id_payment) " .
                "WHERE pay.id_period = $AID_Period " .
                "AND pay.id_account_py = $v_account " .
                "AND pay.base_complete > 0;";

              if ($result6 = mysqli_query($con,$sql6)) {
                $row6 = mysqli_fetch_assoc($result6);
                $v_amount = validateDataToZero($row6['amount']);

                $sql7 = "INSERT INTO policy_details VALUES (NULL, $V_ID_POLICIES, $V_ID_ACCOUNTING, $v_amount);";

                if($result7 = mysqli_query($con, $sql7)) {
                  http_response_code(200);
                } else  {
                  http_response_code(407);
                  echo($con->error);
                  echo($sql7);
                }
              } else {
                  http_response_code(406);
                  echo($con->error);
                  echo($sql6);
              }
            }
          } else {
              http_response_code(405);
              echo($con->error);
              echo($sql5);
          }
        } else {
            http_response_code(404);
            echo($con->error);
            echo($sql4);
        }
      } else {
          http_response_code(433);
          echo($con->error);
          echo($sql3);
      }
    }
  } else {
    http_response_code(402);
    echo($con->error);
    echo($sql2);
  }
}  // Fin del IF.

  // Corremos el query del reporte.
$sql8 = "SELECT
    a.id_period,
    d.name as account,
    c.external_id,
    c.name,
    COALESCE(b.amount, 0.00) AS amount
  FROM policies a
  LEFT JOIN policy_details b ON (a.idpolicies = b.id_policy)
  LEFT JOIN accounting_accounts c on (b.id_ccounting_account = c.idaccounting_accounts)
  LEFT JOIN accounts d on (a.id_account = d.idaccounts)
  WHERE a.id_period = $AID_Period;";

$title = ['Período', 'Cuenta', 'Cuenta_Contable', 'Nombre', 'Monto'];
$output = fopen("php://output", "w");
fputcsv($output, $title);
if($result8 = mysqli_query($con,$sql8)){
    while($row8 = mysqli_fetch_assoc($result8)){
        $exportRow[0] = $row8['id_period'];
        $exportRow[1] = $row8['account'];
        $exportRow[2] = $row8['external_id'];
        $exportRow[3] = $row8['name'];
        $exportRow[4] = $row8['amount'];
        fputcsv($output, $exportRow,",");
        $i++;
    };
}else{
    http_response_code(408);
}
fclose($output);

?>
