<?php
#echo "<pre>";
#print_r($_POST);
#echo "<hr />";
#print_r($_SESSION["warenkorb"]);
#echo "</pre>";
if(count($_SESSION["warenkorb"]) > 0)
{
	# 1. Bestellung speichern
	mysqli_query($link, "insert into bestellungen (lieferanschrift)
						values('".$_POST["lieferanschrift"]."')");
	$bestell_pk = $link->insert_id; # Der neue Primärschlüssel	

	# 2. Warenkorb speichern
	foreach($_SESSION["warenkorb"] as $eintrag)
	{
		$lager_fk = $eintrag["auswahl_lager"];
		$menge = $eintrag["auswahl_menge"];
		$preis = $eintrag["auswahl_preis"]; # Preis aus Bestellübersicht
		mysqli_query($link, "insert into warenkorb (lager_fk, bestell_fk, menge, preis)
								values ($lager_fk, $bestell_pk, $menge, $preis)");	
		# 3. Lagerbestand aktualisieren
		mysqli_query($link, "update lagerbestand 
							set verfuegbare_menge = verfuegbare_menge - $menge
							where lager_pk = $lager_fk");								
	}
	# 4. Warenkorb leeren
	$_SESSION["warenkorb"] = array();

	# Bestätigung
	$_SESSION["mitteilung"] = "<h1>Ihre BestellNr lautet: $bestell_pk</h1>";	
	# automatische Weiterleitung
	header("Location: ?seite=bestellbestaetigung");
	exit;	
}
else
{
	echo "<h1>Vielen Dank für Ihre Bestellung.<br />
	Ihre Bestellung wird demnächst versendet!</h1>";
}