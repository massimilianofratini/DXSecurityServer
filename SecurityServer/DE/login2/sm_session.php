<?php

function store_profile_in_session ()
{
	mydebug("_SERVER:".print_r($_SERVER,true));

	mydebug("Checking if the profile is already in session");
	if (! isset($_SESSION["profile"]))
	{
		mydebug("profile not found in session, check if Siteminder header exist");
		if  ( isset($_SERVER[Cfg::SM_UID]) )
		{
			mydebug("setting session with Siteminder profile found in headers");
			$_SESSION["profile"] = $_SERVER[Cfg::SM_UID];
		}
		else
		{
			mydebug("Siteminder profile not found in heade, check if we have a cookie");
			if  ( isset($_COOKIE["profile"]) )
			{
				mydebug("Cookie found, set in session");
				$_SESSION["profile"] = $_COOKIE["profile"];
			}
			else
			{
				mydebug("COOKIE profile not found");
				echo "Session Closed";
				return false;
			}
		}
	}
	
	return $_SESSION["profile"];
}
?>