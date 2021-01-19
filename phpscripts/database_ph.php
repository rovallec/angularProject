<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

define('db_host', 'localhost');
define('db_user','neadmin');
define('db_password','N$4rsol.@dmin');
define('db_name','minearsol_ph');

function connect()
{
	$connect = mysqli_connect(db_host,db_user,db_password,db_name);
	if(mysqli_connect_errno($connect))
	{
		die("Failed to connect: " . mysqli_connect_errno());
	}

	return $connect;
}

$con = connect();
?>