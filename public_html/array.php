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
  	
  	),
  	
	"Alder"=>array
  	(	
  	
  	)
  );


      
/*
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
*/
$mysqli = new mysqli("jenna.bendiksens.net", "gruppe2it", "123", "gruppe2it");


$sql = "SELECT * FROM FamilyStore WHERE StoredName = 'Hansen'";
		
		if ( ($res = $mysqli->query($sql)) === FALSE )
		{
			die("error");
		}
		// Her må det kodes en måte å hente ut fra DB og rett inn i session arrayet
		
		//$tmpRes = $res->fetch_Assoc();
		while($row = mysqli_fetch_array($res,MYSQLI_ASSOC))
		{
			array_push($families["Yrke"], $row['work']);
			array_push($families["Alder"], $row['age']);
			//array_push($_SESSION['es']->_inhabitantsWork, $row['work']);
			//array_push($_SESSION['es']->_inhabitantsAge, $row['age']);	
		}
print_r($families);



?>