
<?php
require("emssetvalue.inc");
require("emsgetinfo.inc");
require("emschoosers.inc");
flush();
?>

<form method=post> 
<center>
<table border=0 cellspacing=2 cellpadding=7 >
<tr><td  bgcolor=#bbbbbb colspan=2><h3>Betriebsart</h3></td></tr>

<tr>
<td align=center colspan=2 bgcolor=#cccccc>
<input name=hkmode type=submit value=Tag>
<input name=hkmode type=submit value=Nacht>
<input name=hkmode type=submit value=Auto>
</td>   
</tr>
<tr><td bgcolor=#bbbbbb colspan=2><h3>Heizkreis</h3></td></tr>
<tr>
<td colspan=2 bgcolor=#cccccc align=center>

<?php

$temptemp = getHKInfo("temptemp");
$ttcol="#ffff90";
if ($temptemp==0){
  $ttcol="#eeeeee";
  if (getHKInfo("tagbetr")=="on"){
    $temptemp =  getHKInfo("tag");
  } else {
    $temptemp =  getHKInfo("nacht");
  }  
  
}


?>
<script>
var i = <?php print($temptemp);?> ;

function dispv(){
  if (i<5) i=5;
  if (i>30) i=30;
  var sel = document.getElementById('cnt');
  sel.innerHTML = '<input type=hidden name=temptemp value='+i+'><u>'+i.toFixed(1)+'</u>';
}

function addv(){
  i=i+0.5;
  dispv();
}


function subv(){
  i=i-0.5;
  dispv();
}
</script>

<table bgcolor=#eeeeee border=0>
<tr>
<td bgcolor=#eeeeee><a href="javascript:void();" onclick=subv();><img src=/img/minus.png border=0></a></td>
<td bgcolor=<?php print($ttcol);?> width=90px align=center><font size=+1>
<span id=cnt>
<?php 
printf("%1.1f",$temptemp);
print("<input type=hidden name=temptemp value=$temptemp>");
?>
</span></font><font size=-1.5><sup>&nbsp;°C</sup></td>
<td bgcolor=#eeeeee><a href="javascript:void(); " onclick=addv();><img src=/img/plus.png border=0></a></td>
<td><input name=settemptemp type=submit value='Set'><br><input name=settemptempoff type=submit value='Reset'></td>
</tr>
</table>

</td>

</tr>

<tr>
<td align=left valign=top bgcolor=#cccccc>
Tagtemperatur<br><center>
<?php tempchooser("day",10,30,0.5,"°C","waehlen",getHKInfo("tag"));?>
<br>
<input type=submit name=setday value=Setzen>
</td>   
<td align=left valign=top bgcolor=#cccccc>
Nachttemperatur<br><center>
<?php 
if (!cond("abschalt")){
tempchooser("night",10,30,0.5,"°C","waehlen",getHKInfo("nacht"));?>
<br>
<input type=submit name=setnight value=Setzen>
<?php
} else {
  print("<p><b>Heizung<br>aus</b>");
}
?>

</td>   
</tr><tr>
<td align=left bgcolor=#cccccc>
Partymodus<br><center>
<?php tempchooser("party",1,48,1,"h","aus",getPartyInfo("party"));?>
<br>
<input type=submit name=setparty value=Setzen>
</td>   


<td align=left bgcolor=#cccccc>
Pausemodus<br><center>
<?php tempchooser("pause",1,48,1,"h","aus",getPartyInfo("pause"));?>
<br>
<input type=submit name=setpause value=Setzen>
</td>   
</tr>

<tr><td  bgcolor=#bbbbbb colspan=2><h3>Warmwasser</h3></td></tr>
<tr>
<td align=left bgcolor=#cccccc>
Temperatur<br><center>
<?php tempchooser("ww",30,80,1,"°C","wählen",getWWinfo("wwtag","2"));?>
<br>
<input type=submit name=setww value=Setzen>
</td>   
<td align=left bgcolor=#cccccc>
Einmalladung<br><center>
<input name=wwload type=submit value=Starten><br>
<input name=wwload type=submit value=Abbrechen>
</td>   
</tr>
<tr>
<td align=left bgcolor=#cccccc>
Betriebsart<br><center>
<?php modechooser("wwmode",getWWinfo("ww"));?>
<br>
<input type=submit name=setwwmode value=Setzen>
</td>   
<td align=left bgcolor=#cccccc>
Zirkulation<br><center>
<?php modechooser("zirmode",getWWinfo("zir"));?>
<br>
<input type=submit name=setzirmode value=Setzen>
</td>   
</tr>


</table>
</center>
</form>
<?php
flush();
sleep(1);
doEmsCommand("hk1 getstatus");
?>
