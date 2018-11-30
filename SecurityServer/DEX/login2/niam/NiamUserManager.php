<?php
/*
ESEMPIO PROFILI NIAM
CODSISTEMA	CODPROFILO	        DATACENTER (DC_EXTNAME_APP)
DELPHIX	    delphix_admin	    BO_FRdeBO01
DELPHIX	    delphix_admin	    BO_FRdeBO02
DELPHIX	    delphix_operator	PD_FRdePD01_NGOM
DELPHIX	    delphix_operator	BO_FRdeBO01_DYNAMICINVENTORY
DELPHIX	    delphix_readonly	PD_FRdePD01_NGOM
DELPHIX	    delphix_readonly	BO_FRdeBO01_DYNAMICINVENTORY
DATA MASK	msk_admin	        MI_DMmskMI01
DATA MASK	msk_admin	        PD_DMmskPD01
DATA MASK	msk_operator	    MI_DMdeMI01_CRMA
DATA MASK	msk_operator	    MI_DMdeMI01_DRU
DATA MASK	msk_readonly	    PD_DMmskPD01_TRCS
DATA MASK	msk_readonly	    PD_DMmskPD01_XBSM
 */


// codoperazione,numoperazione,matricola,codsistema,userid,codprofilo,cognome,nome,datacenter,opstatus
// codoperazione:
//	Creazione_User_ID 	-->  params 1-2-3-4-5-6-7-8-9
//	Cancellazione_User_ID	-->  params 1-2-3-4-5
//	Sostituzione_Profilo	-->  params 1-2-3-4-5-6- - -9
require_once(__DIR__ . '/../../../common/secsrv_includes/config.php');
require_once_common('mylogger.php');
require_once_common('conn.php');

$conn = ConnectionFactory::getFactory()->getConnection();
$sql = "select * from niam where opstatus='REQUESTED'";
$result = $conn->query($sql);
while ($r=$result->fetchArray(SQLITE3_ASSOC)) {
	mydebug("operazione: ".print_r($r,true));
	$codoperazione=$r['codoperazione'];
	$numoperazione=$r['numoperazione'];
	$username=$r['userid'];
	$codsistema=$r['codsistema'];
	$timestamp=$r['timestamp'];

	if (!in_array($codsistema, array("DELPHIX", "DATA MASK"))){
		$opexecuted=false;
		$opdetails="codice sistema errato: $codsistema";
		mydebug($opdetails);
	}
	else {
		switch ($r['codoperazione']) {
			case "Creazione_User_ID":
				$role=$r['codprofilo'];
				$datacenter=$r['datacenter'];
				
				$sql = "update users set status='INACTIVE', lastupdate='$timestamp' where username='$username' and status='ACTIVE'";
				mydebug($sql);
				$conn->exec($sql);
				
				$sql = "insert into users (username,password,role,datacenter,status,lastupdate) values ('$username','none','$role','$datacenter','ACTIVE','$timestamp')";
				mydebug($sql);
				$opexecuted=$conn->exec($sql);
				
				break;
				
			case "Sostituzione_Profilo":
				$role=$r['codprofilo'];
				$datacenter=$r['datacenter'];
				
				//controlla se è stato cancellato, per cui non si può sostituire
				$sql = "select count(*) from users where username='$username' and status='DELETED' ";
				mydebug($sql);
				$deleted = $conn->querySingle($sql);
				if ($deleted>0) {
					$opexecuted=false;
					$opdetails="username $username cancellato, impossibile cambiare profilo";
					mydebug($opdetails);
					break;
				}
					
				//storicizza stato precedente
				$sql = "update users set status='INACTIVE', lastupdate='$timestamp' where username='$username' and status='ACTIVE'";
				mydebug($sql);
				$conn->exec($sql);
				
				if ($conn->changes()>0) {
					//se update ok vuol dire che esisteva quindi inserisco nuovo ruolo
					$sql = "insert into users (username,password,role,datacenter,status) values ('$username','none','$role','$datacenter','ACTIVE')";
					mydebug($sql);
					$opexecuted=$conn->exec($sql);
				}
				else {
					//se update not ok vuol dire che l'utente non esisteva quindi errore
					$opexecuted=false;
					$opdetails="username $username non esistente, impossibile cambiare profilo";
					mydebug($opdetails);
						
				}
				break;
				
			case "Cancellazione_User_ID":
				//controlla se è stato cancellato, per cui non si può cancellare di nuovo
				$sql = "select count(*) from users where username='$username' and status='DELETED' ";
				mydebug($sql);
				$deleted = $conn->querySingle($sql);
				if ($deleted>0) {
					$opexecuted=false;
					$opdetails="username $username gia cancellato, impossibile cancellare nuovamente";
					mydebug($opdetails);
					break;
				}
				
				$sql = "update users set status='DELETED', lastupdate='$timestamp' where username='$username' and status='ACTIVE'";
				mydebug($sql);
				$conn->exec($sql);
				if ($conn->changes()>0)
					//se update ok vuol dire che esisteva quindi inserisco nuovo ruolo
					$opexecuted=true;
				else {
					$opexecuted=false;
					$opdetails="username $username non esistente";
					mydebug($opdetails);
				}
				break;
	
		}
	}
	if ($opexecuted)
		$sql = "update niam set opstatus='OK',ack='NOACK' where numoperazione='$numoperazione'";
	else {
		if($opdetails=="") 
			$opdetails= $conn->lastErrorMsg();
		$sql = "update niam set opstatus='KO',ack='NOACK', opdetails='$opdetails' where numoperazione='$numoperazione'";
	
	}
	mydebug($sql);
	$conn->exec($sql);
}
?>