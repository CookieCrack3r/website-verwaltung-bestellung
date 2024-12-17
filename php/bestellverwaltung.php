<h1>Aktuelle Bestellungen</h1>
<br />
<?php
if(isset($_GET["bestellung"]))
{

	# Hier wird der Status geändert
	if(isset($_POST["status_button"])) # erledigt oder offen
	{
		switch($_POST["status_button"])
		{
			case "erledigt":
				$statusnr = 1;
			break;
			case "offen":
				$statusnr = 0;
			break;
		}
		mysqli_query($link, "update bestellungen set status_erledigt = $statusnr
										where bestell_pk = ".$_GET["bestellung"]);
	}
	
	# Detailseite für die Bearbeitung
	# Die Eine Bestellung lesen
	$antwort = mysqli_query($link, "select * from bestellungen 
									where bestell_pk = ".$_GET["bestellung"]);	
	$datensatz = mysqli_fetch_array($antwort);
	
	if($datensatz["status_erledigt"] === "1")
	{
		$style_erledigt = "style='background-color: green;'";
	}
	else
	{
		$style_offen = "style='background-color: red;'";
	}	
	echo "<div class='gruppe'>
			<div class='flexibel'>
			<a href='?seite=verwaltung&unterseite=bestellverwaltung'>Zurück</a>
			<div style='width:300px;'></div>
			<form method='post'>
			<input type='submit' class='status_erledigt' 
			name='status_button' ".@$style_erledigt." value='erledigt' />
			<input type='submit' class='status_offen' 
			name='status_button' ".@$style_offen." value='offen' />
			</form>
			</div>
		</div>";	
	
	echo "<div class='gruppe'>";
	echo "<pre>".$datensatz["lieferanschrift"]."</pre>";
	echo "</div>";	
	

	# Alle Daten aus dem Warenkorb für die entsprechende Bestellung
	$antwort = mysqli_query($link, "select * from warenkorb 
									JOIN lagerbestand
									ON warenkorb.lager_fk = lagerbestand.lager_pk
									JOIN produkte
									ON lagerbestand.produkt_fk = produkte.produkt_pk
									where bestell_fk = ".$_GET["bestellung"]);	
	
	echo "<div class='gruppe'>";

	echo "<table>";
		echo "<tr>";	# neue zeile
		echo "<th>Bild</th>"; # neue spalte
		echo "<th>Bezeichnung</th>"; # neue spalte
		echo "<th>Farbe</th>"; # neue spalte
		echo "<th>Größe</th>";
		echo "<th>Einzelpreis</th>";
		echo "<th>Menge</th>";
		echo "<th>Gesamtpreis</th>";	
		echo "</tr>";	

		$endpreis = 0;		
	
	
		while($datensatz = mysqli_fetch_array($antwort))
		{
			echo "<tr>";	# neue zeile
			echo "<td><img src='uploads/".$datensatz["bild"]."' height='100' /></td>";
			echo "<td>".$datensatz["bezeichnung"]."</td>"; # neue spalte
			echo "<td>".$datensatz["farbe"]."</td>";
			echo "<td>".$datensatz["groesse"]."</td>";
			echo "<td align='right'>".$datensatz["preis"]."</td>";
			echo "<td>".$datensatz["menge"]."</td>";
			echo "<td align='right'>".($datensatz["preis"]*$datensatz["menge"])."</td>";
			echo "</tr>";
			$endpreis += $datensatz["preis"]*$datensatz["menge"];
		}

		echo "<tr>";	# neue zeile
		echo "<td></td>";
		echo "<td></td>"; # neue spalte
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<th align='right'>".$endpreis."</th>";
		echo "</tr>";
	echo "</table>";	
	
	echo "</div>";		
}
else
{

	echo "<div class='gruppe'>
	<a href='?seite=verwaltung'>Zurück</a>
	</div>";
	
	# Übersichtseite
	$antwort = mysqli_query($link, "select * from bestellungen 
									order by datum_uhrzeit desc");	
									
								
	echo "<div class='gruppe'>";
	echo "<div class='flexibel'>";	
	while($datensatz = mysqli_fetch_array($antwort))
	{
		#echo "<pre>";
		#var_dump($datensatz);
		#echo "</pre>";
		if($datensatz["status_erledigt"] === "1")
		{
			$klasse = "status_erledigt";
		}
		else
		{
			$klasse = "status_offen";
		}		
		echo "<div class='bestellung $klasse'>";
		echo $datensatz["bestell_pk"];
		echo " <a href='?seite=verwaltung&unterseite=bestellverwaltung&bestellung=".
		$datensatz["bestell_pk"]."'>Bearbeiten</a>";
		echo "<br /><br /><hr />";
		echo "<pre>".$datensatz["lieferanschrift"]."</pre>";
		echo "<hr />";
		echo $datensatz["datum_uhrzeit"];		
		echo "</div>";
	}
	echo "</div>";
	echo "</div>";	
} # else