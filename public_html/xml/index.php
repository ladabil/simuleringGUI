<?PHP

require_once(dirname(__FILE__) . "/std.inc.php");
require_once(dirname(__FILE__) . "/datasetup.inc.php");

// kjører database kolonne-resultat til variabler
$name = $row['name'];
$priHeat = $row['priHeat'];
$secHeat = $row['secHeat'];
$houseTotalArea = $row['houseTotalArea'];
$housePrimaryArea = $row['housePrimaryArea'];
$houseBuildYear = $row['houseBuildYear'];
if($houseBuildYear == '1'){ $houseBuildYear = '1985';}
if($houseBuildYear == '2'){ $houseBuildYear = '1995';}
if($houseBuildYear == '3'){ $houseBuildYear = '2005';}
	
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
	

  	// Lager XML 
	 echo "<?xml version=\"1.0\"?>";
	 $xml = '<simulering>';
	 $xml .= "\n\t";	// For DOM - human readable \n <- line break, \t <- tab for each class
	 
	 // Beboere
	 $xml .= "<Familie type=\"class\">\n\t\t";
	 	$xml .= hentNokkelVerdiForXML($tilkobling, "Familie");
	 	$xml .= "<Person type=\"class\"> \n\t\t\t";
	 		$xml .= hentNokkelVerdiForXML($tilkobling, "Person");
	 		$xml .= "<Alder>50</Alder> \n\t\t\t";	
	 		$xml .= "<Kjonn>Kvinne</Kjonn> \n\t\t";
	 	$xml .= "<Person type=\"class\"> \n\t\t";
	 	$xml .= "</Person> \n\t\t\t";
			$xml .= "<Alder>60</Alder> \n\t\t\t";
			$xml .= "<Kjonn>Mann</Kjonn> \n\t\t";
	 	$xml .= "</Person> \n\t";
	 $xml .= "</Familie>\n\t";
	 
	// Boligtyp>
	$xml .= "<Enebolig type=\"class\">\n\t\t\t";
	 	$xml .= hentNokkelVerdiForXML($tilkobling, "Enebolig");
		$xml .= "<bruttoAreal>".$houseTotalArea."</bruttoAreal> \n\t\t\t";
		$xml .= "<pRomAreal>".$housePrimaryArea."</pRomAreal> \n\t\t";
		$xml .= "<Varmetap type=\"class\"> \n\t\t\t";
	 		$xml .= hentNokkelVerdiForXML($tilkobling, "Varmetap");
			$xml .= "<byggstandard>".$houseBuildYear."</byggstandard>\n\t\t\t";			// Hardkodet ihht testData.xml TODO: Legg inn felter i bygning
 			$xml .= "<ytterveggAreal>".$ytterveggAreal."</ytterveggAreal>\n\t\t\t";
 			$xml .= "<yttertakAreal>".$yttertakAreal."</yttertakAreal>\n\t\t\t";
 			$xml .= "<vinduDorAreal>".$vinduDorAreal."</vinduDorAreal>\n\t\t\t";
 			$xml .= "<luftVolum>".$luftVolum."</luftVolum>\n\t\t\t";
 			$xml .= "<onsketTemp>".$onsketTemp."</onsketTemp>\n\t\t";
		$xml .= "</Varmetap> \n\t\t";
		$xml .= "<Soltilskudd type=\"class\">\n\t\t";
		$xml .= hentNokkelVerdiForXML($tilkobling, "Soltilskudd");
		
		$xml .= "</Soltilskudd> \n\t\t";	
		$xml .= "<ForbrukVann type=\"class\"> \n\t\t\t";
			$xml .= hentNokkelVerdiForXML($tilkobling, "ForbrukVann");
			$xml .= "<priHeat>".$priHeat."</priHeat> \n\t\t\t";
			$xml .= "<secHeat>".$secHeat."</secHeat> \n\t\t\t";
			$xml .= "<heatDiff>".$heatDiff."</heatDiff> \n\t\t\t";
			$xml .= "<floorHeatWa>".$floorHeatWa."</floorHeatWa> \n\t\t\t";
			$xml .= "<floorHeatEl>".$floorHeatEl."</floorHeatEl> \n\t\t\t";
			$xml .= "<priBoilerSize>".$priBoilerSize."</priBoilerSize> \n\t\t\t";
			$xml .= "<priBoilerPower>".$priBoilerPower."</priBoilerPower> \n\t\t\t";
		$xml .= "</ForbrukVann>  \n\t\t";
		$xml .= "<Belysning type=\"class\"> \n\t\t\t";
			$xml .= hentNokkelVerdiForXML($tilkobling, "Belysning");
		// 			$xml .= "<antLys>".$numLight."</antLys> \n\t\t\t";
