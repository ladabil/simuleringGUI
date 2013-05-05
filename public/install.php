<?php

require_once(dirname(__FILE__) . "/../hidden/config.inc.php");

require_once($GLOBALS["cfg_hiddendir"] . "/AuthLib.class.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/AuthLibUser.class.inc.php");
require_once($GLOBALS["cfg_hiddendir"] . "/AuthLibSession.class.inc.php");

// Slett alle tabeller
AuthLibSession::dropTable();
AuthLibUser::dropTable();

// Opprett tabeller
AuthLibUser::createTableIfNotExists();
AuthLibSession::createTableIfNotExists();

// Legg til testdata

if ( !AuthLib::registerUser("admin","testpass","Admin testus", "runar@trollfjordbb.no", AuthLib::$accessLevelAdmin, TRUE) )
{
	die("Add Admin failed<br>\n");
}

if ( !AuthLib::registerUser("bruker","testpass","Bruker testus", "runar@trollfjord.no", AuthLib::$accessLevelUser, TRUE) )
{
	die("Add Bruker failed<br>\n");
}


?>