<?php
/*
function jj_readcsv($filename, $header=false) {
$handle = fopen($filename, "r");
echo '<table>';
//display header row if true
if ($header) {
    $csvcontents = fgetcsv($handle);
    echo '<tr>';
    foreach ($csvcontents as $headercolumn) {
        echo "<th>$headercolumn</th>";
    }
    echo '</tr>';
}
// displaying contents
while ($csvcontents = fgetcsv($handle)) {
    echo '<tr>';
    foreach ($csvcontents as $column) {
        echo "<td>$column</td>";
    }
    echo '</tr>';
}
echo '</table>';
fclose($handle);
}

jj_readcsv('http://jenna.bendiksens.net/~gruppe2/resultater/20131104_1383603361.xml.csv',true);




	$row = 1; 
	if (($handle = fopen("http://jenna.bendiksens.net/~gruppe2/resultater/20131104_1383603361.xml.csv", "r")) !== FALSE) 
		{
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) 
			{
				 $num = count($data); 
				 echo "<br />","Record #",$row,"<br />"; 
				 $row++; 
				 for ($recordcount=0; $recordcount < $num; $recordcount++) 
				 {
				 	 echo $data[$recordcount] . "<br />\n"; 
				 } 
			} 
			fclose($handle); 
		} 




// returns a two-dimensional array or rows and fields
$file = fopen('http://jenna.bendiksens.net/~gruppe2/resultater/20131104_1383603361.xml.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) {
  //$line is an array of the csv elements
  print_r($line);
}
fclose($file);


function csvstring_to_array($string, $separatorChar = ',', $enclosureChar = '"', $newlineChar = "\n") {
    // @author: Klemen Nagode
    $array = array();
    $size = strlen($string);
    $columnIndex = 0;
    $rowIndex = 0;
    $fieldValue="";
    $isEnclosured = false;
    for($i=0; $i<$size;$i++) {

        $char = $string{$i};
        $addChar = "";

        if($isEnclosured) {
            if($char==$enclosureChar) {

                if($i+1<$size && $string{$i+1}==$enclosureChar){
                    // escaped char
                    $addChar=$char;
                    $i++; // dont check next char
                }else{
                    $isEnclosured = false;
                }
            }else {
                $addChar=$char;
            }
        }else {
            if($char==$enclosureChar) {
                $isEnclosured = true;
            }else {

                if($char==$separatorChar) {

                    $array[$rowIndex][$columnIndex] = $fieldValue;
                    $fieldValue="";

                    $columnIndex++;
                }elseif($char==$newlineChar) {
                    echo $char;
                    $array[$rowIndex][$columnIndex] = $fieldValue;
                    $fieldValue="";
                    $columnIndex=0;
                    $rowIndex++;
                }else {
                    $addChar=$char;
                }
            }
        }
        if($addChar!=""){
            $fieldValue.=$addChar;

        }
    }

    if($fieldValue) { // save last field
        $array[$rowIndex][$columnIndex] = $fieldValue;
    }
    return $array;
}

$csv = csvstring_to_array("1,2,3,4");
print_r($csv);

$csv = array();
$lines = file('http://jenna.bendiksens.net/~gruppe2/resultater/20131104_1383603361.xml.csv', FILE_IGNORE_NEW_LINES);

foreach ($lines as $key => $value)
{
    $csv[$key] = str_getcsv($value);
}

echo '<pre>';
print_r($csv);
echo '</pre>';
*/

function parse_csv_file($csvfile) {
    $csv = Array();
    $rowcount = 0;
    if (($handle = fopen($csvfile, "r")) !== FALSE) {
        $max_line_length = defined('MAX_LINE_LENGTH') ? MAX_LINE_LENGTH : 10000;
        $header = fgetcsv($handle, $max_line_length, ";");
        $header_colcount = count($header);
        while (($row = fgetcsv($handle, $max_line_length, ";")) !== FALSE) {
            $row_colcount = count($row);
            if ($row_colcount == $header_colcount) {
                $entry = array_combine($header, $row);
                $csv[] = $entry;
            }
            else {
                error_log("csvreader: Invalid number of columns at line " . ($rowcount + 2) . " (row " . ($rowcount + 1) . "). Expected=$header_colcount Got=$row_colcount");
                return null;
            }
            $rowcount++;
        }
        //echo "Totally $rowcount rows found\n";
        fclose($handle);
    }
    else {
        error_log("csvreader: Could not read CSV \"$csvfile\"");
        return null;
    }
    return $csv;
}


/*
$CSV = array();
$file = fopen('http://jenna.bendiksens.net/~gruppe2/resultater/20131104_1383603361.xml.csv', 'r');
while (($line = fgetcsv($file)) !== FALSE) {
  //$line is an array of the csv elements
  //startTimeAsLong;startTime;opplos;sim;forbruk;varmetap
  array_push($CSV, csvstring_to_array($line));
  
  
}

fclose($file);
 */
$CSV  = parse_csv_file("http://jenna.bendiksens.net/~gruppe2/resultater/20131104_1383603361.xml.csv");
error_reporting(E_ERROR | E_WARNING | E_PARSE);
echo "<br>--------------------------<br>";
echo "<br>RAW DATA FROM ARRAY<br>";
echo "<br>--------------------------<br>";


$antall = count($CSV);

echo "
<table border='1'>
<tr>
<td><strong>startTime</strong></td>
<td><strong>forbruk</strong></td>
</tr>

";

for($i=0; $i<$antall;$i++) {
	echo "<tr>";
	echo "<td>".$CSV[$i][startTime]."</td>";
	echo "<td>".$CSV[$i][forbruk]."</td>";
	echo "</tr>";
}
?>


</table>