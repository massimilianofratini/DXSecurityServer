<?php
session_start();

require_once 'engineList.php';
require_once 'getUserInfo.php';
require_once 'mylogger.php';
require_once 'sm_session.php';

function buildURL($srvname) {
    $pageURL = 'http';
    if (isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    mydebug('pageURL: '.$pageURL);
    mydebug('SERVER_PORT: '.$_SERVER["SERVER_PORT"]);
    if (isset($_SERVER["SERVER_PORT"]) && $_SERVER["SERVER_PORT"] != "80") {
//        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"]; //.$_SERVER["REQUEST_URI"];
        $pageURL .= $srvname.":".$_SERVER["SERVER_PORT"]; //.$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $srvname; //.$_SERVER["REQUEST_URI"];
    }
    mydebug('pageURL: '.$pageURL);
    return $pageURL;
}
function insert_group($code1,$code2)
{
	?>
		<div class="form-group">
			<?php echo $code1;?>
			<?php echo $code2;?>
		</div>
	<?php
}
function insertdiv($code)
{
?> 
		<div class="col-sm-6">
			<?php echo $code;?>
		</div>
<?php
}


?>
<html>
<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=8,9,10">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate, max-age=0">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">

        <link rel="icon" type="image/x-icon" href="/dxcore/image/delphix-favicon.ico">

        <title>Delphix Security Portal</title>

        <link rel="stylesheet" type="text/css" href="/login/style/styles.css?build=2e093d0e03118207cb470148e789cf866ddfe611">
</head>


<body class="body-style">
    

<div id="main-body" class="main-body default" style="">
    <div id="loginDiv" class="login-div" >
        <div class="delphix-server-logo" id="serverLogoStandard" ></div>
        <div class="delphix-free-server-logo" id="serverLogoFree"  style="display: none;"></div>
        <div class="delphix-setup-logo" id="setupLogoFree"  style="display: none;">
            <img src="/login/assets/images/gear.png">
            <img src="/login/assets/images/delphix-express-black.png" class="setup-logo">
        </div>
        <div class="delphix-setup-logo" id="setupLogoStandard"  style="display: none;">
            <img src="/login/assets/images/gear.png">
            <img src="/login/assets/images/delphixSetup.png" class="setup-logo">
        </div>
        <div class="login-input-wrapper">
            <form id="loginForm" class="form-horizontal">

<?php
	$prf = store_profile_in_session();
	if ($prf==false)
	{
		mydebug("Session profile not found");
		insertdiv("<span>Delphix did not receive profile information, cannot go ahead</span>");
	}
	else
	{
		mydebug("Session profile is ".$_SESSION["profile"]);
		//get user info
		$usr_profile=get_user_info($_SESSION["profile"]);
		if ($usr_profile==false) {
			insertdiv("<span>Utente non autorizzato</span>");
		}
		//show the available engines for the user
		$_SESSION ["userprofile"] = $usr_profile;
		mydebug ( "user profile: " . print_r ( $usr_profile, true ) );
		mydebug ( "engine list: " . print_r ( $engines, true ) );
		foreach ( $engines as $eng ) {
			if (strpos(strtoupper($usr_profile["datacenter"]),strtoupper($eng["datacenter"])) !== false) 
			{
				$name="[".$eng['datacenter']."] ".$eng['description'];
				$engurl=$eng['externalname'];
				insert_group(insertdiv("<div id='ServerID".$name."' class='links-to-apps'>
                            				<a href='".buildURL($engurl)."/'>".$name."</a>
						   				</div>"),
							 insertdiv("<div class='user-login-btn'>
                            				<input type='submit' id='btnid".$name."' class='btn btn-primary' value='Open' onclick='window.open(\"".buildURL($engurl)."\",\"Delphixe engine: ".$name."\");'>
                           				</div>")
							);
			}
		}

		//SUPERADMIN CAN MANAGE USERS
		if (strpos($usr_profile["role"],'SUPERADMIN') !== false) 
		{
			insertdiv("<div id='superadmin' class='links-to-apps'>
                            <a href='/login2/usermgmt.php'>Users Management</a>
						   </div>");
		}
?>
			</form>
        </div>
		
<?php
	}
	insertdiv("<div id='logout' class='links-to-apps'>
                            <a href='/login2/logout.php'>Logout</a>
						   </div>");
	
?>

    </div>
</div>
</body>
</html>
