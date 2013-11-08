<link href="http://jenna.bendiksens.net/~gruppe2/css/style2.css" rel="stylesheet" type="text/css" media="screen" />
<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
$array = $_POST['sammenlign'];
$array2 = $_POST['navn'];

if (count($array) < 2)
{
	echo "<font color='red'>Ikke nokk antall for å sammenligne.</font>";
	die;
}

if (count($array) > 2)
{
	echo "<font color='red'>Du har valgt mere enn 2 sammenligninger.. maks antall er 2.</font>";
	die;
}

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
	if($array[0] != "" && $array[1] != "" )
	{
		$CSV  = parse_csv_file("http://jenna.bendiksens.net/~gruppe2/resultater/" . $array[0] . ".csv");

		$antall = count($CSV);
		
		echo "
		<div style='float: left; margin-right: 5px;'>
		<center>
		<font style='background-color: #3f3e50; color: #eb8602;'>
		" . $array2[0] . "</font></center><br />
			<table border='1'>
			<tr>
			<td><strong>startTime</strong></td>
			<td><strong>forbruk</strong></td>
			</tr>
		
		";
		
		for($i=0; $i<$antall;$i++) 
		{
			echo "<tr>";
			echo "<td>".$CSV[$i][startTime]."</td>";
			echo "<td>".$CSV[$i][forbruk]."</td>";
			echo "</tr>";
		}
		echo "</table>
		</div>";
		
		$CSV2  = parse_csv_file("http://jenna.bendiksens.net/~gruppe2/resultater/" . $array[1]  . ".csv");

		$antall2 = count($CSV2);
		
		echo "
		<div>
		<center>
		<font style='background-color: #3f3e50; color: #eb8602;'>
			" . $array2[1] . "</font></center><br />
			<table border='1'>
			<tr>
			<td><strong>startTime</strong></td>
			<td><strong>forbruk</strong></td>
			</tr>
		
		";
		
		for($i=0; $i<$antall2;$i++) 
		{
			echo "<tr>";
			echo "<td>".$CSV2[$i][startTime]."</td>";
			echo "<td>".$CSV2[$i][forbruk]."</td>";
			echo "</tr>";
		}
		echo "</table>
		</div>";
	}

?>
