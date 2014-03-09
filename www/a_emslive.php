<h1>
Heizung Livestatus
<hr>
</h1>
<table id=prog width=250px bgcolor=#cccccc cellspacing=0 cellpadding=5>
<tr bgcolor=#ffffff height=50px><td colspan=4></td></tr>
<tr height=30px><td colspan=4 bgcolor=#cccccc><div id=prc align=center></div></td></tr>
<tr height=3px>
<td id=p0 bgcolor=#eeeeee></td>
<td id=p1 bgcolor=#dddddd></td>
<td id=p2 bgcolor=#cccccc></td>
<td id=p3 bgcolor=#bbbbbb></td>
</tr>
</table>
<script type="text/javascript">
function progress(perc){
    document.getElementById("prc").innerHTML="<b>Bitte warten...</b> ("+perc+"%)";
  for (i=0; i<=perc/30; i++){
    document.getElementById("p"+i).style.backgroundColor="#8888ff";
  }
}
progress(10);
</script>
<?php 
require("/emsincludes/emssetvalue.inc");
require("/emsincludes/emsgetinfo.inc");
require("/emsincludes/emschoosers.inc");
flush_buffers();
$temptemp = getHKInfo("temptemp");
if ($temptemp==0){
  if (getHKInfo("tagbetr")=="on"){
    $temptemp =  getHKInfo("day");
  } else {
    $temptemp =  getHKInfo("night");
  }  

}
?>
<table id=main style="display: none;"><tr><td valign=top>
<script type="text/javascript">

var ttemp = <?php print($temptemp);?> ;
var sources = new Array('heizactive','wwactive','redmode','tagbetr','autobetr','party','day','night','pause','wwtag','wwmode','zirmode','temptemp');
var selsources = new Array('party','day','night','pause','wwtag','wwmode','zirmode');
var ttempoff = <?php print($temptemp);?> ;
var qenabled = new Array();
var currsrc = 0;
var activereq = false;
var activeRequest = false;
var uline = "";

for (n=0; n<sources.length; n++){
  qenabled[sources[n]]=true;
}

progress(20);
function addv(){
  blockEnable("temptemp");
  ttemp=ttemp+0.5;
  uline = "<u>";
  dispv();
}

function subv(){
  blockEnable("temptemp");
  ttemp=ttemp-0.5;
  uline = "<u>";
  dispv();
}

function blockEnable(src){
  qenabled[src] = false;
}

function resetEnable(src){
  qenabled[src] = true;
}

function dispv(){
  if (ttemp==0){
    ttemp = parseFloat(ttempoff);
    uline = "";
  }
  if (ttemp<5) ttemp=5;
  if (ttemp>30) ttemp=30;
  var sel = document.getElementById('cnt');
  sel.innerHTML = uline+ttemp.toFixed(1)+'</u>';
}

xmlhttp=new XMLHttpRequest();
xmlhttp.onreadystatechange=function()
{
  if (xmlhttp.readyState==4 && xmlhttp.status==200){
    var rline = xmlhttp.responseText.split("\n");
    for (r=0; r<rline.length; r++){
      if (rline[r].length < 3) continue;
      var info = rline[r].split("=");
      var src = info[0];
      var val = info[1];

      if (src == "redmode"){
        if (val == "offmode"){
            document.getElementById("nightvalue").style.display="none";
            document.getElementById("nightoff").style.display="inline";
          } else {
            document.getElementById("nightvalue").style.display="inline";
            document.getElementById("nightoff").style.display="none";
          }
      } else if (src == "wwactive"){
        if (val == "off"){
            document.getElementById("wwtemp").style.display="none";
            document.getElementById("oneloadchooser").style.display="none";
            document.getElementById("wwdisabled").style.display="table-cell";
          } else {
            document.getElementById("wwtemp").style.display="table-cell";
            document.getElementById("oneloadchooser").style.display="table-cell";
            document.getElementById("wwdisabled").style.display="none";
          }
      } else if (src == "heizactive"){
        if (val == "off"){
          document.getElementById("daychooser").style.display="none";
          document.getElementById("nightchooser").style.display="none";
          document.getElementById("ttempchooser").style.display="none";
          document.getElementById("heizdisabled").style.display="table-cell";
        } else {
          document.getElementById("daychooser").style.display="table-cell";
          document.getElementById("nightchooser").style.display="table-cell";
          document.getElementById("ttempchooser").style.display="table-cell";
          document.getElementById("heizdisabled").style.display="none";
        }
      } else if (src == "temptemp"){
        ttemp=parseFloat(val);
        var ttbgcol = document.getElementById("ttbgcol");
        if (ttemp == 0){
           ttbgcol.style.backgroundColor="#eeeeee";
        } else {                                
           ttbgcol.style.backgroundColor="#ffffaa";
        }
        
        dispv();
      } else if (src=="tagbetr"){
        var mdsel = document.getElementById("daym");
        var mnsel = document.getElementById("nightm");
        var tdsel = document.getElementById("day");
        var tnsel = document.getElementById("night");
        if (val=="on"){
          mdsel.style.backgroundColor="#FF5555";
          mnsel.style.backgroundColor="#cccccc";
          ttempoff = tdsel.options[tdsel.selectedIndex].value;
        } else {
          mdsel.style.backgroundColor="#cccccc";
          mnsel.style.backgroundColor="#5555FF";
          ttempoff = tnsel.options[tnsel.selectedIndex].value;
        }       

      } else if (src=="autobetr"){
        var masel = document.getElementById("autom");
        if (val=="on"){
          masel.style.backgroundColor="#ffffaa";
        } else {
          masel.style.backgroundColor="#cccccc";
        }       
      
      } else {
        var sel = document.getElementById(src);
        if (!sel) alert("Element "+src+" invalid!");
        for (n=0; n < sel.length; n++){
          if (!activereq && (sel.options[n].value==val)) sel.selectedIndex=n;
        }
      }
    }
    activeRequest=false;
  }
}

