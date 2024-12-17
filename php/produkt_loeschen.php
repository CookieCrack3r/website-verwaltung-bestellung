<?php
if(isset($_POST["produkt_loeschen_ja"]))
{
	include("alte_produkt_daten_laden.php");
	# Schritt 1 Datei löschen
	unlink("uploads/$bilddatei");
	# Schritt 2 Lagerbestand löschen
	mysqli_query($link, "delete from lagerbestand where produkt_fk = $produkt_pk");
	# Schritt 3 Produkt löschen
	mysqli_query($link, "delete from produkte where produkt_pk = $produkt_pk");
	
	# Umleitung
	header("Location: ?seite=verwaltung&unterseite=produktverwaltung");
	exit;	
}
else if(isset($_POST["produkt_loeschen_nein"]))
{
	# Umleitung
	header("Location: ?seite=verwaltung&unterseite=produktverwaltung");
	exit;	
}
else
{
	echo "<h2>Produkt Löschen</h2><br /><hr /><br />";
	
	include("alte_produkt_daten_laden.php");
	include("produkt_loeschbestaetigung.php");
}