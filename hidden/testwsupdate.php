<?php

error_reporting(E_ALL);


require_once(dirname(__FILE__) . "/config.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/AuthLib.class.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/Site.class.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/EnergySimulator.class.inc.php");
require_once(dirname(__FILE__) . "/Site.class.inc.php");

echo Site::updateWeatherStationList();

?>