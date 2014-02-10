<h1>
Statistik
<hr></h1>
<form method="post">

<?php
include("a_emsmenu.inc");

function beginTable(){
?>
<p>
<table border=0 cellpadding=4 cellspacing=5 bgcolor=#eeeeee>
<?php
}

function showline($k, $v){
  print("<tr><td bgcolor=#cccccc><font size=-1>$k</font></td><td bgcolor=#cccccc align=right><font face='Courier,Lucida Console,fixedsys' size=-1 ><b>$v</b></font></td><tr>\n");
  flush();
}

function parseTimeStr($in,$line=""){
  foreach ($in as $l){
    if (($l2 = str_replace("DATA: ".$line." = ","",$l))!=$l){
      $l2 = trim($l2);
      // line found! parse it.
      if (strpos($l2,".")){
        $l3 = explode(".",$l2);
        $min = substr("  ".trim($l3[1]*6),-2);
        $l2 = $l3[0]."h&nbsp;".$min."min";
      } else {
        $l3 = explode(" ",$l2);
        $l2 = $l3[0]."&nbsp;".substr("  ".trim($l3[1]),-5);
        
      }
      return $l2;
    }
  }
}

function getStr($in,$line="",$prefix="DATA: ",$postfix=" = "){
  foreach ($in as $l){
    if (($l2 = str_replace($prefix.$line.$postfix,"",$l))!=$l){
      $l2 = trim($l2);
      return $l2;
    }
  }
}

function getlivedata(){
  global $livedat;
  
  if (isset($livedat)){
    return $livedat;
  } else {
    $in = getEmsLiveData();
    $out = array();
    foreach ($in as $k => $v){
      $out[] = "DATA: ".trim($k)." = ".trim($v);
    }
    $livedat = $out;
    return $out;
  }
}

function parseVersion($in){
  $vmaj = "";
  $vmin = "";
  foreach ($in as $l){
    if (($l2 = str_replace("DATA: Version Major number = ","",$l))!=$l) $vmaj=$l2;
    if (($l2 = str_replace("DATA: Version Minor number = ","",$l))!=$l) $vmin=$l2;
  }  
  $vmaj  = trim($vmaj);
  $vmin  = trim($vmin);
  $vmin  = substr("00".$vmin,-2);
  return ($vmaj.".".$vmin);
}

require("emsqry.inc");
if (!open_ems()) die ("<h3>Keine Verbindung zum EMS-Bus möglich</h3>");
$in = array("Betriebsstunden total" => parseTimeStr(doEmsCommand("totalhours"),"Betriebszeit Gesamtanlage"),
            "Betriebsstunden Kessel" => $bk=parseTimeStr(getlivedata(),"Betriebszeit total"),
            "Heizzeit" => parseTimeStr(getlivedata(),"Betriebszeit Heizen"),
            "Warmwasserbereitungszeit" => parseTimeStr(getlivedata(),"Warmwasserbereitungszeit"),
            "Warmwasserbereitungen" => getStr(getlivedata(),"Warmwasserbereitungen"),
            "Brennerstarts" => $bs=getStr(getlivedata(),"Brennerstarts"),
            "Durchschnittliche Brennerlaufzeit" => round(reset(explode("h",$bk)) / $bs *60 ,1)."min",
            
            "Softwareversion UBA" => parseVersion(doEmsCommand("getversion 08")),
            "Softwareversion BC10" => parseVersion(doEmsCommand("getversion 09")),
            "Softwareversion RC35" => parseVersion(doEmsCommand("getversion 10")),
            "Version EMS-Collector" => getStr(doEmsCommand("getversion me"),"ems-collectord","",""));
            


print("<table cellspacing=14><tr><td>");

beginTable();

foreach ($in as $edesc => $data){
  showline($edesc,$data);
  if ($edesc == "Warmwasserbereitungen") {
    print("</table></td><td>");
    beginTable();
  }
}
print("</td></tr></table>");
?>

</table>
<p>
<input type=submit value="Aktualisieren">
</form>