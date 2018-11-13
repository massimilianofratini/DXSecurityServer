<?php
// ./adapter.sh "\\\"Codice Operazione\\\"\\\"Numero Operazione\\\"\\\"Matricola\\\"\\\"Codice Sistema\\\"\\\"User ID\\\"\\\"Codice Profilo\\\"\\\"Cognome\\\"\\\"Nome\\\"\\\"Datacenter\\\""

//Codice operazione:
//	Creazione_User_ID 	-->  params 1-2-3-4-5-6-7-8-9
//	Cancellazione_User_ID	-->  params 1-2-3-4-5
//	Sostituzione_Profilo	-->  params 1-2-3-4-5-6- - -9

include '../mylogger.php';
include '../conn.php';

if (isset($_GET['niamrequest']))
	$params=$_GET['niamrequest'];
else
	$params = $argv[1];

mydebug("\nparams:$params");
$paramsArr=explode('\"\"', $params);

$codoperazione	=str_replace('\"', '', $paramsArr[0]);
$numoperazione	=$paramsArr[1]; 
$matricola		=$paramsArr[2]; 
$codsistema		=$paramsArr[3]; 
$userid			=$paramsArr[4]; 
$codprofilo		=$paramsArr[5];
$cognome		=$paramsArr[6];
$nome			=$paramsArr[7];
$datacenter		=str_replace('\"', '', $paramsArr[8]);
$opstatus		='REQUESTED'; 

$sql = "insert into niam (codoperazione,numoperazione,matricola,codsistema,userid,codprofilo,cognome,nome,datacenter,opstatus) 
			values ('$codoperazione','$numoperazione','$matricola','$codsistema','$userid','$codprofilo','$cognome','$nome','$datacenter','$opstatus')";
mydebug("SQL: ".$sql);

$conn = ConnectionFactory::getFactory()->getConnection();
$result = $conn->query($sql);
mydebug("SQL result: ".print_r($result,true));

if ($result){
	print "OK";
	exit(0);
}
else {	
	print "KO";
	exit(1);
}
?>