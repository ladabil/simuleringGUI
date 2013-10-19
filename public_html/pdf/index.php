
<?php

require('fpdf.php');

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

$pdf = new FPDF('p','mm','A4');

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


// lager side	
$pdf->AddPage();

$pdf->Image('logo.png',170,5,-500);

// setter Tittel 
$pdf->SetFont('Arial','B',20);
$pdf->Cell(0,10, $name ,0,1,'l');
$pdf->Line(10, 20, 200, 20);

// undertittel for Hus
$pdf->ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6, 'Hus', 0,2,'l');

// verdier
$pdf->SetFont('Times','',12);
$pdf->ln(2);
$pdf->Cell(0,5, utf8_decode('Bygg Type: '.$building) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Byggeår: '.$houseBuildYear) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Brutto Areal: '.$houseTotalArea) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('P-Rom: '.$housePrimaryArea) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Byggeår: '.$houseBuildYear) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Veggareal ytre: '.$ytterveggAreal) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Takareal: '.$yttertakAreal) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Vindu og Dør areal: '.$vinduDorAreal) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Indre luftvolum: '.$luftVolum) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Ønsket innetemp: '.$onsketTemp) ,0,2,'l');

// undertittel for Oppvarming
$pdf->ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6, 'Oppvarming', 0,2,'l');

// verdier
$pdf->SetFont('Times','',12);
$pdf->ln(2);
$pdf->Cell(0,5, utf8_decode('Primær Oppvarming: '.$priHeat) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Sekundær Oppvarming: '.$secHeat) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Varme Differanse: '.$heatDiff.'%') ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Gulvvarme Vannbåren: '.$floorHeatWa) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Gulvvarme Elektrisk: '.$floorHeatEl) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Primær Elektrokjel (liter): '.$priBoilerSize) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Primær Elektrokjel (watt): '.$priBoilerPower) ,0,2,'l');


// undertittel for Lys
$pdf->ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6, 'Lys', 0,2,'l');

// verdier
$pdf->SetFont('Times','',12);
$pdf->ln(2);
$pdf->Cell(0,5, utf8_decode('Antall Lyskilder: '.$numLight) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Primær Belysning: '.$priLightType) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Sekundær Belysning: '.$secLightType) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Gjennomsnittlig Brennetid (timer per dag): '.$lightTime) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Lys Differanse: '.$lightDiff.'%') ,0,2,'l');


// undertittel for Beboere
$pdf->ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6, 'Beboere', 0,2,'l');

// verdier
$pdf->SetFont('Times','',12);
$pdf->ln(2);
$pdf->Cell(0,5, utf8_decode('Antall Hvitevarer: '.$numHvit) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Antall Brunevarer: '.$numBrun) ,0,2,'l');


// undertittel for Klimasone
$pdf->ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6, 'Klimasone', 0,2,'l');

// verdier
$pdf->SetFont('Times','',12);
$pdf->ln(2);
$pdf->Cell(0,5, utf8_decode('Klimasone: '.$climateZone) ,0,2,'l');


// genererer PDF
$pdf->Output();

?>


