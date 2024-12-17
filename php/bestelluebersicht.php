<h1>Bestellübersicht</h1>
<?php
echo "<div class='gruppe'>";
echo "<table>";
	echo "<tr>";	# neue zeile
	echo "<th>Bild</th>"; # neue spalte
	echo "<th>Bezeichnung</th>"; # neue spalte
	echo "<th>Farbe</th>"; # neue spalte
	echo "<th>Größe</th>";
	echo "<th>Einzelpreis</th>";
	echo "<th>Verfügbar</th>";
	echo "<th>Menge</th>";
	echo "<th>Gesamtpreis</th>";	
	echo "</tr>";
$endpreis = 0;	
foreach($_SESSION["warenkorb"] as $nr => $produkt)
{
	#echo "<pre>";
	#print_r($produkt);
	#echo "</pre>";	
	# Die fehlenden Daten aus der Datenbank holen
	$abfrage = mysqli_query($link, "select * from produkte JOIN lagerbestand
						ON produkte.produkt_pk = lagerbestand.produkt_fk
						WHERE lager_pk = ".$produkt["auswahl_lager"]);
	$datensatz = mysqli_fetch_array($abfrage);
	#echo "<pre>";
	#print_r($datensatz);
	#echo "</pre>";
	
	echo "<tr>";	# neue zeile
	echo "<td><img src='uploads/".$datensatz["bild"]."' height='100' /></td>";
	echo "<td>".$datensatz["bezeichnung"]."</td>"; # neue spalte
	echo "<td>".$datensatz["farbe"]."</td>";
	echo "<td>".$datensatz["groesse"]."</td>";
	echo "<td align='right'>".$datensatz["preis"]."</td>";
	echo "<td>".$datensatz["verfuegbare_menge"]."</td>";
	
	
	# Hinweis resetten
	$aktualisierungs_hinweis = "";
	# Wenn Menge > als Lieferbar
	if($produkt["auswahl_menge"] > $datensatz["verfuegbare_menge"])
	{
		# Hinweis erstellen
		$aktualisierungs_hinweis = "<div>ACHTUNG: DIE MENGE WURDE ANGEPASST</div>";
		# Anpassung auf die maximale Menge
		$produkt["auswahl_menge"] = $datensatz["verfuegbare_menge"];
		$_SESSION["warenkorb"][$nr]["auswahl_menge"] = $produkt["auswahl_menge"];
		if($produkt["auswahl_menge"] <= 0)
		{
			# Löschen vom Eintrag im Warenkorb
			unset($_SESSION["warenkorb"][$nr]);
		}		
	}

	echo "<td>".$produkt["auswahl_menge"]." $aktualisierungs_hinweis</td>";
	echo "<td align='right'>".($datensatz["preis"]*$produkt["auswahl_menge"])."</td>";
	echo "</tr>";
	
	# Den angezeigten Preis merken
	$_SESSION["warenkorb"][$nr]["auswahl_preis"] = $datensatz["preis"];
	
	$endpreis += $datensatz["preis"]*$produkt["auswahl_menge"];
}

	echo "<tr>";	# neue zeile
	echo "<td></td>";
	echo "<td></td>"; # neue spalte
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<th align='right'>".$endpreis."</th>";
	echo "</tr>";
echo "</table>";
echo "</div>";
?>	

<div class='gruppe'>
	Bezahlmethode:<br />
	Rechnung
</div>

<div class='gruppe'>
	Lieferanschrift:<br />
	<?php echo $_POST["lieferanschrift"]; ?>
</div>

<?php

if(isset($_SESSION["warenkorb"]) && count($_SESSION["warenkorb"]) > 0)
{
	echo "<form action='?seite=bestellbestaetigung' method='post'>";
	echo "<input type='hidden' name='lieferanschrift' 
			value='".$_POST["lieferanschrift"]."' />";
	echo "<input type='submit' name='kostenpflichtig_bestellen' 
				value='Kostenpflichtig Bestellen' />";
	echo "</form>";
}

?>
