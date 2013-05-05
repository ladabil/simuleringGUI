<?php

require_once(dirname(__FILE__) . "/../hidden/config.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/AuthLib.class.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/Site.class.inc.php");

header('Content-type: text/html; charset=utf-8');

ob_start();

$GLOBALS["authlib"] = new AuthLib();

if ( !$GLOBALS["authlib"]->checkSession() )
{
	if ( $GLOBALS["authlib"]->getStatusCode() == AL_SC_CHECKSESSION_NOCOOKIE )
	{
		echo Site::processLogin();
		die();
	}
	else
	{
		Site::setInfoMessage($GLOBALS["authlib"]->getStatusMessage());
		unset($GLOBALS["authlib"]);
		echo Site::getLoginForm();
		die();
	}
}

echo Site::parseRequest();

?>