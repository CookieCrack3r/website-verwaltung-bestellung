<a href="?seite=verwaltung&unterseite=produktverwaltung">Zurück</a><br /><br /><hr /><br />

<b>Bezeichnung:</b>
<?= @$bezeichnung;?><br />
<b>Beschreibung:</b>
<?= @$beschreibung;?><br />
<b>Farbe:</b>
<?= @$farbe;?><br />
<b>Preis:</b>
<?= @$preis;?><br />
<b>Bild:</b><br /><?= @$bild; # <?php echo == <?= , @ = Fehler unterdrücken ?>
<br />
<br />
<b>Lagerbestand:</b><br />
<pre>
S 		<?= @$groesse_s;?><br />
M		<?= @$groesse_m;?><br />
L		<?= @$groesse_l;?><br />
XL		<?= @$groesse_xl;?><br />
XXL		<?= @$groesse_xxl;?><br />
</pre>
<br />
<h1>Wollen Sie wirklich löschen?</h1>
<form method='POST'>
<input type='submit' value='JA' name='produkt_loeschen_ja' />
<input type='submit' value='NEIN' name='produkt_loeschen_nein' />
</form>