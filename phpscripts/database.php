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

define('db_host', 'localhost');
define('db_user','neadmin');
define('db_password','N$4rsol.@dmin');
define('db_name','minearsol');

function connect()
{
	$connect = mysqli_connect(db_host,db_user,db_password,db_name);
  if (!$connect) {
    die('Error de conexión: ' . mysqli_connect_error());
  }
	/*if(mysqli_connect_errno($connect))
	{
		die("Failed to connect: " . mysqli_connect_errno());
	}
*/
	return $connect;
}

$con = connect();
?>