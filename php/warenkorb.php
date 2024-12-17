<?php
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
	echo "<th><a href='?seite=warenkorb&warenkorb_leeren'>Warenkorb leeren</a></th>";	
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

	$textfeld = "<input type='text' 
					name='menge' style='padding: 5px; width:40px'
					value='".$produkt["auswahl_menge"]."' />";
	$formular = "<form method='post'>$textfeld
					<input type='hidden' name='nr' value='$nr' />
					<input type='submit' name='warenkorb_aendern' value='Ändern' /></form>";
	echo "<td>$formular $aktualisierungs_hinweis</td>";
	echo "<td align='right'>".($datensatz["preis"]*$produkt["auswahl_menge"])."</td>";					
	$endpreis += $datensatz["preis"]*$produkt["auswahl_menge"];		
	
	echo "<td><a href='?seite=warenkorb&aus_warenkorb_entfernen=$nr'>Entfernen</a></td>";
	
	echo "</tr>";	# ende zeile		
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
	echo "<th><a href='?seite=warenkorb&warenkorb_leeren'>Warenkorb leeren</a></th>";
	echo "</tr>";
echo "</table>";	

if(isset($_SESSION["warenkorb"]) && count($_SESSION["warenkorb"]) > 0)
{
	echo "<form action='?seite=bestellen' method='post'>";
	echo "<input type='submit' name='bestellen' 
				value='Kostenpflichtig Bestellen' />";
	echo "</form>";	
}














