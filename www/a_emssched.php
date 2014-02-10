<?php
include("emsqry.inc");
if (open_ems()){

$changed = false;
$currsrc = "hk1-1";
$ca = explode("-",$currsrc);
$currtgt = $ca[0];
$currsub = $ca[1];

$sch = false;

if (isset($_POST["emstgt"])){

  $currsrc = trim($_POST["emstgt"]);
  $ca = explode("-",$currsrc);
  $currtgt = $ca[0];
  $currsub = $ca[1];
  
  switch (trim($_POST["emstrans"])){
    case "Lesen":
      $sch = readSchedule($currtgt,$currsub);
      break;
    case "Schreiben":
      $sch = html2sched($_POST,"schedule");
      programSchedule($sch,$currtgt,$currsub);
      $sch = readSchedule($currtgt,$currsub);
      break;
    case "Aktualisieren":
      $sch = html2sched($_POST,"schedule");
      $changed = true;
      break;
  
  }
}

if ($sch===false){
   $sch = readSchedule($currtgt,$currsub);
}

if (isset($_POST["setmode"])){
  
  doEmsCommand("hk1 chooseschedule ".trim($_POST["hk1"]));
  doEmsCommand("ww chooseschedule ".trim($_POST["ww"]));
  doEmsCommand("ww zirkpump chooseschedule ".trim($_POST["zir"]));

}




$emstgt= array("Heizkreis Eig.1" => "hk1-1",
               "Heizkreis Eig.2" => "hk1-2",
               "Warmwasser" => "ww-",
               "Zirkulation" =>  "wwzirkpump-");


$emspathk = array("Familie"=>"Familie",
                  "Morgen"=>"Morgen",
                  "Früh"=> "Frueh",
                  "Abend"=> "Abend",
                  "Vormittag"=> "Vorm",
                  "Nachmittag"=> "Nachm",
                  "Mittag"=>"Mittag",
                  "Single"=>"Single",
                  "Senioren"=>"Senioren",
                  "Eigenes Prg. 1"=>"Eigen1",
                  "Eigenes Prg. 2"=>"Eigen2");

$emspatww = array("Eigenes Prog." => "Eigen1",
                  "wie Heizkreis" => "Heizkreis");

$emspatzi = array("Eigenes Prog."  => "Eigen1",
                  "wie Warmw." => "Heizkreis");
                  
                  
?>
<h1>
Schaltprogramme
<hr>
</h1>&nbsp;
<?php
print("<form method=post>");
schedule2graph($sch,"schedule.png");

print("<table cellpadding=7 cellspacing=3><tr><td rowspan=2 width=100%>");
print("<img src=/graphs/schedule.png?t=".time()." width=100%><p>");
print("</td><td valign=top bgcolor=#cccccc width=200px>");


print("<h3>RC35-Speicher</h3>");
print("<select name=emstgt>");
foreach ($emstgt as $e => $ei){
  print("<option value=$ei ".($currsrc==$ei?"selected":"")." >$e</option>");
}
print("</select><br>");
print("<input type=submit name=emstrans value=Lesen><br>");
print("<input type=submit name=emstrans value=Schreiben><br>");
print("<input type=submit name=emstrans value=Aktualisieren><br>");


print("</td></tr><tr><td valign=top bgcolor=#cccccc>");



print("<h3>Programme</h3>");

$act = getActSchedule("hk1");
print("<b>Heizkreis</b><br>");
print("<select name=hk1>");
foreach ($emspathk as $e => $ei){
  print("<option value=$ei ".($act==$ei?"selected":"").">$e</option>");
}
print("</select><br>");

$act = getActSchedule("ww");
print("<b>Warmwasser</b><br>");
print("<select name=ww>");
foreach ($emspatww as $e => $ei){
  print("<option value=$ei ".($act==$ei?"selected":"").">$e</option>");
}
print("</select><br>");

$act = getActSchedule("zir");
print("<b>Zirkulation</b><br>");
print("<select name=zir>");
foreach ($emspatzi as $e => $ei){
  print("<option value=$ei ".($act==$ei?"selected":"").">$e</option>");
}
print("</select><br>");

print("<p><input type=submit name=setmode value=Setzen>");

print("</td></tr></table>");

$desc = end(array_keys($emstgt,$currsrc));
if ($changed) $desc .= " (Geändert)";
print("<table cellpadding=10px>");

print("<tr><td bgcolor=#bbbbbb colspan=7><h3>$desc</h3></td></tr>");

schedule2html($sch ,"schedule");


?>

</table>
</form>

<?php

close_ems();
} else die("<h3>Keine EMS-Verbindung möglich!</h3>");
