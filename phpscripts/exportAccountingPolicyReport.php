<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . "reportAccountingPolicy.csv" . '"');
require 'database.php';

echo "\xEF\xBB\xBF";
$AID_Period = $_GET['AID_Period'];
$i = 0;

$sql1 = "SELECT COUNT(*) AS account FROM policies WHERE id_period = $AID_Period;";
$result1 = mysqli_query($con,$sql1);
$row1 = mysqli_fetch_assoc($result1);
$allOk = false;


if ($row1['account'] == 0) {
  $sql2 = "SET @AID_PERIOD=$AID_Period;";
  $sql3 = "PREPARE S FROM 'CALL GENERATE_ACCOUNTING_POLICY( ? )';";
  $sql4 = "EXECUTE S USING @AID_PERIOD;";

  if(mysqli_query($con,$sql2)){
    if(mysqli_query($con,$sql3)){
        if(mysqli_query($con,$sql4)){
            http_response_code(200);
            echo("1");
        } else {
            http_response_code(404);
            echo($con->error);
        }
    } else {
        http_response_code(403);
        echo($con->error);
    }
  } else {
      http_response_code(402);
      echo($con->error);
  }
} // Fin del IF.


if ($allOk){
  // Corremos el query del reporte.
  $sql5 = "SELECT
  a.id_period,
  c.external_id,
  c.name,
  b.amount
FROM policies a
INNER JOIN policy_details b ON (a.idpolicies = b.id_policy)
INNER JOIN accounting_accounts c on (a.id_account = c.idaccounting_accounts)
WHERE a.id_period = $AID_Period;";

$title = ['Período', 'Cuenta', 'Nombre', 'Monto'];
$output = fopen("php://output", "w");
fputcsv($output, $title);
if($result5 = mysqli_query($con,$sql5)){
    while($row5 = mysqli_fetch_assoc($result5)){
        $exportRow[0] = $row5['id_period'];
        $exportRow[1] = $row5['external_id'];
        $exportRow[2] = $row5['name'];
        $exportRow[3] = $row5['amount'];
        fputcsv($output, $exportRow,",");
        $i++;
    };
}else{
    http_response_code(404);
}
fclose($output);

}


?>
