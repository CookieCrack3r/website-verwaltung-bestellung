<?php
if(isset($_POST["produkt_speichern"]))
{
	#Auswertung
	#echo "<pre>";
	#print_r($_POST);
	#print_r($_FILES);
	#echo "</pre>";
	#die(); # sofort beenden
	
	$bezeichnung 	= $_POST["bezeichnung"];
	$beschreibung 	= $_POST["beschreibung"]; 
	$farbe 			= $_POST["farbe"];
	$preis 			= $_POST["preis"];	
	$groesse_s 		= $_POST["groesse_s"];
	$groesse_m 		= $_POST["groesse_m"];
	$groesse_l 		= $_POST["groesse_l"];
	$groesse_xl 	= $_POST["groesse_xl"];
	$groesse_xxl 	= $_POST["groesse_xxl"];	
	

	if($_FILES["bild"]["error"] == 0 && $_FILES["bild"]["size"] > 0)
	{
		$bild			= uniqid().".jpg";	# neuer Dateiname
		move_uploaded_file($_FILES["bild"]["tmp_name"], "uploads/".$bild); # upload
	}
	else
	{
		$bild = "";
	}
	
	
	# Datenbank
	# Produkt speichern
	mysqli_query($link, "insert into produkte 
						(bezeichnung, beschreibung, farbe, preis, bild)
						values
						('$bezeichnung', '$beschreibung', '$farbe', '$preis', '$bild')
						");	
	#print_r($link);
	$produkt_pk = $link->insert_id; # primärschlüssel

	# Lagerbestand speichern
	mysqli_query($link, "insert into lagerbestand 
						(produkt_fk, groesse, verfuegbare_menge)
						values
						('$produkt_pk', 'S', '$groesse_s'),
						('$produkt_pk', 'M', '$groesse_m'),
						('$produkt_pk', 'L', '$groesse_l'),
						('$produkt_pk', 'XL', '$groesse_xl'),
						('$produkt_pk', 'XXL', '$groesse_xxl')
						");
	echo "<br />";
	echo "Es wurde ein neues Produkt gespeichert unter: ". $produkt_pk;
	echo "<hr /><br />";
	echo "<a href='?seite=verwaltung&unterseite=produktverwaltung'>Zurück zur Verwaltung</a>";
	echo "<a href='?seite=verwaltung&unterseite=neues_produkt'>weiteres Produkt</a>";						
}
else
{
	# Formular
	echo "<h2>Neues Produkt</h2><br /><hr /><br />";
	include("produkt_formular.php");
}
?>