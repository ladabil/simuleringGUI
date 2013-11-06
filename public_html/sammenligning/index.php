<form name=	"testform" id="testform" action="?x=submit" method="post">
  <select name="dropdown" id="dropdown">
  	<option value="nothing" selected="selected">Velg Simuleringsresultat</option>
  	<?php
  	$mysqli = new mysqli("jenna.bendiksens.net", "gruppe2it", "123", "gruppe2it");


	$sql = "SELECT * FROM SimTask";
		
		if ( ($res = $mysqli->query($sql)) === FALSE )
		{
			die("error");
		}

		while($row = mysqli_fetch_array($res))
		{
			echo "<option value='http://jenna.bendiksens.net/~gruppe2/resultater/".$row['xmlId'].".xml.csv'>".$row['xmlId']."</option>";
		}
  	
  	?>
  </select>
  <select name="dropdown2" id="dropdown">
  	<option value="nothing" selected="selected">Velg Simuleringsresultat</option>
  	<?php
  	$mysqli = new mysqli("jenna.bendiksens.net", "gruppe2it", "123", "gruppe2it");


	$sql = "SELECT * FROM SimTask";
		
		if ( ($res = $mysqli->query($sql)) === FALSE )
		{
			die("error");
		}

		while($row = mysqli_fetch_array($res))
		{
			echo "<option value='http://jenna.bendiksens.net/~gruppe2/resultater/".$row['xmlId'].".xml.csv'>".$row['xmlId']."</option>";
		}
  	
  	?>
  </select>
  <input type="submit" name="Submit" value="Submit" id="Submit">
</form>

<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
if($_GET['x'] == 'submit')
{

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
	if($_POST['dropdown'] != "nothing" && $_POST['dropdown2'] != "nothing" )
	{
		$CSV  = parse_csv_file($_POST['dropdown']);

		$antall = count($CSV);
		
		echo "
		<div style='float: left; margin-right: 5px;'>
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
		
		$CSV2  = parse_csv_file($_POST['dropdown2']);

		$antall2 = count($CSV2);
		
		echo "
		<div>
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
}
?>
