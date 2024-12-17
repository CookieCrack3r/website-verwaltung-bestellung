<?php
session_start();

# Wenn es noch keine Session mit Warenkorb gibt
if(!isset($_SESSION["warenkorb"]) || isset($_GET["warenkorb_leeren"]))
{
	$_SESSION["warenkorb"] = array(); # Neuer leerer Warenkorb
}

# Wenn der Link zum Entfernen angeklickt wird
if(isset($_GET["aus_warenkorb_entfernen"]))
{
	# Löschen vom Eintrag im Warenkorb
	unset($_SESSION["warenkorb"][    $_GET["aus_warenkorb_entfernen"]     ]);
}

# Wenn die Menge geändert werden soll
if(isset($_POST["warenkorb_aendern"]))
{
	if($_POST["menge"] <= 0)
	{
		# Löschen vom Eintrag im Warenkorb
		unset($_SESSION["warenkorb"][    $_POST["nr"]     ]);
	}	
	else
	{
		#var_dump($_POST["menge"]);
		$zahl = (int) $_POST["menge"]; # Datentyp in einen Int Wert umwandeln
		if($zahl > 0)
		{
			# Den Mengenwert überschreiben
			$_SESSION["warenkorb"][$_POST["nr"]]["auswahl_menge"] = $zahl;		
		}
	}		
}


# Wenn das Formular in den Warenkorb abgeschickt wurde
if(isset($_POST["in_den_warenkorb"]))
{
	# prüfen ob es schon drin steht
	$gefundene_position = -1; # Schalter
	foreach($_SESSION["warenkorb"] as $nr => $eintrag)
	{
		# durchsuchen ob im Array schon etwas identisches gespeichert war
		if($eintrag["auswahl_lager"] == $_POST["auswahl_lager"])
		{
			$gefundene_position = $nr;
			break; # foreach abbrechen (suche abbrechen)
		}
	}
	
	if($gefundene_position >= 0)
	{
		# Neue Menge zur gefundenen Position hinzufügen
		$_SESSION["warenkorb"][$gefundene_position]["auswahl_menge"]+=$_POST["auswahl_menge"];		
	}
	else
	{
		# Hinzufügen in den Warenkorb
		$_SESSION["warenkorb"][] = array("auswahl_lager" => $_POST["auswahl_lager"],
									 "auswahl_menge" => $_POST["auswahl_menge"]);	
	}
}

# Datenbankverbindung
#########################################################################
$link = mysqli_connect("localhost",	"root", 	"", 		"onlineshop");
mysqli_query($link, "SET names utf8"); # Verbindung auf utf-8 umstellen
#########################################################################

if(isset($_GET["seite"]) && $_GET["seite"] == "logout")
{
	session_destroy();
	unset($_SESSION);
	setcookie("login_merken", "", time() -1); # cookie entfernen beim Client
	unset($_COOKIE["login_merken"]); # cookie aus dem Server RAM löschen
}





if(isset($_POST["benutzer"]) && isset($_POST["kennwort"]))
{
	if($_POST["benutzer"] == "max" && $_POST["kennwort"] == "mustermann")
	{
		#echo "klappt";
		$_SESSION["eingeloggt"] = true;
		$_SESSION["benutzer"] = "Max Mustermann";
		$_SESSION["mitteilung"] = "<div style='color:lightgreen'>Erfolgreich eingeloggt</div>";
		if(isset($_POST["merken"]))
		{
			setcookie("login_merken", "Max Mustermann", time() + 60*60*24*365);
		}
		# Kopfzeilen ändern
		header("Location: ?seite=verwaltung"); # Weiterleiten zur Verwaltung
		exit; # PHP - Programm Ende
	}
	else
	{
		#echo "falsch";
		$_SESSION["mitteilung"] = "<div style='color:red'>Falsche Eingabe</div>";
	}	
}

# wenn der cookie da ist
if(isset($_COOKIE["login_merken"]))
{
	# automatisch einloggen
	$_SESSION["eingeloggt"] = true;
	$_SESSION["benutzer"] = "Max Mustermann";	
}
?>
<html>
<head>
	<title>Onlineshop</title>
	<meta charset="utf-8" />	
	<link rel="stylesheet" href="css/style.css" />	
</head>
<body>

<header>
	<a href="?seite=start">Startseite</a>
	<a href="?seite=produkte">Produkte</a>
	<a href="?seite=warenkorb">Warenkorb <?php 
	if(isset($_SESSION["warenkorb"])) echo "(".count($_SESSION["warenkorb"]).")";
	?></a>
	
	<?php
	if(isset($_SESSION["eingeloggt"]))
	{
		echo '<a href="?seite=verwaltung">Verwaltung</a>';
		echo '<a href="?seite=logout">Logout</a>';
		echo "Hallo ".$_SESSION["benutzer"];		
	}
	else
	{
		echo '<a href="?seite=login">Login</a>';
	}	
	?>
	
	
</header>

<main>
<?php
if(isset($_SESSION["mitteilung"]))
{
	echo $_SESSION["mitteilung"]; # Anzeigen
	unset($_SESSION["mitteilung"]); # Entfernen / Löschen
}

# wenn die Seite nicht(!) gesetzt ist
if(!isset($_GET["seite"]))
{
	$_GET["seite"] = "start"; # Startseite einstellen
}

#print_r($_GET);

# Seitenauswahl
switch($_GET["seite"])
{
	case "start":
		include("php/startseite.php"); # externe Datei einbinden
	break;
	case "produkte":
		include("php/produkte.php");	 # externe Datei einbinden
	break;
	
	case "bestellen": 
		if(isset($_POST["bestellen"]) 
			&& count($_SESSION["warenkorb"]) > 0)
		{
			include("php/bestellen.php"); # externe Datei einbinden
			break;			
		}
	case "bestelluebersicht":
		if(isset($_POST["bestellen_fortfahren"]) 
			&& count($_SESSION["warenkorb"]) > 0)
		{
			include("php/bestelluebersicht.php"); # externe Datei einbinden
			break;		
		}		
	
	case "warenkorb":
		include("php/warenkorb.php"); # externe Datei einbinden
	break;
	
	case "bestellbestaetigung":
		include("php/bestellbestaetigung.php"); # externe Datei einbinden
	break;
	
	case "login":
		include("php/login.php"); # externe Datei einbinden	
	break;
	case "logout":
		include("php/logout.php"); # externe Datei einbinden	
	break;	
	case "verwaltung":
		if(isset($_SESSION["eingeloggt"]))
		{
			include("php/verwaltung.php"); # externe Datei einbinden
		}
		else
		{
			header("Location: ?seite=login"); # Weiterleitung zum Login
			exit; # Programm verlassen / beenden
		}	
	break;	
	default:
		include("html/404.html"); # externe Datei einbinden
}

?>
</main>

<footer>
Copyright 2021
</footer>

</body>
</html>
<?php
# Datenbankverbindung trennen
#########################################################################
mysqli_close($link);
#########################################################################
?>