function setTempTemp()
{
  activereq = true;
  xmlhttp3=new XMLHttpRequest();
  xmlhttp3.onreadystatechange=function()
  {
    if (xmlhttp3.readyState==4 && xmlhttp3.status==200){
      resetEnable("temptemp");
      activereq = false;
      uline = "";
      dispv();
    }
  }
  blockEnable("temptemp");
  document.getElementById("statusbar").innerHTML="Temperatur temporär geändert auf "+ttemp+" &deg;C";
  xmlhttp3.open("GET","ajax.php?seite=emssetval.ajax&source=temptemp&value="+ttemp,true);
  xmlhttp3.send();
}

function setTempTempOff()
{
  activereq = true;
  xmlhttp3=new XMLHttpRequest();
  xmlhttp3.onreadystatechange=function()
  {
    if (xmlhttp3.readyState==4 && xmlhttp3.status==200){
      resetEnable("temptemp");
      activereq = false;
      uline = "";      
    }
  }
  blockEnable("temptemp");
  document.getElementById("statusbar").innerHTML="Temporäre Raumtemperatur zurückgesetzt.";
  xmlhttp3.open("GET","ajax.php?seite=emssetval.ajax&source=temptemp&value=off",true);
  xmlhttp3.send();
}



function setValue(src)
{
  activereq = true;
  xmlhttp2=new XMLHttpRequest();
  xmlhttp2.onreadystatechange=function()
  {
    if (xmlhttp2.readyState==4 && xmlhttp2.status==200){
      resetEnable(src);
      activereq = false;
    }
  }

  var sel = document.getElementById(src);
  var newval = sel.options[sel.selectedIndex].value;
  blockEnable(src);
  document.getElementById("statusbar").innerHTML="Wert '"+src+"' gesetzt auf '"+newval+"'.";
  xmlhttp2.open("GET","ajax.php?seite=emssetval.ajax&source="+src+"&value="+newval,true);
  xmlhttp2.send();
}

function setCommand(cmd, param)
{
  activereq = true;
  xmlhttp2=new XMLHttpRequest();
  xmlhttp2.onreadystatechange=function()
  {
    resetEnable("tagbetr");
    resetEnable("autobetr");
    activereq = false;
  }
  document.getElementById("statusbar").innerHTML="Befehl '"+cmd+" "+param+"' ausgeführt.";
  xmlhttp2.open("GET","ajax.php?seite=emssetval.ajax&source="+cmd+"&value="+param,true);
  xmlhttp2.send();
}

