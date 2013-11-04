<?php 

function kobleTil($databasenavn) 
{
	$vert = "jenna.bendiksens.net";
	$brukernavn = "gruppe2it";
	$passord = "123";
	$tilkobling = mysql_connect($vert, $brukernavn, $passord);
	
	if (!$tilkobling)
	{
		die("Kunne ikke koble til: " . mysql_error());
	}
	
	$velgDB = mysql_select_db($databasenavn, $tilkobling);
	if(!$velgDB) 
	{
		die("kunne ikke bruke databasen: " . mysql_error());
	}
	return $tilkobling;
}

function hentNokkelVerdiForXML($sqlFd, $className)
{
	$retStr = "";

	$sql = "SELECT * FROM simValue WHERE Class LIKE '" . strtolower($className) . "'";
	$result = mysql_query($sql);

	if ( !$result || mysql_num_rows($result) <= 0 )
	{
		return "";
	}

	while ( $row = mysql_fetch_assoc($result) )
	{
		$retStr .= "<" . $row['Name'] . ">";
		$retStr .= $row['Value'];
		$retStr .= "</" . $row['Name'] . ">\n";
	}

	return $retStr;
}


function hentVaerstasjonsNavn($sqlFd, $wsId)
{
	$retStr = "";

	$sql = "SELECT `name` FROM `weatherStations` WHERE `stnr`=" . intval($wsId) . " LIMIT 1";
	$result = mysql_query($sql);

	if ( !$result || mysql_num_rows($result) !== 1 )
	{
		return "Ukjent";
	}
	
	return mysql_result($result, 0, 'name');
}

?>		