<?php

// Mysqli configuration
$GLOBALS["mysqli_hostname"] = "jenna.bendiksens.net";
$GLOBALS["mysqli_username"] = "gruppe2it";
$GLOBALS["mysqli_password"] = "123";
$GLOBALS["mysqli_database"] = "gruppe2it";

ini_set("SMTP", "localhost");

date_default_timezone_set("Europe/Oslo");

$GLOBALS["cfg_hiddendir"] = dirname(__FILE__);
$GLOBALS["cfg_publicdir"] = dirname(__FILE__) . "/../public/";

$GLOBALS["cfg_tokensecret"] = "kdksl#dlsaDFfkdlskKSDK343432 2333";
$GLOBALS["cfg_tokentype"] = "sha256";
$GLOBALS["cfg_tokenName"] = "TokenName";
?>