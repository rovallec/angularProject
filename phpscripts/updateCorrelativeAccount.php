<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');
require 'database.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$employee = ($request->employee);
$account_id = ($request->account);
$client_id = $request->client_id;
$correlative = 0;

try {
    $correlative = $request->correlative;
} catch (\Throwable $th) {
    $correlative = 1;
}

if ($correlative == 0) {
    $correlative = 1;
}

$sqlc = "SELECT correlative FROM accounts WHERE idaccounts = $account_id;";
if ($result = mysqli_query($con, $sqlc)){
    $row = mysqli_fetch_assoc($result);
    $corr = $row['correlative'];
}


if ($corr > $correlative) {
    $correlative = $corr + 100;
}

$sql2 = "UPDATE accounts SET correlative = $correlative where idaccounts = $account_id;";

//$sql2 = "UPDATE accounts SET correlative = correlative + 1 where idaccounts = $account_id;";
$sql = "UPDATE `employees` SET `id_account` = '$account_id' WHERE `idemployees` = '$employee'";

if (mysqli_query($con, $sql)){
    if (mysqli_query($con, $sql2)) {
        echo(json_encode('1'));
        http_response_code(200);
    } else {
        echo(json_encode($sql2));
        http_response_code(422);
    }
} else {
    echo(json_encode($sql));
    http_response_code(421);
}
?>