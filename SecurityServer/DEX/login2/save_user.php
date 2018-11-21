<?php
require_once('../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('conn.php');

$username = htmlspecialchars($_REQUEST['username']);
$password = htmlspecialchars($_REQUEST['password']);
$role = htmlspecialchars($_REQUEST['role']);
$resources = htmlspecialchars($_REQUEST['resources']);

$key = myhex2bin(Crypto::SEED);
$password = base64_encode(Crypto::encrypt($password, $key));

$sql = "insert into users(username,password,role,datacenter) values('$username','$password','$role','$resources')";
$conn = ConnectionFactory::getFactory()->getConnection();
$result = $conn->query($sql);
error_log($result);
if ($result){
	echo json_encode(array(
		'id' => $conn->lastInsertRowId(),
		'username' => $username,
		'password' => ($password),
		'role' => $role,
		'resources' => $resources
	));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
