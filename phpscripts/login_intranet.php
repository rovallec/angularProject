<?php

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

function connect(){
	$connect = mysqli_connect(db_host,db_user,db_password,db_name);
	return $connect;
};

$mysqlc = new mysqli(db_host,db_user,db_password,db_name);

if($mysqlc){
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
		};
	}else{
		$authUser['idusers'] = 'NULL';
	};
}else{
	$authUser['idusers'] = 'NULL';
}
echo json_encode($authUser);
?>