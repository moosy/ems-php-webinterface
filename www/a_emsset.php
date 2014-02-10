<script>

function w_act(){
  var sel = document.getElementById('mtmode').selectedIndex;
  switch (sel){
  case 0:
    document.getElementById('w_dat').style.visibility = 'hidden';
    document.getElementById('w_hr').style.visibility = 'hidden';
    break;
  case 1:
    document.getElementById('w_dat').style.visibility = 'visible';
    document.getElementById('w_hr').style.visibility = 'hidden';
    break;
  case 2:
    document.getElementById('w_dat').style.visibility = 'hidden';
    document.getElementById('w_hr').style.visibility = 'visible';
    break;
  } 

  var sel = document.getElementById('refinput').selectedIndex;
  switch (sel){
  case 0:
    document.getElementById('w_at1').style.visibility = 'hidden';
    document.getElementById('w_at2').style.visibility = 'hidden';
    break;
  case 1:
    document.getElementById('w_at1').style.visibility = 'visible';
    document.getElementById('w_at2').style.visibility = 'visible';
    break;
  } 

  var sel = document.getElementById('frostmode').selectedIndex;
  switch (sel){
  case 0:
  case 1:
    document.getElementById('w_fs1').style.visibility = 'hidden';
    break;
  case 2:
    document.getElementById('w_fs1').style.visibility = 'visible';
    break;
  } 

  var sel = document.getElementById('redmode').selectedIndex;
  switch (sel){
  case 0:
  case 1:
  case 2:
    document.getElementById('w_ah1').style.visibility = 'hidden';
    break;
  case 3:
    document.getElementById('w_ah1').style.visibility = 'visible';
    break;
  } 

  var sel = document.getElementById('tdstat').selectedIndex;
  switch (sel){
  case 1:
    document.getElementById('w_di1').style.visibility = 'hidden';
    document.getElementById('w_di2').style.visibility = 'hidden';
    document.getElementById('w_di3').style.visibility = 'hidden';
    break;
  case 0:
    document.getElementById('w_di1').style.visibility = 'visible';
    document.getElementById('w_di2').style.visibility = 'visible';
    document.getElementById('w_di3').style.visibility = 'visible';
    break;
  } 

  var sel = document.getElementById('daempfung').selectedIndex;
  switch (sel){
  case 1:
    document.getElementById('w_da1').style.visibility = 'hidden';
    break;
  case 0:
    document.getElementById('w_da1').style.visibility = 'visible';
    break;
  } 


}
  
window.onload = function() { w_act() };    

</script>
<h1>
Erweiterte Einstellungen
<hr>
</h1>

<?php
require("emsgetinfo.inc");
require("emschoosers.inc");
require("emssetvalue.inc");
?>

<form method=post> 
<table border=0 cellspacing=2 cellpadding=7 >
<tr><td bgcolor=#bbbbbb colspan=4><h2>Heizkreis</h2></td></tr>
<tr>
<td>&nbsp;</td>
<td align=left valign=top bgcolor=#cccccc>
<h3>Temperaturen</h3>
<center>
<table>
<tr style='visibility:hidden' id=w_ah1> 
  <td>Nachts reduzierter Betrieb unter</td>
  <?php if (cond("aussenhalt")){?>
  <td><?php tempchooser("nachtred",-20,10,1,"°C","waehlen",getHKInfo("nachtred"));?></td>
  <td><input type=submit name=setnachtred value=Setzen></td>
  <?php } else notavail();?>
</tr>   
<tr style='visibility:hidden' id=w_fs1>
  <td>Frostschutz</td>
  <?php if (cond("frostaussen")){?>
  <td><?php tempchooser("frost",-20,10,1,"°C","waehlen",getHKInfo("frost"));?></td>
  <td><input type=submit name=setfrost value=Setzen></td> 
  <?php } else notavail();?>
</tr>   
<tr>
  <td>Nachtabsenkung abbrechen unter</td>
  <td><?php tempchooser("absquit",-30,10,1,"°C","waehlen",getHKInfo("absquit"));?></td>
  <td><input type=submit name=setabsquit value=Setzen></td>
</tr>   
<tr style='visibility:hidden' id=w_at1>
  <td>Sommerbetrieb ab</td>
  <?php if (cond("aussentemp")){?>
  <td><?php tempchooser("summertime",9,30,1,"°C","waehlen",getHKInfo("summertime"));?></td>
  <td><input type=submit name=setsummertime value=Setzen></td>
  <?php } else notavail();?>
</tr>
<tr style='visibility:hidden' id=w_at2>
  <td>max. Raumtemperatureinfluss</td>
  <?php if (cond("aussentemp")){?>
  <td><?php tempchooser("maxroomeffect",0,10,0.5,"°C","waehlen",getHKInfo("maxroomeffect"));?></td>
  <td><input type=submit name=setmaxroomeffect value=Setzen></td>
  <?php } else notavail();?>
</tr>
<tr>
  <td>min. Vorlauftemperatur</td>
  <td><?php tempchooser("minvorlauf",5,70,1,"°C","waehlen",getHKInfo("minvorlauf"));?></td>
  <td><input type=submit name=setminvorlauf value=Setzen></td>
