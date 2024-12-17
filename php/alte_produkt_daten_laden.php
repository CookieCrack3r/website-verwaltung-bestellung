<?php
# Einen Datensatz lesen
$antwort = mysqli_query($link, "select * from produkte 
								where produkt_pk = ".$_GET["produkt"]);
# In Array umwandeln
$datensatz = mysqli_fetch_array($antwort);									
#print_r($datensatz);

$produkt_pk 	= $datensatz["produkt_pk"];
$bezeichnung 	= $datensatz["bezeichnung"];
$beschreibung 	= $datensatz["beschreibung"]; 
$farbe 			= $datensatz["farbe"];
$preis 			= $datensatz["preis"];
$bilddatei		= $datensatz["bild"];
$bild 			= "<img src='uploads/".$bilddatei."' height='200' /><br />";	
#echo $bild;


$groessen = array("s","m","l","xl","xxl");
foreach($groessen as $groesse)
{
	$antwort = mysqli_query($link, "select * from lagerbestand 
									where produkt_fk = $produkt_pk 
									and groesse = '$groesse' ");
	$datensatz = mysqli_fetch_array($antwort);

	$zeichenkette = "groesse_$groesse";
	# $$zeichenkette erzeugt folgende Variablen
	# $groesse_s =
	# $groesse_m =
	# $groesse_l =
	# $groesse_xl =
	# $groesse_xxl =
	$$zeichenkette = $datensatz["verfuegbare_menge"];	
}