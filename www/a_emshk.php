<?php

require("/emsincludes/emsgetinfo.inc");
require("/emsincludes/emssetvalue.inc");
require("/emsincludes/emschoosers.inc");
exec('sudo '.$emsscriptpath.'/calcheizkurve.sh');
?>
<script type="text/javascript">
sources = new Array("minaussentemp","auslegtemp","raumoffset","day","night");
function disableControls(){
  for (i=0; i<sources.length; i++){
    document.getElementById(sources[i]).disabled=true;  
  }
}

function enableControls(){
  for (i=0; i<sources.length; i++){
    document.getElementById(sources[i]).disabled=false;  
  }
}

function setValue(src)
{
  disableControls();
  sendq=new XMLHttpRequest();
  sendq.onreadystatechange=function()
  {
    if (sendq.readyState==4 && sendq.status==200){
      refreshPic();
    }
  }
  var sel = document.getElementById(src);
  var newval = sel.options[sel.selectedIndex].value;
  sendq.open("GET","ajax.php?seite=emssetval.ajax&source="+src+"&value="+newval,true);
  sendq.send();
}

function refreshPic(src)
{
  picq=new XMLHttpRequest();
  picq.onreadystatechange=function()
  {
    if (picq.readyState==4 && picq.status==200){
      document.getElementById("hkpic").innerHTML=picq.responseText;
      enableControls();
    }
  }
  picq.open("GET","ajax.php?seite=emshkpic.ajax",true);
  picq.send();
}








</script>
<form name=ems method=post> 

<h1>
Heizkurve
<hr>
</h1>
<center>
<p>
<?php
if(getHKInfo("refinput")=="outdoor"){
print('<div id=hkpic><img src="graphs/hk.png?t='.time().'" alt="Bild" width=90% /></div>');
$temp = file("/tmp/temp.dat");
?>
</p>

<table border=0 cellspacing=3 cellpadding=3 >

<tr>
<td bgcolor=#cccccc>
Minimale Aussentemperatur<br>
<?php tempchooser("minaussentemp",-30,0,1,"°C","wählen",$temp[4]);?>
</td>   
<td bgcolor=#cccccc>
Auslegungstemperatur<br>
<?php tempchooser("auslegtemp",20,50,1,"°C","wählen",$temp[0]);?>
</td>   
<td bgcolor=#cccccc>
Raumtemperaturoffset<br>
<?php tempchooser("raumoffset",-5,5,0.5,"°C","wählen",$temp[1]);?>
</td>   
<td bgcolor=#cccccc>
Tagtemperatur<br>
<?php tempchooser("day",10,30,0.5,"°C","wählen",$temp[2]);?>
</td>   
<td bgcolor=#cccccc valign=top>
Nachttemperatur<br>
<?php 
if (getHKInfo("redmode")!="offmode"){
tempchooser("night",10,30,0.5,"°C","wählen",$temp[3]);?>
<?php
} else { print("<b>Heizung aus</b>");}
?>
</td>   
</tr>
</table>
</center>
</form>
<?php
flush_buffers();

} else {
print("<h3>Anlage ist raumtemperaturgeführt!</h3>");
print("Die Festlegung einer Heizkurve ist nur bei aussentemperaturgeführter Regelung möglich.<br>");
print("Bitte wählen Sie unter 'Einstellungen' die Aussentemperatur als Führungsgröße.<p>");
}
?>
<script type="text/javascript">
for (i=0; i<sources.length; i++){
  document.getElementById(sources[i]).onchange = function(){setValue(this.id);};
}
</script>
<?php
close_ems();
?>