function showInfos(){
  if (!activeRequest){
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
progress(30);
</script>
<?php flush_buffers();?>
<form method=post> 
<table border=0 cellspacing=1 cellpadding=7>
<tr><td bgcolor=#bbbbbb colspan=3><h3>Betriebsart</h3></td></tr>

<tr>
<td rowspan=1 ></td>
<td align=center colspan=2 bgcolor=#cccccc>
<table cellpadding=2><tr>
<td id=daym><input name=hkmode type=button value=Tag onclick=setCommand("hkmode","day");></td>
<td id=nightm><input name=hkmode type=button value=Nacht onclick=setCommand("hkmode","night");></td>
<td id=autom><input name=hkmode type=button value=Auto onclick=setCommand("hkmode","auto");></td>
</table>
</td>   
</tr>
<tr><td bgcolor=#bbbbbb colspan=3><h3>Heizkreis</h3></td></tr>

<tr>
<td rowspan=3></td>
<td id=ttempchooser colspan=2 bgcolor=#cccccc align=center>
<table bgcolor=#eeeeee border=0>
<tr>
<td bgcolor=#eeeeee><a href="javascript:void();" onclick=subv();><img src=img/minus.png border=0></a></td>
<td bgcolor=#eeeeee id=ttbgcol width=90px align=center><font size=+1>
<span id=cnt>
<?php 
printf("%1.1f",$temptemp);
?>
</span></font><font size=-1.5><sup>°C</sup></td>
<td bgcolor=#eeeeee><a href="javascript:void(); " onclick=addv();><img src=img/plus.png border=0></a></td>
<td><input name=settemptemp type=button value='Set' onclick=setTempTemp(); ><br><input name=settemptempoff type=button value='Reset' onclick=setTempTempOff();></td>
</tr>
</table>
</td>
<td id=heizdisabled style="display: none;" colspan=2 align=center bgcolor=#ff7777>Heizfunktion am Kessel deaktiviert!
</td>
</tr>

<script type="text/javascript">
progress(40);
</script>
<?php flush_buffers();?>
<tr>
<td id=daychooser align=left valign=top bgcolor=#cccccc>
Tagtemperatur<br><center>
<?php tempchooser("day",10,30,0.5,"°C","waehlen",getHKInfo("day"));?>
</td>   
<td id=nightchooser align=left valign=top bgcolor=#cccccc>
Nachttemperatur<br><center>
<span id=nightvalue>
<?php tempchooser("night",10,30,0.5,"°C","waehlen",getHKInfo("night"));?>
</span>
<span id=nightoff style="display:none;">
<b>Heizung<br>aus</b>
</span>

</td>   
</tr><tr>
<td align=left bgcolor=#cccccc>
Partymodus<br><center>
<?php tempchooser("party",1,48,1,"h","aus",getPartyInfo("party"));?>
</td>   


<td align=left bgcolor=#cccccc>
Pausemodus<br><center>
<?php tempchooser("pause",1,48,1,"h","aus",getPartyInfo("pause"));?>
</td>   
</tr>

<tr><td  bgcolor=#bbbbbb colspan=3><h3>Warmwasser</h3></td></tr>
<tr>
<td rowspan=2></td>
<td id=wwtemp align=left bgcolor=#cccccc>
Temperatur<br><center>
<?php tempchooser("wwtag",30,80,1,"°C","wählen",getWWinfo("wwtag"));?>
</td>

<td id=wwdisabled style="display: none;" align=center colspan=2 bgcolor=#ff7777>
Warmwasser am Kessel deaktiviert
</td>   

<td id=oneloadchooser align=left bgcolor=#cccccc>
Einmalladung<br><center>
<input name=wwload type=button value=Starten onclick=setCommand("wwload","");><br>
<input name=wwload type=button value=Abbrechen onclick=setCommand("wwstopload","");>
</td>   
</tr>
<tr>
<td align=left bgcolor=#cccccc>
Betriebsart<br><center>
<?php modechooser("wwmode",getWWinfo("wwmode"));?>
</td>   
<td align=left bgcolor=#cccccc>
Zirkulation<br><center>
<?php modechooser("zirmode",getWWinfo("zirmode"));?>
</td>   
</tr>
<tr>
<td bgcolor=#bbbbbb colspan=3>
<div id=statusbar  style="font-size: 6pt;">
Bereit.</div>
</td></tr>
</table>
</form>
<?php flush_buffers();sleep(1);?>
<script type="text/javascript">
progress(50);
</script>
<?php flush_buffers();?>
<?php doEmsCommand("hk1 requestdata");?>

<script type="text/javascript">
progress(60);
for (n=0; n<selsources.length; n++){
  var sel = document.getElementById(selsources[n]);
  sel.onfocus = function(){blockEnable(this.id);};
  sel.onchange = function(){setValue(this.id);};
  sel.onblur = function(){resetEnable(this.id);};
}
</script>
<?php flush_buffers();?>

</td><td valign=top>


<script type="text/javascript">

var windownr=0;

function showLiveInfo()
 {
   xmlhttpI=new XMLHttpRequest();
   xmlhttpI.onreadystatechange=function()
   {
   if (xmlhttpI.readyState==4 && xmlhttpI.status==200)
     {
     if (windownr) {
        document.getElementById("infotable").innerHTML=xmlhttpI.responseText;
        progress(90);
     } else {
        document.getElementById("desctable").innerHTML=xmlhttpI.responseText;
        document.getElementById("main").style.display="block";
        document.getElementById("sub").style.display="block";
        document.getElementById("prog").style.display="none";
        progress(100);
        
     }
     

     }
   }
 windownr=1-windownr;
 xmlhttpI.open("GET","ajax.php?seite=lemscnt.ajax&id="+windownr+"&<?php echo htmlspecialchars(SID); ?>",true);
 xmlhttpI.send();
 }
progress(70);

setInterval(function(){showLiveInfo()},1000);
setInterval(function(){showInfos();},2000);

</script>
<?php flush_buffers();
close_ems();
?>

<table border=0 cellpadding=7 cellspacing=1>
<tr><td bgcolor=#cccccc colspan=2>
<h3>Status</h3>
</td></tr>
<tr><td></td>
<td bgcolor=#cccccc>
<div id=infotable>
</div>
</td></tr>
</table>

</td></tr>
</table>
<p>
<table id=sub border=0 cellpadding=7 cellspacing=1 width=100% style="display: none;">
<tr><td bgcolor=#bbbbbb colspan=2>
<h3>Beschreibung</h3>
</td></tr>
<tr>
<td></td>
<td bgcolor=#cccccc>
<div id=desctable >
</div>
</td></tr>
</table>

