<html>
<head>
<META http-equiv="Expires" CONTENT="0">
<link href="/moosy.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color:#eeeeee" text="#000000" leftmargin="0" topmargin="1em"
rightmargin="1em" marginwidth="1em" marginheight="1em">
<h3>Funktionstest<hr></h3>
<form method=post>
<table border=0 cellspacing=5 cellpadding=3>
<tr bgcolor=#cccccc>
<td colspan=4>
Bitte regelmäßig auf "Aktivieren" klicken, sonst wird der 
Test automatisch abgebrochen!
</td></tr><tr bgcolor=#cccccc>
<?php

require("emsqry.inc");
require("emschoosers.inc");
if (!open_ems()) die("<h3>FATAL: Keine Verbindung zum EMS-Bus möglich.</h3>");

$kessel = $pumpe = 0;
$dwv = $zirk = $active = "off";

if (isset($_POST["active"])){

  if ($_POST["active"] == "Aktivieren"){
    $active = "on";
    $kessel = $_POST["kessel"];
    $pumpe = $_POST["pumpe"];
    $dwv = $_POST["dwv"];
    $zirk = $_POST["zirk"];
  }

  $dwvv = ($dwv=="on"?1:0);
  $zirkv = ($zirk=="on"?1:0);
  doEmsCommand("uba testmode $active $kessel $pumpe $dwvv $zirkv");
}


print("<td><b>Brennerleistung</b><br>");
tempchooser("kessel",0,100,5,"%","wählen",$kessel);
print("</td>");

print("<td><b>Pumpenleistung</b><br>");
tempchooser("pumpe",0,100,5,"%","wählen",$pumpe);
print("</td>");

print("<td><b>3W-Ventil WW</b><br>");
onoffchooser("dwv",$dwv);
print("</td>");

print("<td><b>Zirkulationspumpe</b><br>");
onoffchooser("zirk",$zirk);
print("</td>");

?>
</tr><tr bgcolor=#cccccc>
<td colspan=4 align=center>
<input type=submit name=active value=Aktivieren>
<input type=submit name=active value=Deaktivieren></td>
</tr>
</table>

</form>
</body>
</html>