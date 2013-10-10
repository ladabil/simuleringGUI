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
	

  	// Lager XML 
	 echo "<?xml version=\"1.0\"?>";
	 $xml = '<simulering>';
	 $xml .= "\n\t";	// For DOM - human readable \n <- line break, \t <- tab for each class
	 
	// Boligtyp>
	$xml .= "<Enebolig type=\"class\">\n\t\t\t";
		$xml .= "<bruttoAreal>".$houseTotalArea."</bruttoAreal> \n\t\t\t";
		$xml .= "<pRomAreal>".$housePrimaryArea."</pRomAreal> \n\t\t";
		$xml .= "<Varmetap type=\"class\"> \n\t\t\t";
			$xml .= "<bruttoAreal>".$houseTotalArea."</bruttoAreal> \n\t\t\t";
			$xml .= "<pRomAreal>".$housePrimaryArea."</pRomAreal> \n\t\t";
 			$xml .= "<byggstandard>".$houseBuildYear."</byggstandard>\n\t\t\t\t";			// Hardkodet ihht testData.xml TODO: Legg inn felter i bygning
 			$xml .= "<ytterveggAreal>".$ytterveggAreal."</ytterveggAreal>\n\t\t\t\t";
 			$xml .= "<yttertakAreal>".$yttertakAreal."</yttertakAreal>\n\t\t\t\t";
 			$xml .= "<vinduDorAreal>".$vinduDorAreal."</vinduDorAreal>\n\t\t\t\t";
 			$xml .= "<luftVolum>".$luftVolum."</luftVolum>\n\t\t\t\t";
 			$xml .= "<onsketTemp>".$onsketTemp."</onsketTemp>\n\t\t\t\t";
		$xml .= "</Varmetap> \n\t\t";
		$xml .= "<Soltilskudd type=\"class\">\n\t\t";
  			$xml .= "<vinduDorAreal>20</vinduDorAreal>\n\t\t\t\t";
		$xml .= "</Soltilskudd> \n\t\t";	
		$xml .= "<ForbrukVann type=\"class\"> \n\t\t\t";
			$xml .= "<priHeat>".$priHeat."</priHeat> \n\t\t\t";
			$xml .= "<secHeat>".$secHeat."</secHeat> \n\t\t\t";
			$xml .= "<heatDiff>".$heatDiff."</heatDiff> \n\t\t\t";
			$xml .= "<floorHeatWa>".$floorHeatWa."</floorHeatWa> \n\t\t\t";
			$xml .= "<floorHeatEl>".$floorHeatEl."</floorHeatEl> \n\t\t\t";
			$xml .= "<priBoilerSize>".$priBoilerSize."</priBoilerSize> \n\t\t\t";
			$xml .= "<priBoilerPower>".$priBoilerPower."</priBoilerPower> \n\t\t\t";
			$xml .= "<secBoilerSize>".$secBoilerSize."</secBoilerSize> \n\t\t\t";
			$xml .= "<secBoilerPower>".$secBoilerPower."</secBoilerPower> \n\t\t";
		$xml .= "</ForbrukVann>  \n\t\t";
		$xml .= "<Belysning type=\"class\"> \n\t\t\t";
// 			$xml .= "<antLys>".$numLight."</antLys> \n\t\t\t";
// 			$xml .= "<priLysType>".$priLightType."</priLysType> \n\t\t\t";
// 			$xml .= "<secLysType>".$secLightType."</secLysType> \n\t\t\t";
			$xml .= "<brenntid>".$lightTime."</brenntid> \n\t\t\t";
			$xml .= "<lysDiff>".$lightDiff."</lysDiff> \n\t\t";
		$xml .= "</Belysning>  \n\t";
		$xml .= "<ForbrukHvitevare type=\"class\"> \n\t\t\t";
// 			$xml .= "<hvite>".$hvite."</hvite> \n\t\t";
		$xml .= "</ForbrukHvitevare> \n\t";
		$xml .= "<ForbrukBrunevare type=\"class\"> \n\t\t\t";
// 			$xml .= "<brune>".$brun."</brune> \n\t\t\t";
		$xml .= "</ForbrukBrunevare>  \n\t";
	$xml .= "</Enebolig>\n\t";
	
	// Beboere
	$xml .= "<Familie type=\"class\">\n\t\t";
 		$xml .= "<personAlder>27</personAlder>\n\t\t\t";
		$xml .= "<Person class=\"class\"> \n\t\t";
 			$xml .= "<Alder>50</Alder> \n\t\t\t\t";			// Hardkodet ihht testData.xml <-- TODO: foreach løkke 
 			$xml .= "<Kjonn>Kvinne</Kjonn> \n\t\t\t\t";
 			$xml .= "<Alder>60</Alder> \n\t\t\t\t";	
 			$xml .= "<Kjonn>Mann</Kjonn> \n\t\t\t\t";
		$xml .= "</Person> \n\t";
	$xml .= "</Familie>\n\t";
	
	// Klima
	$xml .= "<Klima type=\"class\">\n\t\t";
		$xml .= "<maalestasjon>86600</maalestasjon>\n\t\t";
	 	$xml .= "<sone>".$climateZone."</sone>\n\t";
	$xml .= "</Klima>\n\t";
	
	// Tidsrom
	$xml .= "<Tidsrom type=\"class\">\n\t\t";
		$xml .= "<startDateTime>2012-05-19 10:00:00 CET</startDateTime>\n\t\t";
		$xml .= "<endDateTime>2012-06-23 10:00:00 CET</endDateTime>\n\t\t";
		$xml .= "<opplosning>3600</opplosning>\n\t";
	$xml .= "</Tidsrom>\n";
	
	
	$xml .= "</simulering>\n\r";
	$xmlobj = new SimpleXMLElement($xml);
// 	$xmlobj -> asXML ("testData.xml");
	
	// DOMDocument for output in human readable file
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = true;
	$dom->formatOutput = true;
	$dom->loadXML($xmlobj->asXML());
	echo $dom->saveXML();
	$filename = date("Ymd") . "_" . time() . ".xml";
	$dom->save("../../new" + $filename);
	
	