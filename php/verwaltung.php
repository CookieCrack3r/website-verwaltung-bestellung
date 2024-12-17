<h1>Adminseite für die Verwaltung von Daten</h1>
<?php
if(isset($_GET["unterseite"]))
{
	switch($_GET["unterseite"])
	{
		case "neues_produkt": 		include("neues_produkt.php"); 		break;
		case "produkt_bearbeiten": 	include("produkt_bearbeiten.php"); 	break;
		case "produkt_loeschen": 	include("produkt_loeschen.php"); 	break;
		
		case "produktverwaltung":	include("produktverwaltung.php");	break;
		case "bestellverwaltung":	include("bestellverwaltung.php");	break;
	}
}
else
{
	include("verwaltungsuebersicht.php");
}