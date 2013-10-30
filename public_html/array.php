<?php


/*
 *  Proof of concept
 *  Mikael
 * 
 */

$families = array
  (
  	"Yrke"=>array
  	(
		"Snekker",
		"latsab",
		"prostituert"
  	),
  	
	"Alder"=>array
  	(
		"10",
		"22",
		"40"
  	)
  );


      

array_push($families["Yrke"], "barnehage");
array_push($families["Alder"], "50");


print_r($families);
echo "<br /><br />";


echo "Jobber som " . $families['Yrke'][0] . 
" og er " . $families['Alder'][0] . " &aring;r gammel? <br />";

echo "Jobber som " . $families['Yrke'][2] . 
" og er " . $families['Alder'][2] . " &aring;r gammel? <br />";

echo "Jobber som " . $families['Yrke'][1] . 
" og er " . $families['Alder'][1] . " &aring;r gammel? <br />";

echo "Jobber som " . $families['Yrke'][3] . 
" og er " . $families['Alder'][3] . " &aring;r gammel? <br />";

echo "<br /><br />";


foreach ($families['Yrke'] as $i => $value ) 
{
	echo "Jobber som " . $families['Yrke'][$i] . " og er " . $families['Alder'][$i] . " &aring;r gammel? <br />";
}

?>