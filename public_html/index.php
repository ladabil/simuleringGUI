<?php

require_once(dirname(__FILE__) . "/../hidden/config.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/AuthLib.class.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/Site.class.inc.php");

ob_start();

header("content-type: text/html; charset: utf-8");

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
		echo Site::getLoginForm();
		die();
	}
}

echo Site::parseRequest();

?>