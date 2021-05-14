<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "reportAccountingPolicy.csv" . '"');

require 'database.php';
require 'funcionesVarias.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$AID_Period = ($request->idperiod);
$exportRow = [];

$i = 0;
$sql1 = "SELECT COUNT(*) AS account FROM policies WHERE id_period = $AID_Period;";
$sql11 = "SELECT a.end FROM periods a WHERE a.idperiods = $AID_Period;";

try {
if ($result1 = mysqli_query($con,$sql1)){
  $row1 = mysqli_fetch_assoc($result1);
  if ($result11 = mysqli_query($con,$sql11)) {
    $row11 = mysqli_fetch_assoc($result11);
    $end = $row11['end'];
    $sql12 = "SELECT DAY(LAST_DAY('" . $end . "')) AS V_DAYS_ON_MONTH;";
    if ($result12 = mysqli_query($con,$sql12)) {
      $row12 = mysqli_fetch_assoc($result12);
      $V_DAYS_ON_MONTH = $row12['V_DAYS_ON_MONTH'];
    } else {
      http_response_code(412);
      echo($con->error);
      echo($sql12);
    }
  } else {
    $error = mysqli_error($con);
    http_response_code(411);
    echo($error);
    echo($sql11);
  }
} else {
  $error =  mysqli_error($con);
  http_response_code(401);
  echo($error);
  echo($sql1);
}
$account = $row1['account'];

if ($account == 0) {
  $sql2 = "SELECT idaccounts FROM accounts;";
  $today = date("Y/m/d");

  if($result2 = mysqli_query($con,$sql2)){
    while($row2 = mysqli_fetch_assoc($result2)){
      $v_account = $row2['idaccounts'];      
      $sql30 = "SET @V_DAYS_ON_MONTH = '$V_DAYS_ON_MONTH';";
      if ($result30 = mysqli_query($con,$sql30)) {
        $i = 0;
        $sql32 = "SELECT DISTINCT idaccounting_accounts, external_id, name, clasif FROM accounting_accounts;";
        if($result32 = mysqli_query($con,$sql32)){
          while($row32 = mysqli_fetch_assoc($result32)) {
            $V_ID_ACCOUNTING = $row32['idaccounting_accounts'];
            $V_EXTERNAL_ID = $row32['external_id'];
            $V_NAME_ACCOUNT = $row32['name'];
            $V_CLASIF = $row32['clasif'];
            $sql31 = "SET @V_EXTERNAL_ID = '$V_EXTERNAL_ID';";
            if ($result31 = mysqli_query($con,$sql31)) {
              //no hace nada, solo setea la variable.
            }
            $sql4 = "SELECT DISTINCT " .
                  "COALESCE( " .
                    "CASE " .
                      "WHEN @V_EXTERNAL_ID IN('51001') THEN ROUND((SUM(pay.base_complete) / 2), 2) " .
                      "WHEN @V_EXTERNAL_ID IN('51021') THEN ROUND(SUM(pay.productivity_complete) / 2, 2) " .
                      "WHEN @V_EXTERNAL_ID IN('51004') THEN ROUND((SUM(pay.base_complete) * 0.1267) / 2, 2) " .
                      "WHEN @V_EXTERNAL_ID IN('51010') THEN ROUND(SUM(IF(cred.type='aguinaldo',cred.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID IN('51017') THEN ROUND(SUM(IF(cred.type='idemnizacion',cred.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID IN('51012') THEN ROUND(SUM(IF(cred.type='bono14',cred.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID IN('51014') THEN ROUND(SUM(IF(cred.type='HR Vacations Payed',cred.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '65002' THEN ROUND(SUM(IF(cred.type='bonificacion RAF',cred.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID IN('51011') THEN ROUND(SUM((pay.base_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) + ROUND(SUM((pay.productivity_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) " .
                      "WHEN @V_EXTERNAL_ID IN('51013') THEN ROUND(SUM((pay.base_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) + ROUND(SUM((pay.productivity_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) " .
                      "WHEN @V_EXTERNAL_ID IN('51015') THEN ROUND(SUM((pay.base_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.0417), 2) + ROUND(SUM((pay.productivity_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.0417), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21082' THEN ROUND(SUM(IF(cred.type='idemnizacion',cred.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21074' THEN ROUND(SUM(IF(cred.type='HR Vacations Payed',cred.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21077' THEN ROUND(SUM(IF(cred.type='aguinaldo',cred.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21079' THEN ROUND(SUM(IF(cred.type='bono14',cred.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21075' THEN ROUND(SUM((pay.base_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.0417), 2) + ROUND(SUM((pay.productivity_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.0417), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21078' THEN ROUND(SUM((pay.base_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) + ROUND(SUM((pay.productivity_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21080' THEN ROUND(SUM((pay.base_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) + ROUND(SUM((pay.productivity_complete / @V_DAYS_ON_MONTH * (ROUND(pay.base_hours / 8, 0)) ) * 0.083333), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21086' THEN ROUND(SUM(pay.base_complete) * 0.1267, 2) " .
                      "WHEN @V_EXTERNAL_ID = '21072' THEN ROUND((SUM(pay.base_complete) / 2), 2) + ROUND(SUM(pay.productivity_complete) / 2, 2) + ROUND(SUM(IF(cred.type='aguinaldo',cred.amount, 0)), 2) + " .
                      "                        ROUND(SUM(IF(cred.type='bonificacion RAF',cred.amount, 0)), 2) + " .
                      "            (-1  * (ROUND(SUM(IF(deb.type='BUS TRANSPORTATION',deb.amount, 0)), 2) + ROUND(SUM(IF(deb.type='CAR PARKING',deb.amount, 0)), 2) + ROUND(SUM(IF(deb.type='MOTORCYCLE PARKING',deb.amount, 0)), 2) + " .
                      "            ROUND(SUM(IF(deb.type IN('HEADSET', 'Descuento Por Servicio de Active Parking', 'TARJETA DE ACCESO/PARQUEO', 'Tarjeta De Acceso'),deb.amount, 0)), 2) + ROUND(SUM(IF(deb.type='ISR',deb.amount, 0)), 2) + " .
                      "            ROUND(SUM(IF(deb.type='Descuento IGSS',deb.amount, 0)), 2) + ROUND(SUM(IF(deb.type='PERSONAL LOAN',deb.amount, 0)), 2) + ROUND(SUM(IF(deb.type='Boleto de Ornato',deb.amount, 0)), 2) + " .
                      "            ROUND(SUM(IF(deb.type IN('Descuento Judicial', 'Acuerdo Judicial'),deb.amount, 0)), 2) )) " .
                      "WHEN @V_EXTERNAL_ID = '46000' THEN ROUND(SUM(IF(deb.type='BUS TRANSPORTATION',deb.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '46000' THEN ROUND(SUM(IF(deb.type='CAR PARKING',deb.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '46000' THEN ROUND(SUM(IF(deb.type='MOTORCYCLE PARKING',deb.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '46000' THEN ROUND(SUM(IF(deb.type IN('HEADSET', 'Descuento Por Servicio de Active Parking', 'TARJETA DE ACCESO/PARQUEO', 'Tarjeta De Acceso'),deb.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21081' THEN ROUND(SUM(IF(deb.type='ISR',deb.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21085' THEN ROUND(SUM(IF(deb.type='Descuento IGSS',deb.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '46000' THEN ROUND(SUM(IF(deb.type='PERSONAL LOAN',deb.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '22050' THEN ROUND(SUM(IF(deb.type='Boleto de Ornato',deb.amount, 0)), 2) " .
                      "WHEN @V_EXTERNAL_ID = '21083' THEN ROUND(SUM(IF(deb.type IN('Descuento Judicial', 'Acuerdo Judicial'),deb.amount, 0)), 2) " .
                    "ELSE 0.00 " .
                  "END, 0.00) AS amount, pay.id_account_py, COALESCE(a2.department, '') AS department, COALESCE(a2.class, '') AS class, COALESCE(a2.site, '') AS site, COALESCE(a2.clientNetSuite, '') AS clientNetSuite " .
                "FROM payments pay " .
                "INNER JOIN periods per ON (pay.id_period = per.idperiods) " .
                "LEFT JOIN accounts a2 ON (pay.id_account_py = a2.idaccounts) " .
                "LEFT JOIN debits deb ON (pay.idpayments = deb.id_payment) " .
                "LEFT JOIN credits cred ON (pay.idpayments = cred.id_payment) " .
                "WHERE pay.id_period = $AID_Period " .
                "AND pay.id_account_py = $v_account;";
                
            if ($result4 = mysqli_query($con,$sql4)) {
              while($row4 = mysqli_fetch_assoc($result4)){
                $exportRow[$i]['external_id'] = $V_EXTERNAL_ID;
                $exportRow[$i]['name'] = $V_NAME_ACCOUNT;
                $exportRow[$i]['clasif'] = $V_CLASIF;
                $exportRow[$i]['department'] = ($row4['department']);
                $exportRow[$i]['class'] = ($row4['class']);
                $exportRow[$i]['site'] = ($row4['site']);
                $exportRow[$i]['amount'] = ($row4['amount']);
                $exportRow[$i]['clientNetSuite'] = ($row4['clientNetSuite']);
                $i++;
              }
            } else {
              http_response_code(404);
              echo($con->error);
              echo($sql4);
            }
          }
        } else {
          http_response_code(432);
          echo($con->error);
          echo($sql32);
        }
      } else {
        http_response_code(430);
        echo($con->error);
        echo($sql30);
      }
    }
  } else {
    http_response_code(402);
    echo($con->error);
    echo($sql2);
  }
} else {  
  $sql5 = "SELECT
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

  if($result5 = mysqli_query($con,$sql5)){
    $i = 0;
    while($row5 = mysqli_fetch_assoc($result5)){
        $exportRow[$i][0] = $row5['id_period'];
        $exportRow[$i][1] = $row5['account'];
        $exportRow[$i][2] = $row5['external_id'];
        $exportRow[$i][3] = $row5['name'];
        $exportRow[$i][4] = $row5['amount'];
        $i++;
    }
    echo(json_encode($exportRow));
  }else{
    http_response_code(405);
    echo($con->error);
    echo($sql5);
  }
}

$resultado = json_encode($exportRow);
echo($resultado);
}
catch (\Throwable $th) {
  //throw $th;
}
?>
