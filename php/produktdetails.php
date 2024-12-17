<?php
$sql_befehl = "select * from produkte
				where produkt_pk = ".$_GET["produkt"];
#echo $sql_befehl;				

$antwort = mysqli_query($link, $sql_befehl);	
$produktdaten = mysqli_fetch_array($antwort);

#print_r($produktdaten);

echo "<a href='?seite=produkte'>Zurück</a><br /><br />";

echo "<div class='flexibel'>";

echo "<div class='produktdetail'>";
echo "<img src='uploads/".$produktdaten["bild"]."' width='300' />";
echo "</div>";

echo "<div class='produktdetail'>";
echo "<h1>".$produktdaten["bezeichnung"]."</h1>";		
echo "<div>".$produktdaten["beschreibung"]."</div>";
echo "<h2>".$produktdaten["preis"]." EURO</h2>";

echo "<br /><hr /><br />";

echo "<form method='post'>";
echo "Größe auswählen: <select name='auswahl_lager'>";

$lager = mysqli_query($link, "select * from lagerbestand 
					where produkt_fk = ".$produktdaten["produkt_pk"]);
while($groessendaten = mysqli_fetch_array($lager))
{
	$groesse = $groessendaten["groesse"];
	$verfuegbar = $groessendaten["verfuegbare_menge"];
	$lager_pk = $groessendaten["lager_pk"];	
	
	echo "<option value='$lager_pk'>$groesse (Verfügbar: $verfuegbar)</option>";
}
echo "</select>";
echo "<br /><br />";
echo "Menge auswählen: <input type='text' name='auswahl_menge' value='1' />";
echo "<br /><br />";
echo "<input type='submit' name='in_den_warenkorb' value='in den Warenkorb' />";
echo "</form>";


echo "</div>";

echo "</div>";

