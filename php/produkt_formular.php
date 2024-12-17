<a href="?seite=verwaltung&unterseite=produktverwaltung">Zurück</a><br /><br /><hr /><br />

<form method='post' enctype="multipart/form-data">

<?php
# <?php echo 	==	      <?= 
# @ = Fehler unterdrücken
?>
Bezeichnung<br /> 
<input type="text" name="bezeichnung" value="<?= @$bezeichnung;?>" /><br />

Beschreibung<br />
<textarea name="beschreibung"><?= @$beschreibung;?></textarea><br />

Farbe<br />
<input type="text" name="farbe" value="<?= @$farbe;?>" /><br />

Preis<br />
<input type="text" name="preis" value="<?= @$preis;?>" /><br />

Bild<br /><?= @$bild; ?>
<input type="file" name="bild" /><br />

<br />
<b>Lagerbestand:</b><br />
<pre>
S 		<input type="text" name="groesse_s" value="<?= @$groesse_s;?>" /><br />
M		<input type="text" name="groesse_m" value="<?= @$groesse_m;?>" /><br />
L		<input type="text" name="groesse_l" value="<?= @$groesse_l;?>" /><br />
XL		<input type="text" name="groesse_xl" value="<?= @$groesse_xl;?>" /><br />
XXL		<input type="text" name="groesse_xxl" value="<?= @$groesse_xxl;?>" /><br />
</pre>



<br />
<input type="submit" name="produkt_speichern" />

</form>