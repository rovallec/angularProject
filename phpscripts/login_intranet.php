<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: *');

$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$usr = ($request->username);
$pss = ($request->password);


define('db_host', 'localhost');
define('db_user', $usr);
define('db_password', $pss);
define('db_name','intranet');

echo('hola');

$authUser = [];

$authUser['idusers'] = 'NULL';

try {
	$mysqlc = mysqli_connect(db_host,db_user,db_password,db_name) or die(json_encode($authUser));

	if (mysqli_connect_errno()) {
		echo(mysqli_error($mysqlc));
		$authUser['idusers'] = 'NULL';
	}else{
		echo(mysqli_error($mysqlc));
		if($mysqlc){
			echo(mysqli_error($mysqlc));
			$sql = "SELECT * FROM `users` where username = '{$usr}';";
			if($result = $mysqlc->query($sql)){
				while($row = mysqli_fetch_assoc($result)){
					$authUser['idusers'] = $row['idusers'];
					$authUser['username'] = $row['username'];
					$authUser['department'] = $row['department'];
					$authUser['user_name'] = $row['user_name'];
					$authUser['active'] = $row['active'];
					$authUser['id_role'] = $row['id_role'];
					$authUser['signature'] = $row['signature'];
					$authUser['id_profile'] = $row['id_profile'];

				};
			} else {
				$authUser['idusers'] = 'NULL';
				echo(mysqli_error($mysqlc));
			};
		} else {
			$authUser['idusers'] = 'NULL';
			echo(mysqli_error($mysqlc));
		}
	}
} catch (\Throwable $th) {
		http_response_code(400);
		$authUser['idusers'] = 'NULL';
		echo(mysqli_error($mysqlc));
}

echo json_encode($authUser);
?>