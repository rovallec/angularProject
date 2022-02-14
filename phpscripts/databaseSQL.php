<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header('Access-Control-Allow-Origin: *');
  header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
  header('Access-Control-Allow-Headers: token, Content-Type');
  header('Access-Control-Max-Age: 1728000');
  header('Content-Length: 0');
  header('Content-Type: text/plain');
  die();
}

$serverName = "172.18.2.39"; //172.18.2.39
$connectionInfo = array( "Database"=>"MiNearsol_local", "UID"=>"minearsol", "PWD"=>"Nearsol.2020");

function connectSQL()
{
  $serverName = "172.18.2.39"; //172.18.2.39
  $connectionInfo = array( "Database"=>"MiNearsol_local", "UID"=>"minearsol", "PWD"=>"Nearsol.2020");
  $connSQL = sqlsrv_connect( $serverName, $connectionInfo);
  if (!$connSQL) {
    die('Error de conexión: ' . sqlsrv_errors());
  }
  return $connSQL;
}

$connSQL = connectSQL();

/*
function transactionSQL() {
  $connect = new mysqli(db_host,db_user,db_password,db_name);
  return $connect;
}

$transact = transaction();
*/
?>
