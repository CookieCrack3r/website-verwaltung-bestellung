<?php
if(isset($_GET["produkt"]))
{
	include("produktdetails.php"); # nur ein produkt
}
else
{
	include("produktuebersicht.php"); # alle produkte
}
?>