<?php
include 'crypto.php';
include 'mylogger.php';
include 'conn.php';

function updateUser($array, $condition) 
{
		$conn = ConnectionFactory::getFactory()->getConnection();
		
		mydebug("editUser array:".print_r($array,true));
		mydebug("editUser key:".print_r($condition,true));
		
        /*Assuming array keys are = to database fileds*/
        if (count($array) > 0) {
            foreach ($array as $_key => $_value) {
				if (!is_numeric($_value))
                {
					$_value = SQLite3::escapeString($_value); 
					$_value = "'$_value'";
				}
                $updates[] = "$_key = $_value";
            }
        }
        $implodeArray = implode(', ', $updates);
        $sql = ("UPDATE users SET ".$implodeArray." WHERE ".$condition);
        mydebug("editUser sql:".print_r($sql,true));
		return $conn->query($sql);
}



//---main
$usr['id'] = intval($_REQUEST['id']);
$usr['username'] = htmlspecialchars($_REQUEST['username']);
$usr['password'] = htmlspecialchars($_REQUEST['password']);
$usr['role'] = htmlspecialchars($_REQUEST['role']);
$usr['status'] = htmlspecialchars($_REQUEST['status']);
$usr['datacenter'] =  '';
foreach ($_REQUEST['datacenter'] as $ngn) {
	if ($usr['datacenter']=='') 
		$usr['datacenter'] = htmlspecialchars($ngn);
	else 
		$usr['datacenter'] .= ",".htmlspecialchars($ngn);
	mydebug("collecting user data:".print_r($usr,true));
	
	}
	
mydebug("user data:".print_r($usr,true));

$key = myhex2bin(Crypto::SEED);
$usr['password'] = Crypto::encrypt(($usr['password']), $key);
error_log("encrypted password:".$usr['password']);
$usr['password'] = base64_encode($usr['password']);
mydebug("base64 password:".$usr['password']);

$passwordcheck=base64_decode($usr['password']);
mydebug("base64 decoded password:".$passwordcheck);
$passwordcheck = Crypto::decrypt($passwordcheck, $key);
mydebug("decrypted password:".$passwordcheck);

$result = updateUser($usr, "id=".$usr['id']);
mydebug("result is:".print_r($result,true));

if ($result)
{
	$jsonarray = json_encode(array(
		'id' => $usr['id'],
		'username' => $usr['username'],
		'password' => $usr['password'],
		'role' => $usr['role'],
		'datacenter' => $usr['datacenter'],
		'status' => $usr['status']
	));
	mydebug("jsonarray:".print_r($jsonarray,true));
		
	echo $jsonarray;
} 
else 
{
	echo json_encode(array('errorMsg'=>'Some errors occured.'));
}


?>
