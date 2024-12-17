<?php
if(isset($_POST["produkt_speichern"]))
{
	#echo "<pre>";
	#print_r($_POST);
	#print_r($_FILES);
	#echo "</pre>";	
	include("alte_produkt_daten_laden.php");
	
	if(isset($_FILES["bild"]))
	{
		if($_FILES["bild"]["name"] != "")
		{
			# altes Bild entfernen	
			echo $bilddatei; # altes bild
			unlink("uploads/$bilddatei"); # Bilddatei löschen	
			$bild			= uniqid().".jpg";	# neuer Dateiname
			move_uploaded_file($_FILES["bild"]["tmp_name"], "uploads/".$bild); # upload	
			mysqli_query($link, "update produkte set bild = '$bild'
								 where produkt_pk = $produkt_pk ");			
		}
	}
	# Neue Daten
	$bezeichnung 	= $_POST["bezeichnung"];
	$beschreibung 	= $_POST["beschreibung"]; 
	$farbe 			= $_POST["farbe"];
	$preis 			= $_POST["preis"];	
	mysqli_query($link, "update produkte set 
							bezeichnung = '$bezeichnung',
							beschreibung = '$beschreibung',
							farbe = '$farbe',
							preis = '$preis'
						 where produkt_pk = $produkt_pk ");	
	$groessen = array("s","m","l","xl","xxl");	
	foreach($groessen as $groesse)
	{	
		$verfuegbare_menge = $_POST["groesse_$groesse"];
		mysqli_query($link, "update lagerbestand set 
							verfuegbare_menge = '$verfuegbare_menge'
						 where produkt_fk = $produkt_pk 
						 and groesse = '$groesse' ");
	}

}
##########################################################################
echo "<h2>Produkt Bearbeiten</h2><br /><hr /><br />";
include("alte_produkt_daten_laden.php");
include("produkt_formular.php");
