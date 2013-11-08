
<?php

require('fpdf.php');
require_once(dirname(__FILE__) . "/../xml/std.inc.php");
require_once(dirname(__FILE__) . "/../xml/datasetup.inc.php");

// kj�rer database kolonne-resultat til variabler
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
$opplosning = $row['climateZone'];

$building = $row['building'];
$houseBuildYear = $row['houseBuildYear'];

if($building == '1') {$building = "Enebolig";}
if($building == '2') {$building = "Leilighet";}
if($building == '3') {$building = "Rekkehus";}
		
if($houseBuildYear == '1') {$houseBuildYear = "Før 1987";}
if($houseBuildYear == '2') {$houseBuildYear = "Mellom 1987 og 1997";}
if($houseBuildYear == '3') {$houseBuildYear = "Etter 1997";}

$pdf = new FPDF('p','mm','A4');

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

if ( isset($inhabitantsArr) && count($inhabitantsArr) > 0 )
{
	foreach ( $inhabitantsArr as $inhabitant )
	{
		$pdf->Cell(0,5, 'Person: '. $inhabitant->age . ' �r, ' . $inhabitant->sexAsText . ', Yrke: ' . $inhabitant->work . '' ,0,2,'l');
	}
}
else
{
	$pdf->Cell(0,5, utf8_decode("Ingen beboere funne") ,0,2,'l');
	
}

//$pdf->Cell(0,5, utf8_decode('Antall Hvitevarer: '.$numHvit) ,0,2,'l');
//$pdf->Cell(0,5, utf8_decode('Antall Brunevarer: '.$numBrun) ,0,2,'l');


// undertittel for Klimasone
$pdf->ln(5);
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,6, 'Klima og tidsrom', 0,2,'l');

// verdier
$pdf->SetFont('Times','',12);
$pdf->ln(2);
$pdf->Cell(0,5, utf8_decode('Klimasone: ' . $climateZoneTxt . " (". $climateZone . ")") ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Værstasjon: ' . $climateWeatherStationTxt . " (stnr: " . $climateWeatherStation . ")" ) ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('TemperaturOffset: '.$climateTemperatureOffset . ' grader') ,0,2,'l');

$pdf->Cell(0,5, utf8_decode('Start tid: '.$startTime. ' CET') ,0,2,'l');
$pdf->Cell(0,5, utf8_decode('Slutt tid: '.$endTime. ' CET') ,0,2,'l');

// genererer PDF
$pdf->Output();

?>