// 			$xml .= "<priLysType>".$priLightType."</priLysType> \n\t\t\t";
// 			$xml .= "<secLysType>".$secLightType."</secLysType> \n\t\t\t";
			$xml .= "<brenntid>".$lightTime."</brenntid> \n\t\t\t";
			$xml .= "<lysDiff>".$lightDiff."</lysDiff> \n\t\t";
		$xml .= "</Belysning>  \n\t\t";
		$xml .= "<ForbrukHvitevare type=\"class\"> \n\t\t";
			$xml .= hentNokkelVerdiForXML($tilkobling, "ForbrukHvitevare");
		// 			$xml .= "<hvite>".$hvite."</hvite> \n\t\t";
		$xml .= "</ForbrukHvitevare> \n\t\t";
		$xml .= "<ForbrukBrunevare type=\"class\"> \n\t\t";
			$xml .= hentNokkelVerdiForXML($tilkobling, "ForbrukBrunevare");
		// 			$xml .= "<brune>".$brun."</brune> \n\t\t\t";
		$xml .= "</ForbrukBrunevare>  \n\t";
	$xml .= "</Enebolig>\n\t";
	
	// Klima
	$xml .= "<Klima type=\"class\">\n\t\t";
		$xml .= hentNokkelVerdiForXML($tilkobling, "Klima");
		if ( floatval($climateTemperatureOffset) <> 0.0 )
		{
			$xml .= "<temperatureoffset>" . $climateTemperatureOffset . "</temperatureoffset>\n\t\t";
		}
		
		if ( intval($climateWeatherStation) > 0 )
		{
			$xml .= "<maalestasjon>" . intval($climateWeatherStation) . "</maalestasjon>\n\t\t";
		}
		else
		{
			if ( intval($climateZone) <= 0 || intval($climateZone) > 7 )
			{
				// Default klimasone er 1 -> Sør-norge
				$climateZone = 1;
			}
			
		 	$xml .= "<sone>".intval($climateZone)."</sone>\n\t";
		}
	$xml .= "</Klima>\n\t";
	
	// Tidsrom
	$xml .= "<Tidsrom type=\"class\">\n\t\t";
		$xml .= hentNokkelVerdiForXML($tilkobling, "Tidsrom");
		$xml .= "<startDateTime>".$startTime." CET</startDateTime>\n\t\t";
		$xml .= "<endDateTime>".$endTime." CET</endDateTime>\n\t\t";
		$xml .= "<opplosning>".$opplosning."</opplosning>\n\t";
	$xml .= "</Tidsrom>\n";
	
	
	$xml .= "</simulering>\n\r";
	$xmlobj = new SimpleXMLElement($xml);
// 	$xmlobj -> asXML ("testData.xml");
	
	// DOMDocument for output in human readable file
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = true;
	$dom->formatOutput = true;
	$dom->loadXML($xmlobj->asXML());
	//echo $dom->saveXML(); // For test -> viser xml datene
	$filename = "/home/gruppe2/new/" . date("Ymd") . "_" . time() . ".xml";
	$dom->save($filename);

	$datetime1 = new DateTime($startTime);
	$datetime2 = new DateTime($endTime);
	$interval = $datetime1->diff($datetime2);
	$tidsforbruk = ( ( ($datetime2->getTimestamp()/1973963) - ($datetime1->getTimestamp()/1973963) )  ) * 2.65 ; //973963
	
	echo "Sender simuleringen for {$name} til kalkulering <br><br>
	Simuleringsperiode fra {$startTime} til {$endTime} <br><br>
	Simuleringstiden er {$interval->format('%R%a dager')} <br><br>
	Estimert tidsfobruk er {$tidsforbruk} sekunder <br>
	<i>med forbehold om kø i beregning og kommunikasjonsfeil</i><br><br>"; 
	
	?>
<a href="javascript:window.close();">Tilbake</a>
	