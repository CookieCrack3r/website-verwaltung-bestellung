<?php
if(isset($_POST["suchbutton"]))
{
	$_SESSION["suche"] = $_POST["suche"];
}
?>
<form method='post'>
Suche: <input type='text' name='suche' value='<?= @$_SESSION["suche"]; ?>' />
<input type='submit' name='suchbutton' value='Suchen' />

<?php
$array = array("S","M","L","XL","XXL");
foreach($array as $groesse)
{
	$klasse = "button_inaktiv";
	
	# Wenn der Button gedrückt wurde
	if(isset($_POST["button_$groesse"]))
	{
		if(!isset($_SESSION["filter_groessen"]["$groesse"]))
		{
			# Dann den Button speichern (aktivieren)
			$_SESSION["filter_groessen"]["$groesse"] = true;			
		}
		else
		{
			# Dann den Button rausschmeissen (deaktivieren)
			unset($_SESSION["filter_groessen"]["$groesse"]);			
		}
	}		
	
	if(isset($_SESSION["filter_groessen"]["$groesse"]))
	{
		$klasse = "button_aktiv";
	}
	
	echo "<input type='submit' value='$groesse' 
			name='button_$groesse' class='$klasse' />";
}
?>






</form>

