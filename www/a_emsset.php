<h1>
Erweiterte Einstellungen
<hr>
</h1>
<table id=prog width=250px bgcolor=#cccccc cellspacing=0 cellpadding=5>
<tr bgcolor=#ffffff height=50px><td colspan=8></td></tr>
<tr height=30px><td colspan=8 bgcolor=#cccccc><div id=prc align=center></div></td></tr>
<tr height=3px>
<td id=p0 bgcolor=#eeeeee></td>
<td id=p1 bgcolor=#dddddd></td>
<td id=p2 bgcolor=#cccccc></td>
<td id=p3 bgcolor=#bbbbbb></td>
<td id=p4 bgcolor=#aaaaaa></td>
<td id=p5 bgcolor=#999999></td>
<td id=p6 bgcolor=#888888></td>
<td id=p7 bgcolor=#777777></td>
</tr>
</tablel>
<script type="text/javascript">
function progress(perc){
    document.getElementById("prc").innerHTML="<b>Bitte warten...</b> ("+perc+"%)";
  for (i=0; i<=perc/12; i++){
    if (i<8) document.getElementById("p"+i).style.backgroundColor="#8888ff";
  }
}
progress(10);
</script>
<?php
require("/emsincludes/emssetvalue.inc");
require("/emsincludes/emsgetinfo.inc");
require("/emsincludes/emschoosers.inc");
flush_buffers();
?>
<script type="text/javascript">
function setValue(src)
{
  w_act();
  nextRequest = 20;
  sendq[src]=new XMLHttpRequest();
  sendq[src].onreadystatechange=function()
  {
    if (sendq[src].readyState==4 && sendq[src].status==200){
//    if (  !( ((src=="vacation")||(src=="holiday")) && onholidayedit[src] )  ) resetEnable(src);
      
      nextRequest = 3;
    }
  }
  sndsrc = src;
  if ((src == "contact1") || (src == "contact2")){
    var newval = document.getElementById(src).value;
  } else if ((src == "mtmode") || (src == "mtdate") || (src == "mthours")){
    switch (document.getElementById('mtmode').selectedIndex){
    case 0:
      var newval = "off";
      break;
    case 1:
      var newval = "bydate-"+document.getElementById("mtdate").value;
      break;
    case 2:
      var newval = "byhours-"+(document.getElementById("mthours").value/100);
      break;
    }
    sndsrc = "maint";
  } else if ((src == "holiday")||(src == "vacation")){
    onholidayedit[src] = false;
    var newval = document.getElementById(src+"_von").value+"-"+document.getElementById(src+"_bis").value;
  } else if ((src == "kpmin")||(src == "kpmax")){
    var newval = document.getElementById("kpmin").value+"-"+document.getElementById("kpmax").value;
    sndsrc = "kpmod";
  } else { 
    var sel = document.getElementById(src);
    var newval = sel.options[sel.selectedIndex].value;
  }
  document.getElementById("statusbar").innerHTML="Wert '"+sndsrc+"' gesetzt auf '"+newval+"'.";
  sendq[src].open("GET","ajax.php?seite=emssetval.ajax&source="+sndsrc+"&value="+newval,true);
  sendq[src].send();
  resetEnable(src);
}

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

  var sel = document.getElementById('refinputvac').selectedIndex;
  switch (sel){
  case 0:
    document.getElementById('w_uah1').style.visibility = 'hidden';
    break;
  case 1:
    document.getElementById('w_uah1').style.visibility = 'visible';
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


function showInfos(){
  if (!activeRequest){
    nextRequest--;
    if (nextRequest <= 0){
      nextRequest = 10;
      var qrysrc = "";
      for (currsrc=0; currsrc < sources.length; currsrc++){
        var src = sources[currsrc];
        if (qenabled[src]) qrysrc = qrysrc + (qrysrc==""?"":"-") + src;
      }
      activeRequest=true;
      xmlhttp.open("GET","ajax.php?seite=emsgetval.ajax&source="+qrysrc+"&id="+Math.random(),true);
      xmlhttp.send();
    }
  }
}


function blockEnable(src){
  if ((src == "holiday") || (src == "vacation")){
    onholidayedit[src]=true;
  }
  setEnable(src,false);
}

function resetEnable(src){
  setEnable(src,true);
}

function setEnable(src,value){
  if (src == "maint") src="mtdate";
  qenabled[src] = value;
  return value;
}


var selsources = new Array('nachtred','frost','absquit','summertime','maxroomeffect','minvorlauf','maxvorlauf',
                        'refinputvac','urlaubt','urlaubnachtred','refinput','redmode','frostmode','schedoptimizer',
                        'daempfung','gebaeude','minaussentemp','spzir','limittemp','loadled',
                        'tdstat','tdtemp','tdday','tdhour','antipen','hystein','hystaus',
                        'kpnachl','kpmin','kpmax','mtmode','mthours');
var sources = selsources.concat(new Array("holiday","vacation","mtdate","contact1","contact2"));

var qenabled = new Array();
var sendq = new Array();
var currsrc = 0;
var activeRequest = false;
var nextRequest = 1;
var onholidayedit = new Object();
onholidayedit["holiday"] = false;
onholidayedit["vacation"] = false;

for (n=0; n<sources.length; n++){
  setEnable(sources[n],true);
}
  

xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function()
{
  w_act();
  progress(100);
  document.getElementById("main").style.display="block";
  document.getElementById("prog").style.display="none";

  if (xmlhttp.readyState==4 && xmlhttp.status==200){
    var rline = xmlhttp.responseText.split("\n");
    for (r=0; r<rline.length; r++){
      if (rline[r].length < 3) continue;
      var info = rline[r].split("=");
      var src = info[0];
      var val = info[1];
      if (!qenabled[src]) continue;            
      if ((src == "contact1") || (src == "contact2") || (src == "mtdate")){
        document.getElementById(src).value=val;
      } else if ((src == "holiday") || (src == "vacation")){
        var dates = val.split("-");
        var dfrom = dates[0];
        var dto   = dates[1];
        document.getElementById(src+"_von").value=dfrom;
        document.getElementById(src+"_bis").value=dto;
      } else {    
        if (src=="mthours") val *= 100;
        var sel = document.getElementById(src);
        for (n=0; n < sel.length; n++){
          if (sel.options[n].value==val) sel.selectedIndex=n;
        }
      }
    }
    activeRequest=false;
  }
}

  
window.onload = function() { w_act() };    
progress(20);

</script>
<?php flush_buffers();?>
<form method=post> 
<table border=0 cellspacing=2 cellpadding=7 id=main style="display: none;"> 
<tr><td bgcolor=#bbbbbb colspan=4><h2>Heizkreis</h2></td></tr>
<tr>
<td>&nbsp;</td>
<td align=left valign=top bgcolor=#cccccc>
<h3>Temperaturen</h3>
<table width=100%> 
<tr style='visibility:hidden' id=w_ah1> 
  <td>Nachts reduzierter Betrieb unter</td>
  <td align=right><?php tempchooser("nachtred",-20,10,1,"°C","waehlen",getHKInfo("nachtred"));?></td>
</tr>   
<script type="text/javascript">progress(30);</script>
<?php flush_buffers();?>
<tr style='visibility:hidden' id=w_fs1>
  <td>Frostschutz</td>
  <td align=right ><?php tempchooser("frost",-20,10,1,"°C","waehlen",getHKInfo("frost"));?></td>
</tr>   
<tr>
  <td>Nachtabsenkung abbrechen unter</td>
  <td align=right ><?php tempchooser("absquit",-31,10,1,"°C","waehlen",getHKInfo("absquit"),array("-31" => "aus"));?></td>
</tr>   
<tr style='visibility:hidden' id=w_at1>
  <td>Sommerbetrieb ab</td>
  <td align=right ><?php tempchooser("summertime",9,30,1,"°C","waehlen",getHKInfo("summertime"));?></td>
</tr>
<tr style='visibility:hidden' id=w_at2>
  <td>max. Raumtemperatureinfluss</td>
  <td align=right ><?php tempchooser("maxroomeffect",0,10,0.5,"°C","waehlen",getHKInfo("maxroomeffect"));?></td>
</tr>
<tr>
  <td>min. Vorlauftemperatur</td>
  <td align=right ><?php tempchooser("minvorlauf",5,70,1,"°C","waehlen",getHKInfo("minvorlauf"));?></td>
</tr>
<tr>
  <td>max. Vorlauftemperatur</td>
  <td align=right ><?php tempchooser("maxvorlauf",30,90,1,"°C","waehlen",getHKInfo("maxvorlauf"));?></td>
</tr>

</table>

</td>

<td align=left valign=top bgcolor=#cccccc>
<h3>Urlaub</h3>
<table width=100%>
<tr>
  <td>anwesend<br>(stets Samstagsprogramm)</td>
  <td align=right >
      <?php $def = getHolVacInfo("holiday");
        datechooser("holiday",$def["von"],$def["bis"]);?>
  </td>
</tr>   
<tr>
  <td>abwesend<br>(Spezialtemperatur)</td>
  <td align=right >
      <?php $def = getHolVacInfo("vacation");
        datechooser("vacation",$def["von"],$def["bis"]);?>
  </td>
</tr>   
<script type="text/javascript">progress(40);</script>
<?php flush_buffers();?>
<tr>
  <td>Abwesendtemperatur</td>
  <td align=right ><?php tempchooser("urlaubt",10,30,0.5,"°C","waehlen",getHKInfo("urlaubt"));?></td>
</tr>   
<tr>
  <td>Absenkung</td>
  <td align=right >
      <?php $def = getHKInfo("refinputvac");
        refinputvacchooser("refinputvac",$def);?>
  </td>
</tr>   

<tr id=w_uah1>
  <td>Nachts reduzierter Betrieb unter</td>
  <td align=right ><?php tempchooser("urlaubnachtred",-20,10,1,"°C","waehlen",getHKInfo("urlaubnachtred"));?></td>
</tr>   
</table>

</td>
</tr>
<tr>
<td>&nbsp;</td>
<td align=left valign=top bgcolor=#cccccc>
<h3>Regelung</h3>
<table width=100%>
<tr>
  <td>Führungsgröße</td>
  <td align=right >
      <?php $def = getHKInfo("refinput");
        refinputchooser("refinput",$def);?>
  </td>
</tr>   
<tr>
  <td>Absenkungsart</td>
  <td align=right >
      <?php $def = getHKInfo("redmode");
        redmodechooser("redmode",$def);?>
  </td>
</tr>   

<tr>
  <td>Frostschutzmethode</td>
  <td align=right >
      <?php $def = getHKInfo("frostmode");
        frostmodechooser("frostmode",$def);?>
  </td>
</tr>   

<tr>
  <td>Schaltzeitenoptimierung</td>
  <td align=right >
      <?php $def = getHKInfo("schedoptimizer");
        onoffchooser("schedoptimizer",$def);?>
  </td>
</tr>   



</table>
</td>

<td align=left valign=top bgcolor=#cccccc>
<h3>Standortdaten</h3>

<table width=100%>

<tr>
  <td>Dämpfung Außentemperatur</td>
  <td align=right ><?php onoffchooser("daempfung",getRCInfo("daempfung"));?></td>
</tr>
<script type="text/javascript">progress(50);</script>
<?php flush_buffers();?>
<tr style='visibility:hidden' id=w_da1>
  <td>Gebäudeart</td>
  <td align=right ><?php gebaeudechooser("gebaeude",getRCInfo("gebaeude"));?></td>
</tr>
  
<tr>
  <td>Minimale Aussentemperatur</td>
  <td align=right ><?php tempchooser("minaussentemp",-30,0,1,"°C","waehlen",getRCInfo("minaussentemp"));?></td>
</tr>



</table>


</td>

</tr>


<tr><td  bgcolor=#bbbbbb colspan=4><h2>Warmwasser</h2></td></tr>
<tr>
<td>&nbsp;</td>
<td align=left valign=top bgcolor=#cccccc>
<h3>Parameter</h3>

<table width=100%>
<tr>
  <td>Zirkulation pro Std.</td>
  <td align=right ><?php zirkchooser("spzir",getWWInfo("spzir"));?></td>
</tr>
<script type="text/javascript">progress(50);</script>
<?php flush_buffers();?>
<tr>
  <td>Begrenzung Warmwasser auf</td>
  <td align=right ><?php tempchooser("limittemp",30,80,1,"°C","waehlen",getWWInfo("limittemp"));?></td>
</tr>
<tr>
  <td>LED Einmalladungstaste</td>
  <td align=right >
      <?php $def = getWWInfo("loadled");
        onoffchooser("loadled",$def);?>
  </td>
</tr>   

</table>


</td>
<td align=left  valign=top bgcolor=#cccccc>
<h3>Desinfektion</h3>
<table width=100%>
<tr>
  <td>Betriebsart</td>
  <td align=right ><?php onoffchooser("tdstat",getWWInfo("tdstat"));?></td>
</tr>
<tr style='visibility:hidden' id=w_di1>
  <td>Temperatur</td>
  <td align=right ><?php tempchooser("tdtemp",30,80,1,"°C","wählen",getWWInfo("tdtemp"));?></td>
</tr>   
<tr style='visibility:hidden' id=w_di2>
  <td>Tag</td>
  <td align=right ><?php daychooser("tdday",getWWInfo("tdday"));?></td>
</tr>   
<tr style='visibility:hidden' id=w_di3>
  <td>Stunde</td>
  <td align=right ><?php hourchooser("tdhour",getWWInfo("tdhour"));?></td>
</tr>   
</table>

</td>

</tr>


<tr><td  bgcolor=#bbbbbb colspan=4><h2>Kessel</h2></td></tr>
<tr>
<td>&nbsp;</td>

<td align=left  valign=top bgcolor=#cccccc>
<h3>Taktsperre</h3>
<table width=100%>
<tr>
  <td>Antipendelzeit</td>
  <td align=right ><?php tempchooser("antipen",5,30,1,"min","wählen",getUBAinfo("antipen"));?></td>
</tr>   
<script type="text/javascript">progress(60);</script>
<?php flush_buffers();?>
<tr>
  <td>Einschalthysterese</td>
  <td align=right ><?php tempchooser("hystein",-10,-1,1,"°C","wählen",getUBAinfo("hystein"));?></td>
</tr>   
<tr>
  <td>Ausschalthysterese</td>
  <td align=right ><?php tempchooser("hystaus",1,10,1,"°C","wählen",getUBAinfo("hystaus"));?></td>
</tr>   
</table>

</td>

<td align=left valign=top bgcolor=#cccccc>
<h3>Kesselpumpe</h3>
<table width=100%>
<tr>
  <td>Nachlauf</td>
  <td align=right ><?php tempchooser("kpnachl",1,61,1,"min","wählen",getUBAinfo("kpnachl"),array("61"=>"24 h"));?></td>
</tr>   
<tr>
  <td>min. Leistung </td>
  <td align=right ><?php tempchooser("kpmin",55,100,5,"%","wählen",getUBAinfo("kpmin"));?></td>
</tr> 
<tr>  
  <td>max. Leistung </td>
  <td align=right ><?php tempchooser("kpmax",55,100,5,"%","wählen",getUBAinfo("kpmax"));?></td>
</tr>   
</table>

</td>

</tr>

<tr>
<td></td>
<td align=left valign=top bgcolor=#cccccc>
<h3>Wartung</h3>
<table width=100%>
<tr>
  <td>Wartungsmeldungen</td>
  <td align=right ><?php maintchooser("mtmode",getMaintenanceInfo("mtmode"));?></td>
</tr>
<tr id='w_dat' style='visibility:hidden'> 
  <td>
  nächstes Wartungsdatum</td>
  <td align=right >

  <?php
    $def = getMaintenanceInfo("mtdate");
    datechooser("mtdate",$def);
  ?>
  </td>
</tr>   
<tr id='w_hr' style='visibility:hidden'> 
  <td>Wartungsbetriebsstunden</td>
  <td align=right >
  <?php
    $def = getMaintenanceInfo("mthours")*100;
    tempchooser("mthours",100,6000,100,"h","wählen",$def);
    ?>
  </td>
</tr>   
</table>
<script type="text/javascript">progress(70);</script>
<?php flush_buffers();?>
</td>

<td  align=left valign=top bgcolor=#cccccc>
<h3>Allgemein</h3>
<table width=100%>


<tr>
  <td valign=top>Kontaktdaten</td>
  <td align=right >
  <?php
    $cl1 = htmlentities(getContactInfo(1));
    $cl2 = htmlentities(getContactInfo(2));
    print('<input style="font-family: monospace;" name=contact1 id=contact1 type=text size=21 maxlength=21 value="'.$cl1.'"><br>');
    print('<input style="font-family: monospace;" name=contact2 id=contact2 type=text size=21 maxlength=21 value="'.$cl2.'"><br>');
    ?>
  </td>
</tr>   

</table>
</td>
</tr>
<tr><td id=statusbar colspan=3  bgcolor=#bbbbbb >Bereit</td></tr>
</table>
</form>
<script type="text/javascript">
progress(80);

for (n=0; n<selsources.length; n++){
  var sel = document.getElementById(selsources[n]);
  if (!sel) alert (selsources[n]+" not def");
  sel.onfocus = function(){blockEnable(this.id);};
  sel.onchange = function(){setValue(this.id);};
  sel.onblur = function(){resetEnable(this.id);};
}
document.getElementById("vacation_von").onblur = function(){setValue("vacation");};
document.getElementById("vacation_bis").onblur = function(){setValue("vacation");};
document.getElementById("vacation_von").onfocus = function(){blockEnable("vacation");};
document.getElementById("vacation_bis").onfocus = function(){blockEnable("vacation");};
document.getElementById("holiday_von").onblur = function(){setValue("holiday");};
document.getElementById("holiday_bis").onblur = function(){setValue("holiday");};
document.getElementById("holiday_von").onfocus = function(){blockEnable("holiday");};
document.getElementById("holiday_bis").onfocus = function(){blockEnable("holiday");};
document.getElementById("mtdate").onblur = function(){setValue("mtdate");};
document.getElementById("mtdate").onfocus = function(){blockEnable("mtdate");};
document.getElementById("contact1").onblur = function(){setValue(this.id);};
document.getElementById("contact1").onfocus = function(){blockEnable(this.id);};
document.getElementById("contact2").onblur = function(){setValue(this.id);};
document.getElementById("contact2").onfocus = function(){blockEnable(this.id);};


setInterval(function(){showInfos();},1000);
progress(90);

</script>

<?php
flush_buffers();
close_ems();
?>
