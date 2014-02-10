<?php

require("emsgetinfo.inc");
require("emssetvalue.inc");
require("emschoosers.inc");

exec('/usr/local/bin/calcheizkurve.sh');

?>
<form method=post> 

<h1>
Heizkurve
<hr>
</h1>
<center>
<p>
<?php

if(cond("aussentemp")){

print('<img src="/graphs/hk.png?t='.time().'" alt="Bild" width=90% />');
$temp = file("/tmp/temp.dat");
?>
</p>

<table border=0 cellspacing=3 cellpadding=3 >

<tr>
<td bgcolor=#cccccc>
Minimale Aussentemperatur<br>
<?php tempchooser("maut",-30,0,1,"°C","wählen",$temp[4]);?>
<br>
<input type=submit name=setmaut value=Setzen>
</td>   
<td bgcolor=#cccccc>
Auslegungstemperatur (<?php print($temp[4]);?>°C)<br>
<?php tempchooser("at",20,50,1,"°C","wählen",$temp[0]);?>
<br>
<input type=submit name=setat value=Setzen>
</td>   
<td bgcolor=#cccccc>
Raumtemperaturoffset<br>
<?php tempchooser("ro",-5,5,0.5,"°C","wählen",$temp[1]);?>
<br>
<input type=submit name=setro value=Setzen>
</td>   
<td bgcolor=#cccccc>
Tagtemperatur<br>
<?php tempchooser("day",10,30,0.5,"°C","wählen",$temp[2]);?>
<br>
<input type=submit name=setday value=Setzen>
</td>   
<td bgcolor=#cccccc valign=top>
Nachttemperatur<br>
<?php 
if (!cond("abschalt")){
tempchooser("night",10,30,0.5,"°C","wählen",$temp[3]);?>
<br>
<input type=submit name=setnight value=Setzen>
<?php
} else { print("<p><b>Heizung aus</b>");}
?>
</td>   
</tr>
</table>
</center>
</form>
<?php
flush();
} else {
print("<h3>Anlage ist raumtemperaturgeführt!</h3>");
print("Die Festlegung einer Heizkurve ist nur bei aussentemperaturgeführter Regelung möglich.<br>");
print("Bitte wählen Sie unter 'Einstellungen' die Aussentemperatur als Führungsgröße.<p>");
}
?>
