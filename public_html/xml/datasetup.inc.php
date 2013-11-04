<?php 

$tilkobling =kobleTil("gruppe2it");

$sql2 = "SELECT * FROM SimStoring WHERE id=" . intval($_REQUEST['id']);
$result2 = mysql_query($sql2) or die(mysql_error());
$row = mysql_fetch_array ($result2);

$climateZone = $row['climateZone'];
$climateTemperatureOffset = $row['climateTemperatureOffset'];
$climateWeatherStation = $row['climateWeatherStation'];

if ( intval($climateWeatherStation) > 0 )
{
	$climateWeatherStationTxt = hentVaerstasjonsNavn($tilkobling, $climateWeatherStation);
}
else
{
	$climateWeatherStationTxt = "ikke valgt, benytter klimasone";
}


$climateZone = $row['climateZone'];
$climateZoneTxt = "";

if ( intval($climateZone) <= 0 || intval($climateZone) > 7 )
{
	// Default klimasone er 1 -> S�r-norge
	$climateZone = 1;
}

switch ( $climateZone )
{
	case 1:
	default:
		$climateZoneTxt = "Sør-Norge, kyst";
		break;
	case 2:
		$climateZoneTxt = "Sør-Norge, innland";
		break;
	case 3:
		$climateZoneTxt = "Sør-Norge, høyfjell";
		break;
	case 4:
		$climateZoneTxt = "Midt-Norge, kyst";
		break;
	case 5:
		$climateZoneTxt = "Midt-Norge, innland";
		break;
	case 6:
		$climateZoneTxt = "Nord-Norge, kyst";
		break;
	case 7:
		$climateZoneTxt = "Finnmark og innland Troms";
		break;
}

?>