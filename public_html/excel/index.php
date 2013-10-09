<?PHP

$i = $_GET['id'];

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

	$tilkobling =kobleTil("gruppe2it");
		
	$sql2 = "SELECT * FROM SimStoring WHERE id='$i'";
	$result2 = mysql_query($sql2) or die(mysql_error());
	$row = mysql_fetch_array ($result2);
	
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
	$secBoilerSize = $row['secBoilerSize'];
	$secBoilerPower = $row['secBoilerPower'];
	$numLight = $row['numLight'];
	$priLightType = $row['priLightType'];
	$secLightType = $row['secLightType'];
	$lightTime = $row['lightTime'];
	$lightDiff = $row['lightDiff'];
	$numHvit = $row['numHvit'];
	$numBrun = $row['numBrun'];
	$climateZone = $row['climateZone'];
	if($climateZone == '1'){ $climateZone = "Sør-Norge, kyst";}
	if($climateZone == '2'){ $climateZone = "Sør-Norge, innland";}
	if($climateZone == '3'){ $climateZone = "Sør-Norge, høyfjell";}
	if($climateZone == '4'){ $climateZone = "Midt-Norge, kyst";}
	if($climateZone == '5'){ $climateZone = "Midt-Norge, innland";}
	if($climateZone == '6'){ $climateZone = "Nord-Norge, kyst";}
	if($climateZone == '7'){ $climateZone = "Finnmark og innland Troms";}
	
	$building = $row['building'];
	$houseBuildYear = $row['houseBuildYear'];
	
	if($building == '1') {$building = "Enebolig";}
	if($building == '2') {$building = "Leilighet";}
	if($building == '3') {$building = "Rekkehus";}
			
	if($houseBuildYear == '1') {$houseBuildYear = "Før 1987";}
	if($houseBuildYear == '2') {$houseBuildYear = "Mellom 1987 og 1997";}
	if($houseBuildYear == '3') {$houseBuildYear = "Etter 1997";}
	

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
	array("Type" => "Sekundær Elektrokjel (liter)", "Verdi" => "$secBoilerSize"),
	array("Type" => "Sekundær Elektrokjel (watt)", "Verdi" => "$secBoilerPower"),
	array("Type" => "", "Verdi" => ""),
	array("Type" => "Antall Lyskilder", "Verdi" => "$numLight"),
	array("Type" => "Primær Belysning", "Verdi" => "$priLightType"),
	array("Type" => "Sekundær Belysning", "Verdi" => "$secLightType"),
	array("Type" => "Gjennomsnittlig Brennetid (timer per dag)", "Verdi" => "$lightTime"),
	array("Type" => "Lys Differanse", "Verdi" => "$lightDiff"."%"),
	array("Type" => "", "Verdi" => ""),
	array("Type" => "Antall Hvitevarer", "Verdi" => "$numHvit"),
	array("Type" => "Antall Brunevarer", "Verdi" => "$numBrun"),
	array("Type" => "", "Verdi" => ""),
	array("Type" => "Klimasone", "Verdi" => "$climateZone")
  );
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