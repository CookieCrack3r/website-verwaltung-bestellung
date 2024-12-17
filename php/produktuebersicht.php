<?php
include("produkt_suchen.php");


/*
# 100% identisch
$antwort = mysqli_query($link, "
select * from produkte
where bezeichnung = '".$_SESSION["suche"]."'
order by preis
");

# ähnlich LIKE (Teilbegriffe,  % = beliebige viele Zeichen)
$antwort = mysqli_query($link, "
select * from produkte
where bezeichnung LIKE '%".$_SESSION["suche"]."%'
order by preis
");
*/

#where bezeichnung LIKE '%".$_SESSION["suche"]."%'
$bedingungen_array = array();
# Suche
if(isset($_SESSION["suche"]) && $_SESSION["suche"] != "")
{
	$bedingungen_array[] = "
	(	
	bezeichnung LIKE '%".$_SESSION["suche"]."%'
	OR
	beschreibung LIKE '%".$_SESSION["suche"]."%'
	OR
	farbe LIKE '%".$_SESSION["suche"]."%'
	)
	";
}

# Filter
$array = array("S","M","L","XL","XXL");
$filter_array = array();
foreach($array as $groesse)
{
	if(isset($_SESSION["filter_groessen"][$groesse]))
	{
		$filter_array[] = "(select sum(verfuegbare_menge) from lagerbestand 
									where groesse = '$groesse' 
									and lagerbestand.produkt_fk = produkte.produkt_pk) > 0 ";
	}
}
if(count($filter_array) > 0)
{
	$bedingungen_array[] = "(".implode(" OR ", $filter_array).")";
}
$bedingungen = "";
if(count($bedingungen_array) > 0)
{
	$bedingungen = " WHERE ";
	$bedingungen .= implode(" AND ", $bedingungen_array);
}


$sql_befehl = "
select * from produkte
$bedingungen
order by preis";

#echo "<h1>$sql_befehl</h1>";
$antwort = mysqli_query($link, $sql_befehl);


echo "<div class='flexibel'>";
while($datensatz = mysqli_fetch_array($antwort))
{
	if($datensatz["bild"] == "")
	{
		$datensatz["bild"] = "dummy.jpg";
	}	
	# Produktanfang
	echo "<a href='?seite=produkte&produkt=".$datensatz["produkt_pk"]."' class='produktvorschau'>";
	echo "<div>".$datensatz["bezeichnung"]."</div>";
	echo "<img src='uploads/".$datensatz["bild"]."' />";
	echo "<div>".$datensatz["preis"]."</div>";
	echo "<div>".$datensatz["beschreibung"]."</div>";
	echo "<div>".$datensatz["farbe"]."</div>";

	# Lagerbestand
	$lagerbestand = mysqli_query($link, "select * from lagerbestand
							 where produkt_fk = ".$datensatz["produkt_pk"]."
							 and verfuegbare_menge > 0");
			
	echo "<div>(";
	$liste = array();
	while($lagerdatensatz = mysqli_fetch_array($lagerbestand))
	{
		$liste[] = $lagerdatensatz["groesse"];
	}	
	echo implode(" , ",$liste); # konvertieren zur Zeichenkette
	
	echo ")</div>";
	echo "</a>";
	# Produktende
}
echo "</div>";


