<?PHP

require_once(dirname(__FILE__) . "/../xml/std.inc.php");
require_once(dirname(__FILE__) . "/../xml/datasetup.inc.php");

// kjører database kolonne-resultat til variabler
$name = $row['name'];
$priHeat = $row['priHeat'];
$secHeat = $row['secHeat'];
$houseTotalArea = $row['houseTotalArea'];
$housePrimaryArea = $row['housePrimaryArea'];
$houseBuildYear = $row['houseBuildYear'];
$ytterveggAreal = $row['ytterveggAreal'];
$yttertakAreal = $row['yttertakAreal'];
$vinduDorAreal = $row['vinduDorAreal'];
$luftVolum = $row['luftVolum'];
$onsketTemp = $row['onsketTemp'];
$heatDiff = $row['heatDiff'];
$floorHeatWa = $row['floorHeatWa'];
$floorHeatEl = $row['floorHeatEl'];
$priBoilerSize = $row['priBoilerSize'];
$priBoilerPower = $row['priBoilerPower'];
$numLight = $row['numLight'];
$priLightType = $row['priLightType'];
$secLightType = $row['secLightType'];
$lightTime = $row['lightTime'];
$lightDiff = $row['lightDiff'];
$numHvit = $row['numHvit'];
$numBrun = $row['numBrun'];

$startTime = $row['startTime'];
$endTime = $row['endTime'];
$opplosning = $row['opplosning'];

$building = $row['building'];
$houseBuildYear = $row['houseBuildYear'];

if($building == '1') {$building = "Enebolig";}
if($building == '2') {$building = "Leilighet";}
if($building == '3') {$building = "Rekkehus";}
		
if($houseBuildYear == '1') {$houseBuildYear = "Før 1987";}
if($houseBuildYear == '2') {$houseBuildYear = "Mellom 1987 og 1997";}
if($houseBuildYear == '3') {$houseBuildYear = "Etter 1997";}


$inhabForCSVArr = Array();
if ( isset($inhabitantsArr) && count($inhabitantsArr) > 0 )
{
	foreach ( $inhabitantsArr as $inhabitant )
	{
		$inhabForCSVArr[] = array("Type" => "Person", "Verdi" => utf8_encode('Person: '. $inhabitant->age . '�r, ' . $inhabitant->sexAsText . ', Yrke: ' . $inhabitant->work . ''));
	}
}
else
{
	$inhabForCSVArr[] = array("Type" => "Person", "Verdi" => "Ingen beboere funnet");
}


$data = array(
  	//array("Hus" => " "),
  	array("Type" => "Bygg Type", "Verdi" => "$building"),
  	array("Type" => "Byggeår", "Verdi" => "$houseBuildYear"),
    array("Type" => "Brutto Areal", "Verdi" => "$houseTotalArea"),
    array("Type" => "P-Rom", "Verdi" => "$housePrimaryArea"),
  	array("Type" => "Veggareal ytre", "Verdi" => "$ytterveggAreal"),
  	array("Type" => "Takareal", "Verdi" => "$yttertakAreal"),
  	array("Type" => "Vindu og Dør areal", "Verdi" => "$vinduDorAreal"),
  	array("Type" => "Indre luftvolum", "Verdi" => "$luftVolum"),
  	array("Type" => "ønsket innetemp", "Verdi" => "$onsketTemp"),
    array("Type" => "", "Verdi" => ""),
	array("Type" => "Primær Oppvarming", "Verdi" => "$priHeat"),
	array("Type" => "Sekundær Oppvarming", "Verdi" => "$secHeat"),
	array("Type" => "Varme Differanse", "Verdi" => "$heatDiff"."%"),
	array("Type" => "Gulvvarme Vannbåren", "Verdi" => "$floorHeatWa"),
	array("Type" => "Gulvvarme Elektrisk", "Verdi" => "$floorHeatEl"),
	array("Type" => "Primær Elektrokjel (liter)", "Verdi" => "$priBoilerSize"),
	array("Type" => "Primær Elektrokjel (watt)", "Verdi" => "$priBoilerPower"),
	array("Type" => "", "Verdi" => ""),
	array("Type" => "Antall Lyskilder", "Verdi" => "$numLight"),
	array("Type" => "Primær Belysning", "Verdi" => "$priLightType"),
	array("Type" => "Sekundær Belysning", "Verdi" => "$secLightType"),
	array("Type" => "Gjennomsnittlig Brennetid (timer per dag)", "Verdi" => "$lightTime"),
	array("Type" => "Lys Differanse", "Verdi" => "$lightDiff"."%"),
	array("Type" => "", "Verdi" => "")
		);

$data = array_merge($data, $inhabForCSVArr);
		
		
$data2 = Array(
	array("Type" => "", "Verdi" => ""),
	array("Type" => "Klimasone", "Verdi" => $climateZone),
	array("Type" => "VærstasjonsId", "Verdi" => $climateWeatherStation),
	array("Type" => "TemperaturOffset", "Verdi" => $climateTemperatureOffset),
  	array("Type" => "Start tid", "Verdi" => "$startTime"),
  	array("Type" => "Slutt tid", "Verdi" => "$endTime"),
  	array("Type" => "Oppløsning", "Verdi" => "$opplosning")
  );

$data = array_merge($data, $data2);

?>

<?PHP
  function cleanData(&$str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	$str = mb_convert_encoding($str, 'UTF-16LE', 'UTF-8');
  }

  // filename for download
  $filename = "SimExport_". $name . " " .  date('Ymd') . ".xls";

  header("Content-Disposition: attachment; filename=\"$filename\"");
  header("Content-Type: application/vnd.ms-excel;  charset=UTF-16LE");

  $flag = false;
  foreach($data as $row) {
    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\r\n";
      $flag = true;
    }
    array_walk($row, 'cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
  }
  exit;
?>