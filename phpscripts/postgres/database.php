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

define('db_host', '172.18.200.21');
//define('db_host', 'localhost');
define('db_user','neadmin');
define('db_password','N$4rsol.@dmin');
define('db_name','minearsol');

function connect()
{
    $connect = pg_connect("host=" . db_host ." dbname=". db_name . " user=" . db_user . " password=" . db_password)
    or die('No se ha podido conectar: ' . pg_last_error());

  if (!$connect) {
    die('Error de conexión: ' . pg_result_error($connect));
  }
	/*if(mysqli_connect_errno($connect))
	{
		die("Failed to connect: " . mysqli_connect_errno());
	}
*/
	return $connect;
}

$con = connect();

function transaction() {
	$connect = new PDO("pgsql:host=" . db_host ." dbname=". db_name . " user=" . db_user . " password=" . db_password)
	or die('No se ha podido conectar: ' . pg_last_error());

	if (!$connect) {
	  die('Error de conexión: ' . pg_result_error($connect));
	}
	return $connect;
}

$transact = transaction();
?>