</tr>
<tr>
  <td>max. Vorlauftemperatur</td>
  <td><?php tempchooser("maxvorlauf",30,90,1,"°C","waehlen",getHKInfo("maxvorlauf"));?></td>
  <td><input type=submit name=setmaxvorlauf value=Setzen></td>
</tr>

</table>

</td>

<td align=left valign=top bgcolor=#cccccc>
<h3>Urlaub</h3>
<center>
<table>
<tr>
  <td>anwesend<br>(stets Samstagsprogramm)</td>
  <td>
      <?php $def = getHolVacInfo("holiday");
        datechooser("ferien",$def["von"],$def["bis"]);?>
  </td>
  <td><input type=submit name=setferien value=Setzen></td>
</tr>   
<tr>
  <td>abwesend<br>(Spezialtemperatur)</td>
  <td>
      <?php $def = getHolVacInfo("vacation");
        datechooser("urlaub",$def["von"],$def["bis"]);?>
  </td>
  <td><input type=submit name=seturlaub value=Setzen></td>
</tr>   
<tr>
  <td>Absenkung</td>
  <td>
      <?php $def = getHKInfo("refinputvac");
        refinputvacchooser("refinputvac",$def);?>
  </td>
  <td><input type=submit name=setrefinputvac value=Setzen></td>
</tr>   

<tr>
  <td>Abwesendtemperatur</td>
  <td><?php tempchooser("ferient",10,30,0.5,"°C","waehlen",getHKInfo("ferient"));?></td>
  <td><input type=submit name=setferient value=Setzen></td>
</tr>   
</table>

</td>
</tr>
<tr>
<td>&nbsp;</td>
<td align=left valign=top bgcolor=#cccccc>
<h3>Regelung</h3>
<center>
<table>
<tr>
  <td>Führungsgröße</td>
  <td>
      <?php $def = getHKInfo("refinput");
        refinputchooser("refinput",$def);?>
  </td>
  <td><input type=submit name=setrefinput value=Setzen></td>
</tr>   
<tr>
  <td>Absenkungsart</td>
  <td>
      <?php $def = getHKInfo("redmode");
        redmodechooser("redmode",$def);?>
  </td>
  <td><input type=submit name=setredmode value=Setzen></td>
</tr>   

<tr>
  <td>Frostschutzmethode</td>
  <td>
      <?php $def = getHKInfo("frostmode");
        frostmodechooser("frostmode",$def);?>
  </td>
  <td><input type=submit name=setfrostmode value=Setzen></td>
</tr>   

<tr>
  <td>Schaltzeitenoptimierung</td>
  <td>
      <?php $def = getHKInfo("schedoptimizer");
        onoffchooser("schedoptimizer",$def);?>
  </td>
  <td><input type=submit name=setschedoptimizer value=Setzen></td>
</tr>   



</table>
</td>

<td align=left valign=top bgcolor=#cccccc>
<h3>Standortdaten</h3>
<center>

<table>

<tr>
  <td>Dämpfung Außentemperatur</td>
  <td><?php onoffchooser("daempfung",getHKInfo("daempfung"));?></td>
  <td><input type=submit name=setdaempfung value=Setzen></td>
</tr>
<tr style='visibility:hidden' id=w_da1>
  <td>Gebäudeart</td>
<?php if (cond("daempfung")){?>
  <td><?php gebaeudechooser("gebaeude",getHKInfo("gebaeude"));?></td>
  <td><input type=submit name=setgebaeude value=Setzen></td>
<?php } else notavail();?>
</tr>
  
<tr>
  <td>Minimale Aussentemperatur</td>
  <td><?php tempchooser("maut",-30,0,1,"°C","waehlen",getHKInfo("minaussentemp"));?></td>
  <td><input type=submit name=setmaut value=Setzen></td>
</tr>



</table>


</td>

</tr>


<tr><td  bgcolor=#bbbbbb colspan=4><h2>Warmwasser</h2></td></tr>
<tr>
<td>&nbsp;</td>
<td align=left valign=top bgcolor=#cccccc>
<h3>Parameter</h3>
<center>

<table>
<tr>
  <td>Zirkulation pro Std.</td>
  <td><?php zirkchooser("spzir",getWWInfo("spzir"));?></td>
  <td><input type=submit name=setspzir value=Setzen></td>
</tr>
<tr>
  <td>Begrenzung Warmwasser auf</td>
  <td><?php tempchooser("limittemp",30,80,1,"°C","waehlen",getWWInfo("limittemp"));?></td>
  <td><input type=submit name=setlimittemp value=Setzen></td>
</tr>
<tr>
  <td>LED Einmalladungstaste</td>
  <td>
      <?php $def = getWWInfo("loadled");
        onoffchooser("loadled",$def);?>
  </td>
  <td><input type=submit name=setloadled value=Setzen></td>
</tr>   

</table>


</td>
<td align=left  valign=top bgcolor=#cccccc>
<h3>Desinfektion</h3>
<center>
<table>
<tr>
  <td>Betriebsart</td>
  <td><?php onoffchooser("tdstat",getWWInfo("tdstat"));?></td>
  <td><input type=submit name=settdstat value=Setzen></td>
