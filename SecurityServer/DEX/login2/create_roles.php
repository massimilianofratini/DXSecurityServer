<?php
require_once(__DIR__ . '/../../common/secsrv_includes/config.php');
require_once_common('crypto.php');
require_once_common('conn.php');



$roles[0]=array('SUPERADMIN','sysadmin','Pippo.123');
$roles[1]=array('ADMINISTRATOR','delphix_admin','Pippo123');
$roles[2]=array('OPERATOR','delphix_oper','delphix_oper');

$conn = ConnectionFactory::getFactory()->getConnection();
$key = myhex2bin(Crypto::SEED);

foreach ($roles as $r) {
        $password = base64_encode(Crypto::encrypt($r[2], $key));
        $sql = "insert into roles_type (role,de_username,de_password) values('$r[0]','$r[1]','$password')";
        print "\n$sql";
        $result = $conn->query($sql);
}
?>
