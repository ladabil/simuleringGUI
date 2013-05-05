<html>
	<head>
		<link href="style.css" rel="stylesheet" type="text/css" media="screen" />
		<title>Sim</title>
		
	</head>
	<body>
		<div class="sim">
			<img class="logo" src="logo.png" />
			
			<!-- Formen -->
			<form method="post">
				<label>Antall Personer i Husstand</label><br />
				<input type="text" name="antall_i_hus"/><br />
			
				<label>Gjennomsnittsalder</label><br />
				<input type="text" name="gjen_alder"/><br />
			
				<label>Bygge&aring;r</label><br />
			
				<select name="byggaar">
					<option value="for87">F&oslash;r 1987</option>
					<option value="87-97">Mellom 1987 og 1997</option>
					<option value="etter97">Etter 1997</option>
				</select><br />
			
			    <label>Klima</label><br />
				<select name="klima">
					<option value="sn-kyst">S&oslash;r-Norge, kyst</option>
					<option value="sn-innland">S&oslash;r-Norge, innland</option>
					<option value="sn-hf">S&oslash;r-Norge, h&oslash;yfjell</option>
					<option value="mn-kyst">Midt-Norge, kyst</option>
					<option value="mn-innland">Midt-Norge, innland</option>
					<option value="nn-kyst">Nord-Norge, kyst</option>
					<option value="ft">Finnmark og innland Troms</option>
				</select><br /><br />
			
				<input type='submit' class="button" value='Beregn' />
			</form>
		
		<?
		
		if($_POST) {
			
			$antall_i_huset = $_POST['antall_i_hus'];
			$gjen_alder = $_POST['gjen_alder'];
			$byggeaar = $_POST['byggaar'];
			$klima = $_POST['klima'];
			
			
			// eks: 
			// antall i huset * 60watt gange 2 lyspærer per person * 12 timer i døgnet * dager i året
			// anna ikke kordan man regna ut dettan doh.........
			$resultat = $antall_i_huset*(60*2)*(12*365);
			
			echo "<h3>Resultat</h3>";
			echo $resultat." watt i &aring;ret.";
		}
		
		?>
		</div>
	<body>
</html>
