<?php

require_once(dirname(__FILE__) . "/../hidden/config.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/AuthLib.class.inc.php");

ob_start();

$GLOBALS["authlib"] = new AuthLib();

if ( !$GLOBALS["authlib"]->checkSession() )
{
	if ( $GLOBALS["authlib"]->getStatusCode() == AL_SC_CHECKSESSION_NOCOOKIE )
	{
		echo "Loginskjema";
		die();
	}
	else
	{
		echo "Here";
		echo "<h1>" . $GLOBALS["authlib"]->getStatusMessage() . "</h1>";
		echo "<br>\n";
	}
}

echo "Test";

?>