</tr>
<tr style='visibility:hidden' id=w_di1>
  <td>Temperatur</td>
  <?php if (cond("desinfect")){?>
  <td><?php tempchooser("tdtemp",30,80,1,"°C","wählen",getWWInfo("tdtemp"));?></td>
  <td><input type=submit name=settdtemp value=Setzen></td>
  <?php } else notavail();?>
</tr>   
<tr style='visibility:hidden' id=w_di2>
  <td>Tag</td>
  <?php if (cond("desinfect")){?>
  <td><?php daychooser("tdday",getWWInfo("tdday"));?></td>
  <td><input type=submit name=settdday value=Setzen></td>
  <?php } else notavail();?>
</tr>   
<tr style='visibility:hidden' id=w_di3>
  <td>Stunde</td>
  <?php if (cond("desinfect")){?>
  <td><?php hourchooser("tdhour",getWWInfo("tdhour"));?></td>
  <td><input type=submit name=settdhour value=Setzen></td>
  <?php } else notavail();?>
</tr>   
</table>

</td>

</tr>


<tr><td  bgcolor=#bbbbbb colspan=4><h2>Kessel</h2></td></tr>
<tr>
<td>&nbsp;</td>

<td align=left  valign=top bgcolor=#cccccc>
<h3>Taktsperre</h3>
<center>
<table>
<tr>
  <td>Antipendelzeit</td>
  <td><?php tempchooser("antipen",5,30,1,"min","wählen",getUBAinfo("antipen"));?></td>
  <td><input type=submit name=setantipen value=Setzen></td>
</tr>   
<tr>
  <td>Einschalthysterese</td>
  <td><?php tempchooser("hystein",-10,-1,1,"°C","wählen",getUBAinfo("hystein"));?></td>
  <td><input type=submit name=sethystein value=Setzen></td>
</tr>   
<tr>
  <td>Ausschalthysterese</td>
  <td><?php tempchooser("hystaus",1,10,1,"°C","wählen",getUBAinfo("hystaus"));?></td>
  <td><input type=submit name=sethystaus value=Setzen></td>
</tr>   
</table>

</td>

<td align=left valign=top bgcolor=#cccccc>
<h3>Kesselpumpe</h3>
<center>
<table>
<tr>
  <td>Nachlauf</td>
  <td><?php tempchooser("kpnachl",1,15,1,"min","wählen",getUBAinfo("kpnachl"));?></td>
  <td><input type=submit name=setkpnachl value=Setzen></td>
</tr>   
<tr>
  <td>min. Leistung </td>
  <td><?php tempchooser("kpmin",55,100,5,"%","wählen",getUBAinfo("kpmin"));?></td>
  <td><input type=submit name=setkp value=Setzen></td>
</tr> 
<tr>  
  <td>max. Leistung </td>
  <td><?php tempchooser("kpmax",55,100,5,"%","wählen",getUBAinfo("kpmax"));?></td>
  <td><input type=submit name=setkp value=Setzen></td>
</tr>   
</table>

</td>

</tr>

<tr>
<td></td>
<td align=left valign=top bgcolor=#cccccc>
<h3>Wartung</h3>
<center>
<table>
<tr>
  <td>Wartungsmeldungen</td>
  <td><?php maintchooser("mtmode",getMaintenanceInfo("mtmode"));?></td>
  <td rowspan=3 valign=bottom><input type=submit name=setmaint value=Setzen></td>
</tr>
<tr id='w_dat' style='visibility:hidden'> 
  <td>
  nächstes Wartungsdatum</td>
  <td>

  <?php
    $def = substr("00".getMaintenanceInfo("mtday"),-2).".".
           substr("00".getMaintenanceInfo("mtmonth"),-2).".20".
           substr("00".getMaintenanceInfo("mtyear"),-2);
    datechooser("mtdate",$def);
  
  ?>
  </td>
</tr>   
<tr id='w_hr' style='visibility:hidden'> 
  <td>Wartungsbetriebsstunden</td>
  <td>
  <?php
    $def = getMaintenanceInfo("mthours")*100;
    tempchooser("mthours",100,6000,100,"h","wählen",$def);
    ?>
  </td>
</tr>   
</table>

</td>

<td  align=left valign=top bgcolor=#cccccc>
<h3>Allgemein</h3>
<center>
<table>


<tr>
  <td valign=top>Kontaktdaten</td>
  <td>
  <?php
    $cl1 = htmlentities(getContactInfo(1));
    $cl2 = htmlentities(getContactInfo(2));
    print('<input style="font-family: monospace;" name=contact1 type=text size=21 maxlength=21 value="'.$cl1.'"><br>');
    print('<input style="font-family: monospace;" name=contact2 type=text size=21 maxlength=21 value="'.$cl2.'"><br>');
    ?>
  </td>
  <td valign=bottom><input type=submit name=setcontact value=Setzen></td>
</tr>   

</table>
</td>
</tr>

</table>
</form>
<?php
flush();
?>
