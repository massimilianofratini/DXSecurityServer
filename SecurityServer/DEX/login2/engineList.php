<?php 
require_once 'conn.php';
require_once 'mylogger.php';

//Load list of engines
$engines = getEngineList();


function getEngineList()
{
	$conn = ConnectionFactory::getFactory()->getConnection();
	
	$rs = $conn->query("select * from engine_list");
	$_engines = array();
	while ($row = $rs->fetchArray(SQLITE3_ASSOC)) {
		array_push($_engines, $row);
	}
	mydebug("engine list:".print_r($_engines,true));
	return $_engines;
}

//Get internal ip
function getEngineInfoByExtName($extname)
{
	mydebug("engine external name:".$extname);
	if (!isset($engines))
		$engines = getEngineList();
	
	$found=0;
	foreach ($engines as $eng) {
		mydebug("externalname:".$eng["externalname"]);
		if (strtoupper($eng["externalname"])==strtoupper($extname)) {
			$found=$eng;
			break;
		}
	}
	if ($found==0)
		return false;
	else
		return $found;
}

//Get engine ip
function getInternalIP($extname)
{
	mydebug("server name:".$extname);

	if (!isset($engines))
		$engines = getEngineList();

		$found=0;
		foreach ($engines as $eng)
		{
			mydebug("externalname:".$eng["externalname"]);

			#if (strtoupper($eng["externalname"])==strtoupper($_SERVER["SERVER_NAME"]))
			if (strtoupper($eng["externalname"])==strtoupper($extname))
			{
				$deip=$eng["internalip"];
				$found=1;
				break;
			}
		}
		if ($found==0)
			return false;
		else
			return $deip;
}
		
//$engine['DE1.localdomain'] = '172.16.226.131';
//$engine['DE2.localdomain'] = '172.16.226.132';
?>
