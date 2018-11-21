<?php
require_once('../../common/secsrv_includes/config.php');
require_once_common('conn.php');

$id = intval($_REQUEST['id']);

$conn = ConnectionFactory::getFactory()->getConnection();
	
$sql = "delete from users where id=$id";
$result = $conn->query($sql);
if ($result){
	echo json_encode(array('success'=>true));
} else {
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}
?>
