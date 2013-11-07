<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once(dirname(__FILE__) . "/../hidden/config.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/AuthLib.class.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/Site.class.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");

//header('Content-type: text/html; charset=utf-8');
//header('Content-type: text/html; charset=iso-8859-1');

session_start();
ob_start();

echo Site::parseRequest();

?>