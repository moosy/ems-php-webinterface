<script type="text/javascript">

function popup (url) {
  fenster = window.open(url, "Popupfenster", "width=1000,height=700,resizable=yes,scrollbars=yes");
  fenster.focus();
  return false;
}
function popup2 (url) {
  fenster = window.open(url, "Popupfenster2", "width=600,height=200,resizable=no,scrollbars=no");
  fenster.focus();
  return false;
}
</script>

<html>
<head>
<META http-equiv="Expires" CONTENT="0">
<meta http-equiv="refresh" content="2; URL=?seite=a_lemsdisp.php&menu=no">
<link href="/moosy.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0"
rightmargin="0" marginwidth="0" marginheight="0">

<table border=0 cellpadding=10 cellspacing=0>
<tr><td bgcolor=#cccccc>
<h3>Status</h3>
</td><td bgcolor=#cccccc>
<div align=right>
<a href="?seite=a_emsdet.php&menu=no"  onclick="return popup(this.href);">Details</a>
&nbsp;&nbsp;
<a href="?seite=a_emstest.php&menu=no"  onclick="return popup2(this.href);">Funktionstest</a>

</div>
</td></tr>
<tr><td colspan=2 bgcolor=#cccccc>
<center><table bgcolor=#FFFFFF cellspacing=5 cellpadding=5 width=100%>
<?php
flush();
require("emsqry.inc");
require("emsscdesc.inc");
$data = getEmsLiveData();
$d = array();
foreach ($data as $k => $v){
  $d[$k]=htmlentities($v);
}
$desc = $scdesc[$data["Servicecode"].$data["Fehlercode"]];
$stoer = (strpos(" ".$desc,"Störungscode"));

$col1="#dddddd";
$col2="#ffdddd";
$col3="#ddddff";

print("<tr><td colspan=3>");
  print("<table><tr><td>");
  print("<img src=/img/".($d["Flamme"]=="AN"?"flame.jpg":"off.jpg")." alt=Flamme>");
  print("<img src=/img/".($d["3-Wege-Ventil auf WW"]=="AN"?"valve.jpg":"heater.jpg")." alt='Heiz- oder Warmwasserbetrieb'>");
  print("</td><td valign=top>");
  print("<img src=/img/".($d["WW-Tagbetrieb"]=="AN"?"ww_on.png":"ww_off.png")." alt=Warmwasserbereitung>&nbsp;");
  print("<img src=/img/".($d["Zirkulation-Tagbetrieb"]=="AN"?"zirk_on.png":"zirk_off.png")." alt=Zirkulation>&nbsp;");
  print("<img src=/img/".($d["Partybetrieb"]=="AN"?"pp_on.png":"pp_off.png")." alt='Pause-/Partybetrieb'>&nbsp;");
  print("<img src=/img/".($d["Sommerbetrieb"]=="AN"?"sun_on.png":"sun_off.png")." alt=Sommerbetrieb>&nbsp;");
  
  if ($stoer){
    $sccol = "#ff0000";
    print("<img src=/img/maint_error.png alt=Störung>&nbsp;");
  
  } else {  
    $sccol = "#cccccc";
    print("<img src=/img/".($d["Wartung faellig"]=="ja, wegen Datum"?"maint_cal.png":($d["Wartung faellig"]=="ja, wegen Betriebsstunden"?"maint_hour.png":"maint_off.png"))." alt='Wartung'>&nbsp;");
  }
  print("</td></tr></table>");
print("</td></tr>");
print("<tr><td valign=top>Betriebsart</b><br><font size=+1>".($d["HK-Tagbetrieb"]=="AN"?"Tag":"Nacht")."</font><br> ".($d["Automatikbetrieb"]=="AN"?"Automatik":"manuell"));


     
print("</td>");
print("<td valign=top bgcolor=$col2>Kessel</b><br><font size=+1><center>".$d["Kessel-Isttemperatur"]."</font><br>soll: ".$d["Kessel-Solltemperatur"]."</td>");
print("<td valign=top bgcolor=$col1>Kesselpumpe<br><font size=+1><center>".$d["Pumpenmodulation"]."</font></td></tr>");

print("<tr><td>");
print("</td>");

$fs = substr($d["Flammenstrom"],0,-3);
$colfs="#dddddd";
if ($fs > 1) $colfs="#eeeedd";
if ($fs > 10) $colfs="#ffffdd";
if ($fs > 15) $colfs="#ffffcc";
if ($fs > 20) $colfs="#ffffaa";
if ($fs > 35) $colfs="#ffff00";
 

print("<td valign=top bgcolor=$col1>Leistung<br><font size=+1><center>".$d["Mom. Leistung"]."</font></td>");
print("<td valign=top bgcolor=$colfs>Flammenstrom<br><font size=+1><center>".$d["Flammenstrom"]."</font></td></tr>");

print("<tr><td valign=top >");
print("</td>");
print("<td valign=top bgcolor=$col1>Statuscode<br><font size=+1><center>".$d["Servicecode"]." ".$d["Fehlercode"]."</font></td>");
print("<td valign=top bgcolor=$col3>Rücklauf<br><font size=+1><center>".$d["Rücklauftemperatur"]."</font></td></tr>");

print("<tr><td valign=top>Außentemperatur<br><font size=+1>".$d["Außentemperatur"]."</font></td>");
print("<td valign=top bgcolor=$col1>Warmwasser<br><font size=+1><center>".$d["Warmwasser-Isttemperatur (Messstelle 1)"]."</font><br>soll: ".$d["Warmwasser-Solltemperatur"]."</td>");
print("<td  valign=top bgcolor=$col1>Zirkulation<br><font size=+1><center>".$d["Zirkulation"]."</font></td><tr>");



print("</table></center>");
print("<h3>Beschreibung</h3><div style='padding: 1em 1em 1em 1em;background-color: $sccol;' >".$desc."</div>");
?>
</td></tr>
</table>
</body>
</html>